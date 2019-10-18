<?php

namespace App\Http\Controllers;

use App\CartCalculator;
use App\DeliveryMethod;
use App\Http\Requests;
use App\Http\Utilities\Country;
use App\Mail\SendToFriend;
use App\Order;
use App\PaymentMethod;
use App\Product;
use App\Profit;
use App\ShippingCost;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Validator;

class CartController extends Controller
{
    public function index()
    {
        $orders = Order::where('order_status_id', 0)->get();
        if(count($orders) > 0) {
            foreach ($orders as $order) {
                //$order->delete();
            }
        }

        // dd(Cart::content());
        $cartCalculator = new CartCalculator;

        $tireCount = $rimsCount = 0;
        $addedMount = null;
        $addedKit = null;
        $cantOrder = false;
        if(sizeof(Cart::content()) > 0) {
            foreach(Cart::content() as $item) {
                // if (\App::environment('production')) {
                //     if($item->model->id === 15833) {
                //         $addedMount = true;
                //     }
                // } else {
                //     if($item->model->id === 4) {
                //         $addedMount = true;
                //     }
                // }
                if($item->model->id == 4) {
                    $addedMount = true;
                }

                if($item->model->id == 2) {
                    $addedKit = true;
                }

                if($item->options->product_category_id == 1)
                    $tireCount += $item->qty;

                if($item->options->product_category_id == 2)
                    $rimsCount += $item->qty;

            }
        }
        $cantOrder = ($tireCount>0 and $tireCount<4) ? true : $cantOrder;
        $cantOrder = ($rimsCount>0 and $rimsCount<4) ? true : $cantOrder;
        // $addedMount =  empty($inchArr)?  true : null;
        $cookie = json_decode(Cookie::get('cookie'), true);
        if(isset($cookie['addMount'])) $addedMount = true;
        if(isset($cookie['addKit'])) $addedKit = true;
        if(isset($cookie['addTPMS'])) $addedTPMS = true;
        if(isset($cookie['addCarChange'])) $addedCarChange = true;
        if(isset($cookie['addLockKit'])) $addedLockKit = true;
        $cookie = json_encode($cookie);
        Cookie::queue('cookie', $cookie, 60*24*7);

        $carSearch = json_decode(Cookie::get('carSearch'), true);
        $regNumber = $carSearch['searchData']['RegNumber'];
        $orderInfo = Session::get('orderInfo');

        $deliveryMethods = DeliveryMethod::all();
        $paymentMethods = PaymentMethod::all();

        // $totalPrice = str_replace(' ', '', Cart::total());
        // if($totalPrice < 5000) {
        //     Session::forget('campaign');
        // }

        // dd($inchArr);
        // $addedMount = null;

        $countries = Country::all();

        return view('cart.cart', compact('addedMount', 'addedKit', 'addedTPMS', 'addedCarChange', 'addedLockKit', 'cartCalculator', 'regNumber', 'orderInfo', 'deliveryMethods', 'paymentMethods', 'countries', 'tireCount', 'cantOrder'));
    }

