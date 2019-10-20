<?php

namespace App\Http\Controllers;

use App\CarData;
use App\Http\Requests;
use App\Mail\ConfirmOrderSent;
use App\Mail\ConfirmOrderTreated;
use App\Mail\ItemNotification;
use App\Order;
use App\OrderDetail;
use App\OrderStatus;
use App\Product;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class AdminOrdersController extends Controller
{

    private $connector;
    private $order = null;
    // test
    // private $eid = '7551';
    // private $sharedSecret = 'hFH4ToOoftLKno9';
    // live
    // private $eid = '57655';
    // private $sharedSecret = 'VuZWxmVzXezVNaH';
    protected $userID;
    protected $orderID;
    protected $filterOrder;
    protected $myConfig;
    private $eid;
    private $sharedSecret;

    protected $orderStatus;
    protected $orderFromDate;
    protected $orderToDate;
    protected $orderSearch;

    public function __construct()
    {
        // if (\App::environment('production')) {
        //     $this->myConfig = \Svea\SveaConfig::getProdConfig();
        // } else {
        //     $this->myConfig = \Svea\SveaConfig::getTestConfig();
        // }

        // $this->myOrder = \WebPay::createOrder( $this->myConfig );
    }

    // public function dashboard()
    // {
    // 	return view('admin.dashboard');
    // }

    public function orders(Request $request)
    {
        $orders = $this->getSavedOrderList();
        $savedStatus = $this->orderStatus;
        $savedFromDate = $this->orderFromDate;
        $savedToDate = $this->orderToDate;
        $savedSearch = $this->orderSearch;
        // $orders = Order::orderBy('created_at', 'DESC')->paginate(100);
        // $orderStatus = OrderStatus::where('id', '>', 1)->orderBy('id')->get();
        $orderStatus = OrderStatus::orderBy('id')->get();

        if ($request->ajax()) {

            if ($request->status) {
                $updateOrder = Order::find($request->id);
                $updateOrder->order_status_id = $request->status;
                $updateOrder->save();
            }

            return [
                'table' => view('admin.partials.table.order_table', compact('orders', 'orderStatus'))->render()
            ];
        }

        return view('admin.orders', compact('orders', 'orderStatus', 'savedStatus', 'savedFromDate', 'savedToDate', 'savedSearch'));
    }

    public function getSavedOrderList()
    {
        $this->getSearchCookie();
        $this->filterOrder = Order::where('id', '<>', 0);
        isset($this->orderStatus) ? $this->filterByStatus() : '';
        isset($this->orderFromDate) ? $this->filterByFromDate() : '';
        isset($this->orderToDate) ? $this->filterByToDate() : '';
        isset($this->orderSearch) ? $this->filterBySearch() : '';
        return $this->filterOrder->orderBy('created_at', 'DESC')->paginate(100);
    }

    public function showOrder($id)
    {
        $order = Order::findOrFail($id);
        // $orderStatus = OrderStatus::where('id', '>', 1)->orderBy('id')->get();
        $orderStatus = OrderStatus::orderBy('id')->get();
        // $orderInfo = Storage::get($order->klarna_reference . '.txt');
        // $orderInfo = json_decode($orderInfo, true);
        // dd( $orderInfo );

        return view('admin/order_details', compact('order', 'orderStatus'));
    }

    public function printOrder($id)
    {
        // $pdf = PDF::loadView('admin.order_details', compact('order'));
        // return $pdf->download('invoice.pdf');
        $order = Order::findOrFail($id);
        $carData = "";
        if (isset($order->carData)) {
            if (!empty($order->carData->reg_number))
                $carData .= "<li><b>Reg. nr: </b> {$order->carData->reg_number}</li>";


            if (!empty($order->carData->car_model))
                $carData .= "<li><b>Bil: </b> {$order->carData->car_model}</li>";


            if (!empty($order->carData->front_tire))
                $carData .= "<li><b>Däck: </b> {$order->carData->front_tire}</li>";


            if (!empty($order->carData->pcd))
                $carData .= "<li><b>PCD: </b> {$order->carData->pcd}</li>";


            if (!empty($order->carData->offset))
                $carData .= "<li><b>Offset: </b> {$order->carData->offset}</li>";


            if (!empty($order->carData->nav))
                $carData .= "<li><b>Nav: </b> { $order->carData->nav} { $order->carData->oe_type }</li>";
        }

        $products = "";

        foreach ($order->orderDetails as $item) {
            $products = "<tr class='CartProduct'>
                <td>
                    <div class='CartDescription'>
                        <h4>{$item->product->product_name} </a></h4>
                        <div class='price'><span>{$item->unit_price} kr</span></div>
                    </div>
                </td>
                <td> x{$item->quantity}</td>
                <td>0</td>
                <td><span class='price'>{$item->total_price_including_tax}kr</span> <br> varav moms: {$item->total_tax_amount}kr</td>
            </tr>";
        }

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('admin/invoice', compact('order', 'carData', 'products'));
        return $pdf->stream();
    }

    public function updateQuantity(Request $request)
    {
        $quantity = Product::find($request->product_id)->quantity;

        // Validation on max quantity
        $validator = Validator::make($request->all(), [
            'quantity' => "required|numeric|min:0|max:{$quantity}"
        ]);

        if ($validator->fails()) {
            $message = $validator->errors();
            session()->flash('error_message', $message->first('quantity'));
            return response()->json(['success' => false]);
        }

        $orderDetail = OrderDetail::find($request->order_details_id);
        $orderDetail->quantity = $request->quantity;
        $orderDetail->total_price_excluding_tax = $request->quantity * $orderDetail->unit_price;
        $orderDetail->total_price_including_tax = $orderDetail->total_price_excluding_tax * 1.25;
        $orderDetail->total_tax_amount = $orderDetail->total_price_including_tax * 0.2;
        $orderDetail->save();

        $order = Order::find($orderDetail->order_id);
        $order->total_price_excluding_tax = 0;
        $order->total_price_including_tax = 0;
        $order->total_tax_amount = 0;
        foreach ($order->orderDetails as $item) {
            $order->total_price_excluding_tax += $item->total_price_excluding_tax;
            $order->total_price_including_tax += $item->total_price_including_tax;
            $order->total_tax_amount += $item->total_tax_amount;
        }
        $order->save();

        return;
    }


    public function updateStatus(Request $request)
    {
        $order = Order::find($request->id);
        // $orderStatus = OrderStatus::where('id', '>', 1)->orderBy('id')->get();
        $orderStatus = OrderStatus::orderBy('id')->get();

        if (($order->order_status_id == 4 || $order->order_status_id == 3) && !empty($order->svea_order_id)) {
            $orders = Order::orderBy('created_at', 'DESC')->paginate(100);

            return [
                'table' => view('admin.partials.table.order_table', compact('orders', 'orderStatus'))->render(),
            ];
        }

        $mySveaOrderId = $order->svea_order_id;
        $paymentType = $order->payment_type;
        $countryCode = $order->billing_country;

        if ($request->status == 4) {
            // $myOrder = \WebPay::deliverOrder( $this->myConfig );
            // $myOrder->setCountryCode($countryCode);                         
            // $myOrder->setOrderId( $mySveaOrderId );

            // if($paymentType == "Svea faktura") {
            //     $myOrder->setInvoiceDistributionType(\DistributionType::POST);
            //     $myDeliverOrderRequest = $myOrder->deliverInvoiceOrder();
            //     $myResponse = $myDeliverOrderRequest->doRequest();
            // }

            // if($paymentType == "Svea delbetalning" ) {
            //     $myDeliverOrderRequest = $myOrder->deliverPaymentPlanOrder();
            //     $myResponse = $myDeliverOrderRequest->doRequest();
            // }

            // if($paymentType == "Kort") {
            //     $myDeliverOrderRequest = $myOrder->deliverCardOrder();
            //     $myResponse = $myDeliverOrderRequest->doRequest();
            // }

            Mail::to($order->user->email)->send(new ConfirmOrderSent($order));
        }

        if ($request->status == 3) {
            // $sveaRequest = \WebPayAdmin::cancelOrder($this->myConfig)
            //      ->setOrderId($mySveaOrderId)     // required, use SveaOrderId recieved with createOrder response
            //      // ->setTransactionId()   // optional, card or direct bank only, alias for setOrderId
            //      ->setCountryCode($countryCode); // required, use same country code as in createOrder request

            // if($paymentType == "Svea faktura" )
            //     $response = $sveaRequest->cancelInvoiceOrder()->doRequest();        // returns CloseOrderResponse

            // if($paymentType == "Svea delbetalning" )
            //     $response = $sveaRequest->cancelPaymentPlanOrder()->doRequest();    // returns CloseOrderResponse

            // if($paymentType == "Kort" )
            //     $response = $sveaRequest->cancelCardOrder()->doRequest();


            // foreach ($order->orderDetails as $orderDetail) {
            //     $orderDetail->product->quantity += $orderDetail->quantity;
            //     $orderDetail->product->save();
            // }
            // Mail::to($order->user->email)->send(new ConfirmOrderDenied($order));
        }

        if ($request->status == 2) {
            // Mail::to($order->user->email)->send(new ConfirmOrderTreated($order));
        }


        $order->order_status_id = $request->status;
        $order->save();

        $orders = Order::orderBy('created_at', 'DESC')->paginate(100);
        // $orderStatus = OrderStatus::where('id', '>', 1)->orderBy('id')->get();
        $orderStatus = OrderStatus::orderBy('id')->get();

        return [
            'table' => view('admin.partials.table.order_table', compact('orders', 'orderStatus'))->render(),
        ];
    }

    //Klarna däckline
    // public function updateStatus(Request $request)
    // {
    //     $order = Order::find($request->id);
    //     // $orderStatus = OrderStatus::where('id', '>', 1)->orderBy('id')->get();
    //     $orderStatus = OrderStatus::orderBy('id')->get();

    //     if($request->status == 4) {
    //         Mail::to($order->user->email)->send(new ConfirmOrderSent($order));
    //     }
    //     if($request->status == 3) {
    //         foreach ($order->orderDetails as $orderDetail) {
    //             $orderDetail->product->quantity += $orderDetail->quantity;
    //             $orderDetail->product->save();
    //         }
    //     }
    //     if($request->status == 2) {
    //         Mail::to($order->user->email)->send(new ConfirmOrderTreated($order));
    //     }

    //     if($order->order_status_id == 3) {
    //         foreach ($order->orderDetails as $orderDetail) {
    //             $orderDetail->product->quantity -= $orderDetail->quantity;
    //             $orderDetail->product->save();
    //         }
    //     }

    //     $order->order_status_id = $request->status;
    //     $order->save();

    //     $orders = Order::orderBy('created_at', 'DESC')->paginate(100);
    //     // $orderStatus = OrderStatus::where('id', '>', 1)->orderBy('id')->get();
    //     $orderStatus = OrderStatus::orderBy('id')->get();

    //     return [
    //         'table' => view('admin.partials.table.order_table', compact('orders', 'orderStatus'))->render(),
    //     ];
    // }

    //for klarna
    // public function updateStatus(Request $request)
    // {
    // 	if($request->status) {
    //         $order = Order::find($request->id);
    //         $orderStatus = OrderStatus::where('id', '>', 1)->orderBy('id')->get();
    //         $klarnaOrderID = $order->klarna_reference;

    //         $this->order = new \Klarna_Checkout_Order($this->connector, $klarnaOrderID);

    //         try {
    //             $this->order->fetch();
    //         } catch (\Klarna_Checkout_ApiErrorException $e) {
    //             var_dump($e->getMessage());
    //             var_dump($e->getPayload());
    //         }

    //         $order->order_status_id = $request->status;
    //         $order->save();

    //         if($request->status == 4) {
    //             $update['status'] = 'created';
    //             // $update['merchant_reference'] = array(
    //             //     "orderid1" => "someUniqueId..."
    //             // );
    //             $this->order->update($update);
    //         }
    //     }


    //     $orders = Order::orderBy('created_at', 'DESC')->paginate(100);

    // 	return [
    //         'table' => view('admin.partials.table.order_table', compact('orders', 'orderStatus'))->render(),
    //     ];
    // }
    // 

    private function initKlarnaOrderManagement()
    {
        $k = new \Klarna();

        if (\App::environment('production')) {
            $k->config(
                $this->eid,              // Merchant ID
                $this->sharedSecret, // Shared secret
                \KlarnaCountry::SE,    // Purchase country
                \KlarnaLanguage::SV,   // Purchase language
                \KlarnaCurrency::SEK,  // Purchase currency
                \Klarna::LIVE   // Server
            );
        } else {
            $k->config(
                $this->eid,              // Merchant ID
                $this->sharedSecret, // Shared secret
                \KlarnaCountry::SE,    // Purchase country
                \KlarnaLanguage::SV,   // Purchase language
                \KlarnaCurrency::SEK,  // Purchase currency
                \Klarna::BETA   // Server
            );
        }



        return $k;
    }

    // for klarna
    public function activateKlarna(Request $request)
    {
        $k = $this->initKlarnaOrderManagement();

        // $orderStatus = OrderStatus::where('id', '>', 1)->orderBy('id')->get();

        try {
            $order = Order::find($request->id);

            $result = $k->activate(
                $order->klarna_reservation,
                null,    // OCR Number
                \KlarnaFlags::RSRV_SEND_BY_EMAIL
            );

            $order->klarna_status = "Aktiverad";
            $order->klarna_risk = $result[0];
            $order->klarna_invoice_no = $result[1];
            $order->save();

            // echo "OK: invoice number {$invNo} - risk status {$risk}\n";
        } catch (Exception $e) {
            echo "{$e->getMessage()} (#{$e->getCode()})\n";
        }

        $orderStatus = OrderStatus::all();
        $orders = Order::orderBy('created_at', 'DESC')->paginate(100);

        return [
            'table' => view('admin.partials.table.order_table', compact('orders', 'orderStatus'))->render(),
        ];
    }

    // for klarna
    public function cancelKlarna(Request $request)
    {
        $k = $this->initKlarnaOrderManagement();

        try {
            $order = Order::find($request->id);

            $k->cancelReservation($order->klarna_reservation);

            $order->klarna_status = "Annullerad";
            $order->save();

            // echo "OK: invoice number {$invNo} - risk status {$risk}\n";
        } catch (Exception $e) {
            echo "{$e->getMessage()} (#{$e->getCode()})\n";
        }

        $orderStatus = OrderStatus::all();
        $orders = Order::orderBy('created_at', 'DESC')->paginate(100);

        return [
            'table' => view('admin.partials.table.order_table', compact('orders', 'orderStatus'))->render(),
        ];
    }

    public function getSearchCookie()
    {
        $orderFilter = json_decode(Cookie::get('orderFilter'), true);
        // dd($orderFilter);
        $this->orderStatus = $orderFilter['orderStatus'] ? $orderFilter['orderStatus'] : null;
        $this->orderFromDate = isset($orderFilter['orderFromDate']) ? $orderFilter['orderFromDate'] : null;
        $this->orderToDate = isset($orderFilter['orderToDate']) ? $orderFilter['orderToDate'] : null;
        $this->orderSearch = isset($orderFilter['orderSearch']) ? $orderFilter['orderSearch'] : null;
    }

    public function storeSearchInCookie()
    {
        $orderFilter['orderStatus'] = $this->orderStatus;
        $orderFilter['orderFromDate'] = $this->orderFromDate;
        $orderFilter['orderToDate'] = $this->orderToDate;
        $orderFilter['orderSearch'] = $this->orderSearch;
        $orderFilter = json_encode($orderFilter);
        Cookie::queue('orderFilter', $orderFilter, 60 * 24);
    }

    public function filterOrders(Request $request)
    {
        // dd($request->all());
        // $orderStatus = OrderStatus::where('id', '>', 1)->orderBy('id')->get();

        $this->filterOrder = Order::where('id', '<>', 0);

        if (!empty($request->status)) {
            $this->orderStatus = $request->status;
            $this->filterByStatus();
        }

        if (!empty($request->fromDate)) {
            $this->orderFromDate = $request->fromDate;
            $this->filterByFromDate();
        }

        if (!empty($request->toDate)) {
            $this->orderToDate = $request->toDate;
            $this->filterByToDate();
        }

        if (!empty($request->search)) {
            $this->orderSearch = $request->search;
            $this->filterBySearch();
        }

        $orders =  $this->filterOrder->orderBy('created_at', 'DESC')->paginate(100);
        $orderStatus = OrderStatus::orderBy('id')->get();
        $this->storeSearchInCookie();


        return [
            'table' => view('admin.partials.table.order_table', compact('orders', 'orderStatus'))->render()
        ];
    }

    public function filterByStatus()
    {
        if ($this->orderStatus == 'AllaUtanAvslag') {
            $this->filterOrder->where('order_status_id', '<>', 3);
        } else {
            $this->filterOrder->where('order_status_id', $this->orderStatus);
        }
    }

    public function filterByFromDate()
    {
        $this->filterOrder->where('created_at', '>=', $this->orderFromDate);
    }

    public function filterByToDate()
    {
        $this->filterOrder->where('created_at', '<=', $this->orderToDate);
    }

    public function filterBySearch()
    {
        $this->filterOrder->where(function ($query) {
            return $query
                ->where('total_price_including_tax', 'like', "%{$this->orderSearch}%")
                ->orWhere('id', 'like', "%{$this->orderSearch}%")
                ->orWhere('reference', 'like', "%{$this->orderSearch}%")
                ->orWhereHas('user', function ($query) {
                    return $query
                        ->where('first_name', 'like', "%{$this->orderSearch}%")
                        ->orWhere('last_name', 'like', "%{$this->orderSearch}%")
                        ->orWhere('email', 'like', "%{$this->orderSearch}%")
                        ->orWhere('date_of_birth', 'like', "%{$this->orderSearch}%")
                        ->orWhere('billing_street_address', 'like', "%{$this->orderSearch}%")
                        ->orWhere('billing_postal_code', 'like', "%{$this->orderSearch}%")
                        ->orWhere('billing_city', 'like', "%{$this->orderSearch}%")
                        ->orWhere('billing_country', 'like', "%{$this->orderSearch}%")
                        ->orWhere('billing_phone', 'like', "%{$this->orderSearch}%");
                })
                ->orWhereHas('carData', function ($query) {
                    return $query
                        ->where('reg_number', 'like', "%{$this->orderSearch}%")
                        ->orWhere('car_model', 'like', "%{$this->orderSearch}%")
                        ->orWhere('front_tire', 'like', "%{$this->orderSearch}%")
                        ->orWhere('pcd', 'like', "%{$this->orderSearch}%")
                        ->orWhere('offset', 'like', "%{$this->orderSearch}%");
                });
        });

        // \Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
        //     var_dump($query->sql);
        //     var_dump($query->bindings);
        //     var_dump($query->time);
        // });
    }

    public function showOrderCommentModal(Request $request)
    {
        // dd($request->orderId);
        $order = Order::find($request->orderId);

        return [
            // 'commentOrderModal' => view('admin/partials/form/comment_order_modal')->render(),
            'order' => $order
        ];
    }

    public function commentOrder(Request $request)
    {
        // dd($request->all());
        // $orderStatus = OrderStatus::where('id', '>', 1)->orderBy('id')->get();
        $orderStatus = OrderStatus::orderBy('id')->get();
        $updateOrder = Order::find($request->orderId);
        $updateOrder->comment = $request->comment;
        $updateOrder->save();

        $orders = Order::orderBy('created_at', 'DESC')->paginate(100);
        return [
            'table' => view('admin.partials.table.order_table', compact('orders', 'orderStatus'))->render(),
        ];
    }

    public function updateCarData($id)
    {
        $updateCarData = CarData::where('order_id', $id)->first();

        // if (\App::environment('production')) {
        //     $username = 'wheelzone';
        //     $password = 'wheel@zone123';
        // } else {
        //     $username = 'ptest';
        //     $password = 'ptest';
        // }

        $username = env('API_SEARCH_USER');
        $password = env('API_SEARCH_PASS');

        $headers[] = 'Authorization: Basic ' .
            base64_encode($username . ':' . $password);
        $headers[] = 'Content-Type: application/json';

        $host = "https://slimapi.abswheels.se/regNoSearch/$updateCarData->reg_number/";
        $apiResponse = $this->CallAPIHeader("GET", $headers, $host);
        $search = json_decode($apiResponse, true);

        // dd($search, $updateCarData->reg_number);


        if ($search['status'] == "NotOk") {
            return back();
        }

        $updateCarData->car_model = $search['data']['Manufacturer'] . " " . $search['data']['ModelName'] . " " . $search['data']['FoundYear'];
        $updateCarData->front_tire = $search['data']['FoundDackFront'];
        $updateCarData->pcd = $search['data']['PCD'];
        $updateCarData->offset = $search['data']['Offset'];
        $updateCarData->nav = $search['data']['ShowCenterBore'];
        $updateCarData->oe_type = $search['data']['OE_Type'];
        $updateCarData->save();

        return back();
    }

    public function sendItemNotification(Request $request)
    {
        // dd($request->all());
        // if (\App::environment('production')) {
        //     Mail::to('order@wheelzone.se')->send(new ItemNotification($request->all()));
        // } else {
        //     Mail::to('sibar@abswheels.se')->send(new ItemNotification($request->all()));
        // }
        // Mail::to(env('MAIL_FROM_ADDRESS'))->send(new ItemNotification($request->all()));

        return redirect()->back()->with('message', 'Skickat.');

        return [
            'message' => 'Skickat.'
        ];
    }

    public function CallAPIHeader($method, $headers, $url, $data = false)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        switch ($method) {
            case "POST":
                if ($data) {
                    curl_setopt($curl, CURLOPT_POST, 1);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;

            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;

            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }
}