    public function store(Request $request)
    {

        // dd($request->all());
        if($request->id == 1) {
            $cookie = json_decode(Cookie::get('cookie'), true);
            $cookie['addTPMS'] = true;
            $cookie = json_encode($cookie);
            Cookie::queue('cookie', $cookie, 60*24*7);
        }

        if($request->id == 2) {
            $cookie = json_decode(Cookie::get('cookie'), true);
            $cookie['addKit'] = true;
            $cookie = json_encode($cookie);
            Cookie::queue('cookie', $cookie, 60*24*7);
        }

        // if($request->id == 4) {
        //     $cookie = json_decode(Cookie::get('cookie'), true);
        //     $cookie['addCarChange'] = true;
        //     $cookie = json_encode($cookie);
        //     Cookie::queue('cookie', $cookie, 60*24*7);
        // }

        if($request->id == 3) {
            $cookie = json_decode(Cookie::get('cookie'), true);
            $cookie['addLockKit'] = true;
            $cookie = json_encode($cookie);
            Cookie::queue('cookie', $cookie, 60*24*7);
        }

        $product = Product::find($request->id);
        $shipping = ShippingCost::where('product_type_id', $product->product_type_id)->first();
        // dd($shipping);
        // if($product->product_category_id === 2 && strpos($product->product_name, 'Blank')) {
        //     $shipping->cost += 100;
        // }
        $shippingCost = $shipping->cost * $request->quantity;

        // $this->validate($request, [
        //     'quantity' => "required|max:{$quantity}",
        // ]);
        if($product->min_orderble_quantity <=  $product->quantity ) {
            $validator = Validator($request->all(), [
                'quantity' => "required|numeric|min:{$product->min_orderble_quantity}|max:{$product->quantity}",
            ]);
        } else {
            $validator = Validator($request->all(), [
                'quantity' => "required|numeric|min:1|max:{$product->quantity}",
            ]);
        }

        if ( $validator->fails() ) {
            // $errors = $validation->errors();
            return redirect()->back()->withErrors($validator);
        }

        $pcd = isset($request->pcd) ? $request->pcd : null;

        $cartItem = Cart::add(
            $request->id,
            $request->name,
            $request->quantity,
            $product->webPrice(),
            [
                'shipping_cost' => $shippingCost,
                'product_category_id' => $product->product_category_id,
                'product_type_id' => $product->product_type_id,
                'et' => $product->et,
                'pcd' => $pcd
            ]
        )->associate('App\Product','App\Models');

        $gotCompleteRims = false;
        $gotCompleteTires = false;
        $gotRims = false;
        $gotTires = false;

        $monteringsKit = Product::find(2);
        $id = $monteringsKit->id;
        $existMonteringsKit = Cart::search(function($cartItem) use ($id) {
            return $cartItem->id === (string) $id;
        });


        $balansering = Product::find(4);
        $id = $balansering->id;
        $existBalansering = Cart::search(function($cartItem) use ($id) {
            return $cartItem->id === (string) $id;
        });

        // dd($existMonteringsKit, sizeof($existMonteringsKit) > 0,  $existBalansering, !sizeof($existBalansering) > 0, $gotCompleteTires, $gotCompleteRims);


        if(sizeof(Cart::content()) > 0) {
            foreach(Cart::content() as $item) {

                if($item->options->product_category_id == 2 || $item->options->product_category_id == 1) {
                    $qtyArr[] = $item->qty;
                }

                $inchArr[] = $item->model->product_inch;


                if($item->options->product_category_id == 2)
                    $gotRims = true;

                if($item->options->product_category_id == 1)
                    $gotTires = true;

                if($item->options->product_category_id == 2 && $item->qty >= 4)
                    $gotCompleteRims = true;

                if($item->options->product_category_id == 1 && $item->qty >= 4)
                    $gotCompleteTires = true;
            }

            if($gotCompleteRims && sizeof($existMonteringsKit) <=0) {
                $cartItem = Cart::add(
                    (string) $monteringsKit->id,
                    $monteringsKit->product_name,
                    "1",
                    $monteringsKit->webPrice(),
                    [
                        'product_category_id' => $monteringsKit->product_category_id
                    ]
                )->associate('App\Product','App\Models');
            }

            if($gotRims && $gotTires && sizeof($existBalansering) <=0) {
                $inch = max($inchArr);
                $qty = min($qtyArr);

                if(!empty($inch)) {
                    $mountPrice = 0;
                    $mountPrice = Profit::where('product_type', 3)
                            ->where('size', $inch)
                            ->first()->mount;

                    $cartItem = Cart::add(
                        (string) $balansering->id,
                        $balansering->product_name,
                        $qty,
                        $mountPrice,
                        [
                            'product_category_id' => $balansering->product_category_id
                        ]
                    )->associate('App\Product','App\Models');
                }
            }
            // dd($existMonteringsKit, $existBalansering, $gotCompleteRims, $gotCompleteTires);
        }

        $id = $request->id;
        $existingItem = Cart::search(function($cartItem) use ($id) {
            return $cartItem->id == $id;
        });
        // dd($existingItem);

        $carSearch = json_decode(Cookie::get('carSearch'), true);
        $completeTiresProcess = isset($carSearch['completeTiresProcess']) ? $carSearch['completeTiresProcess'] : null;
        if($completeTiresProcess == 1) {
            if($existingItem->first()->qty > $product->quantity && $product->product_category_id !== 2) {
                Cart::update($existingItem->first()->rowId, $product->quantity);
                session()->flash('error_message', "Kvantiten får inte överstiga lagersaldot {$product->quantity}!");
                return redirect('varukorg');
            } else {
                // return redirect('sok/reg/kompletta-hjul/dack')->withSuccessMessage('Varan har lagts till din varukorg!');
                return redirect('sok/reg/kompletta-hjul/dack');
            }
        }
        if($completeTiresProcess == 2) {
            if($existingItem->first()->qty > $product->quantity && $product->product_category_id !== 2) {
                Cart::update($existingItem->first()->rowId, $product->quantity);
                session()->flash('error_message', "Kvantiten får inte överstiga lagersaldot {$product->quantity}!");
                return redirect('varukorg');
            } else {
                // return redirect('varukorg')->withSuccessMessage('Varan har lagts till din varukorg!');
                return redirect('varukorg');
            }
        }

        $notification = [
            'productInCart' => 'Varan har lagts i varukorgen',
            'alert-typ' => 'success'
        ];

        if($existingItem->first()->qty > $product->quantity && $product->product_category_id !== 2) {
            Cart::update($existingItem->first()->rowId, $product->quantity);
            session()->flash('error_message', "Kvantiten får inte överstiga lagersaldot {$product->quantity}!");
            return redirect('varukorg');
        } else {
            // return redirect()->back()->withSuccessMessage('Varan har lagts till din varukorg!');
            return redirect()->back()->with($notification);
        }


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $rowId)
    {
        $product = Product::find($request->id);
        $shippingCost = 0;
        if($product->product_category_id === 1 || $product->product_category_id === 2 ){
            $shipping = ShippingCost::where('product_type_id', $product->product_type_id)->first();
            // dd($shipping);
            $shippingCost = $shipping->cost * $request->quantity;
        }

        // Validation on max quantity

        if($product->min_orderble_quantity <=  $product->quantity ) {
             $validator = Validator::make($request->all(), [
                'quantity' => "required|numeric|min:{$product->min_orderble_quantity}|max:{$product->quantity}",
            ]);
        } else {
             $validator = Validator::make($request->all(), [
                'quantity' => "required|numeric|min:1|max:{$product->quantity}",
            ]);
        }
         if ($validator->fails()) {
            $message = $validator->errors();
            session()->flash('error_message', $message->first('quantity'));
            return response()->json(['success' => false]);
        }

        $id = $product->id;
        $existingItem = Cart::search(function($cartItem) use ($id) {
            return $cartItem->id == $id;
        });

        // dd(Cart::get($rowId));
        Cart::update($rowId, [
            'qty' => $request->quantity,
            'options' => [
                'shipping_cost' => $shippingCost,
                'product_category_id' => $product->product_category_id,
                'product_type_id' => $product->product_type_id,
                'et' => $existingItem->first()->options->et,
                'pcd' => $existingItem->first()->options->pcd
            ]
        ]);



        $gotRims = false;
        $gotTires = false;
        $balansering = Product::find(4);
        $id = $balansering->id;
        $existBalansering = Cart::search(function($cartItem) use ($id) {
            return $cartItem->id === (string) $id;
        });

        if(sizeof(Cart::content()) > 0) {
            foreach(Cart::content() as $item) {

                if($item->options->product_category_id == 2 || $item->options->product_category_id == 1) {
                    $qtyArr[] = $item->qty;
                }

                $inchArr[] = $item->model->product_inch;

                if($item->options->product_category_id == 2)
                    $gotRims = true;

                if($item->options->product_category_id == 1)
                    $gotTires = true;

            }
            if($gotRims && $gotTires && sizeof($existBalansering) > 0) {
                $inch = max($inchArr);
                $qty = min($qtyArr);

                if(!empty($inch)) {
                    $mountPrice = 0;
                    $mountPrice = Profit::where('product_type', 3)
                            ->where('size', $inch)
                            ->first()->mount;
                    Cart::update($existBalansering->first()->rowId, [
                        'qty' => $qty,
                    ]);
                }
            }
        }

        // session()->flash('success_message', 'Kvantiten har uppdaterats!');
        return response()->json(['success' => true]);
    }

    public function updateET(Request $request)
    {
        $product = Product::find($request->id);
        // $shippingCost = 0;
        // if($product->product_category_id === 1 || $product->product_category_id === 2 ){
        //     $shipping = ShippingCost::where('product_type_id', $product->product_type_id)->first();
        //     // dd($shipping);
        //     $shippingCost = $shipping->cost * $request->quantity;
        // }

        // Validation on max quantity
        $validator = Validator::make($request->all(), [
            'et' => "required|numeric|min:{$product->et_min}|max:{$product->et}"
        ]);
         if ($validator->fails()) {
            $message = $validator->errors();
            session()->flash('error_message', $message->first('quantity'));
            return response()->json(['success' => false]);
        }

        $id = $request->id;
        $existingItem = Cart::search(function($cartItem) use($id) {
            return $cartItem->id == $id;
        });
        // dd($existingItem->first()->options->et);

        // dd(Cart::get($rowId));
        Cart::update($request->rowId, [
            'options' => [
                'shipping_cost' => $existingItem->first()->options->shipping_cost,
                'product_category_id' => $existingItem->first()->options->product_category_id,
                'et' => $request->et,
                'pcd' => $existingItem->first()->options->pcd
            ]
        ]);

        // session()->flash('success_message', 'ET har uppdaterats!');
        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($varukorg)
    {
        // dd($varukorg);
        $cartItem = Cart::get($varukorg);
        $cookie = json_decode(Cookie::get('cookie'), true);


        if($cartItem->id == 1)
            unset($cookie['addTPMS']);
        if($cartItem->id == 2)
            unset($cookie['addKit']);
        // if($cartItem->id == 4)
        //     unset($cookie['addCarChange']);
        if($cartItem->id == 3)
            unset($cookie['addLockKit']);
        if($cartItem->id == 4)
            unset($cookie['addMount']);

        $cookie = json_encode($cookie);
        Cookie::queue('cookie', $cookie, 60*24*7);

        Cart::remove($varukorg);

        $gotRims = false;
        $gotTires = false;
        $balansering = Product::find(4);
        $id = $balansering->id;
        $existBalansering = Cart::search(function($cartItem) use ($id) {
            return $cartItem->id === (string) $id;
        });

        if(sizeof(Cart::content()) > 0) {
            foreach(Cart::content() as $item) {

                if($item->options->product_category_id == 2 || $item->options->product_category_id == 1) {
                    $qtyArr[] = $item->qty;
                }

                $inchArr[] = $item->model->product_inch;

                if($item->options->product_category_id == 2)
                    $gotRims = true;

                if($item->options->product_category_id == 1)
                    $gotTires = true;

            }
            if($gotRims && $gotTires && sizeof($existBalansering) > 0) {
                $inch = max($inchArr);
                $qty = min($qtyArr);

                if(!empty($inch)) {
                    $mountPrice = 0;
                    $mountPrice = Profit::where('product_type', 3)
                            ->where('size', $inch)
                            ->first()->mount;
                    Cart::update($existBalansering->first()->rowId, [
                        'qty' => $qty,
                    ]);
                }
            }

            if( (!$gotRims || !$gotTires) && sizeof($existBalansering) > 0) {
                Cart::remove($existBalansering->first()->rowId);
            }
        }
        // return redirect()->back()->withSuccessMessage('Varan har tagits bort!');
        return redirect()->back();
    }
    /**
     * Remove the resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function emptyCart()
    {
        $cookie = json_decode(Cookie::get('cookie'), true);
        unset($cookie['addMount']);
        unset($cookie['addKit']);
        unset($cookie['addTPMS']);
        unset($cookie['addCarChange']);
        unset($cookie['addLockKit']);
        $cookie = json_encode($cookie);
        Cookie::queue('cookie', $cookie, 60*24*7);

        Cart::destroy();
        // return redirect('varukorg')->withSuccessMessage('Din varukorg har tömts!');
        return redirect('varukorg');
    }

    public function addMount()
    {
        if(sizeof(Cart::content()) <= 0) { return redirect('varukorg'); }

        $cookie = json_decode(Cookie::get('cookie'), true);
        $cookie['addMount'] = true;
        $cookie = json_encode($cookie);
        Cookie::queue('cookie', $cookie, 60*24*7);
        $inchArr = [];
        $qtyArr = [];

        foreach(Cart::content() as $item) {
            $inchArr[] = $item->model->product_inch;
            $qtyArr[] = $item->qty;
        }
        $inch = max($inchArr);

        if(empty($inch)) { return redirect('varukorg'); }
        // $qty = 1;
        $mountPrice = 0;
        $qty = max($qtyArr);
        $mountPrice = Profit::where('product_type', 3)
                ->where('size', $inch)
                ->first()->mount;

        // if (\App::environment('production')) {
        //     $montering = Product::find(15833);
        // } else {
        //     $montering = Product::find(3);
        // }
        $montering = Product::find(4);
        $cartItem = Cart::add(
            (string) $montering->id,
            $montering->product_name,
            $qty,
            $mountPrice
        )->associate('App\Product','App\Models');

        return redirect('varukorg');
    }

    public function addKit()
    {
        $cookie = json_decode(Cookie::get('cookie'), true);
        $cookie['addKit'] = true;
        $cookie = json_encode($cookie);
        Cookie::queue('cookie', $cookie, 60*24*7);

        // $shipping = ShippingCost::where('product_type_id', 7)->first();
        // $shippingCost = $shipping->cost; //Kolla upp fraktkostander för dessa

        $kit = Product::find(2);
        $cartItem = Cart::add(
            (string) $kit->id,
            $kit->product_name,
            "1",
            $kit->webPrice(),
            [
                'shipping_cost' => 0,
                'product_category_id' => $kit->product_category_id
            ]
        )->associate('App\Product','App\Models');

        return redirect('varukorg');
    }

    public function addTPMS()
    {
        // $inch = [];
        // $qty = [];
        // foreach(Cart::content() as $item) {
        //     $inch[] = $item->model->product_inch;
        //     $qty[] = $item->qty;
        // }
        // $inch = max($inch);
        // $qty = max($qty);

        // $mountPrice = Profit::where('product_type', 3)
        //             ->where('size', $inch)
        //             ->first();

        $cookie = json_decode(Cookie::get('cookie'), true);
        $cookie['addTPMS'] = true;
        $cookie = json_encode($cookie);
        Cookie::queue('cookie', $cookie, 60*24*7);

        // $shipping = ShippingCost::where('product_type_id', 7)->first();
        // $shippingCost = $shipping->cost; //Kolla upp fraktkostander för dessa

        $tpms = Product::find(1);
        $cartItem = Cart::add(
            (string) $tpms->id,
            $tpms->product_name,
            "4",
            $tpms->webPrice(),
            [
                'shipping_cost' => 0,
                'product_category_id' => $tpms->product_category_id
            ]
        )->associate('App\Product','App\Models');

        return redirect('varukorg');
    }

    // public function addCarChange()
    // {
    //     if(sizeof(Cart::content()) <= 0) { return redirect('varukorg'); }

    //     $cookie = json_decode(Cookie::get('cookie'), true);
    //     $cookie['addCarChange'] = true;
    //     $cookie = json_encode($cookie);
    //     Cookie::queue('cookie', $cookie, 60*24*7);

    //     // $shipping = ShippingCost::where('product_type_id', 11)->first();
    //     // $shippingCost = $shipping->cost; //Kolla upp fraktkostander för dessa

    //     $carChange = Product::find(4);
    //     $cartItem = Cart::add(
    //         (string) $carChange->id,
    //         $carChange->product_name,
    //         "1",
    //         $carChange->webPrice(),
    //         [
    //             'shipping_cost' => 0,
    //             'product_category_id' => $carChange->product_category_id
    //         ]
    //     )->associate('App\Product','App\Models');

    //     return redirect('varukorg');
    // }

    public function addLockKit()
    {
        if(sizeof(Cart::content()) <= 0) { return redirect('varukorg'); }

        $cookie = json_decode(Cookie::get('cookie'), true);
        $cookie['addLockKit'] = true;
        $cookie = json_encode($cookie);
        Cookie::queue('cookie', $cookie, 60*24*7);

        // $shipping = ShippingCost::where('product_type_id', 12)->first();
        // $shippingCost = $shipping->cost; //Kolla upp fraktkostander för dessa

        $lockKit = Product::find(3);
        $cartItem = Cart::add(
            (string) $lockKit->id,
            $lockKit->product_name,
            "1",
            $lockKit->webPrice(),
            [
                'shipping_cost' => 0,
                'product_category_id' => $lockKit->product_category_id
            ]
        )->associate('App\Product','App\Models');

        return redirect('varukorg');
    }

    public function campaignCode(Request $request)
    {
        return back()->withErrors(
            ['campaignCode' => trans('Ogiltig kampanjkod.')]
        );

        // dd($request->all());
        $totalPrice = str_replace(' ', '', Cart::total());
        if( strcasecmp($request->campaignCode, "bf2016") == 0 && $totalPrice >= 5000) {
            $request->session()->put('campaign.discount', 800);
            return redirect('varukorg');
        }

        $request->session()->forget('campaign');

        if(strcasecmp($request->campaignCode, "bf2016") == 0 && $totalPrice < 5000)
            $response = 'Du måste beställa produkter för minst 5000 kr, för att rabattkoden skall gälla.';

        if(strcasecmp($request->campaignCode, "bf2016") !== 0)
            $response = 'Ogiltig kampanjkod.';

        return back()->withErrors(
            ['campaignCode' => trans($response)]
        );
    }

    // public function campaignCode(Request $request)
    // {
    //     // dd($request->all());
    //     $totalPrice = str_replace(' ', '', Cart::total());
    //     if( strcasecmp($request->campaignCode, "tioprocentsw") == 0) {
    //         $request->session()->put('campaign.code', 'tioprocentsw');
    //         $request->session()->put('campaign.discount', $totalPrice * 0.1);
    //         return redirect('varukorg');
    //     }

    //     if( strcasecmp($request->campaignCode, "femtonprocentsw") == 0) {
    //         $request->session()->put('campaign.code', 'femtonprocentsw');
    //         $request->session()->put('campaign.discount', $totalPrice * 0.15);
    //         return redirect('varukorg');
    //     }

    //     if( strcasecmp($request->campaignCode, "tjugoprocentsw") == 0) {
    //         $request->session()->put('campaign.code', 'tjugoprocentsw');
    //         $request->session()->put('campaign.discount', $totalPrice * 0.20);
    //         return redirect('varukorg');
    //     }

    //     if(strcasecmp($request->campaignCode, "tioprocentsw") !== 0 || strcasecmp($request->campaignCode, "femtonprocentsw") !== 0 || strcasecmp($request->campaignCode, "tjugoprocentsw") !== 0)
    //         $response = 'Ogiltig kampanjkod.';

    //     return back()->withErrors(
    //         ['campaignCode' => trans($response)]
    //     );
    // }

    // public function calculateDiscountedPrice()
    // {
    //      if(Session::has('campaign.code')) {
    //         $totalPrice = str_replace(' ', '', Cart::total());
    //         if( strcasecmp(Session::get('campaign.code'), "tioprocentsw") == 0) {
    //             Session::put('campaign.discount', $totalPrice * 0.1);
    //         }

    //         if( strcasecmp(Session::get('campaign.code'), "femtonprocentsw") == 0) {
    //             Session::put('campaign.discount', $totalPrice * 0.15);
    //         }

    //         if( strcasecmp(Session::get('campaign.code'), "tjugoprocentsw") == 0) {
    //             Session::put('campaign.discount', $totalPrice * 0.20);
    //         }
    //     }
    // }

    public function printCartOrder()
    {
        $cartCalculator = new CartCalculator;

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('cart/print_cart_order', compact(/*'order', 'carData', 'products',*/ 'cartCalculator'));
        return $pdf->stream();
    }

    public function sendToFriend(Request $request)
    {
        $cartCalculator = new CartCalculator;
        // dd($request->all(), $request->name, $request->mailMessage, $cartCalculator);
        //Någonting här
        Mail::to($request->mailTo)->send(new SendToFriend($request->name, $request->mailMessage, $cartCalculator));
        session()->flash('success_message', 'Mailet har skickats till angiven e-post adress.');
        return redirect()->back();
    }
}
