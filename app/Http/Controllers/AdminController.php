<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Order;
use App\OrderDetail;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{

    private $connector;
    private $order = null;
    private $eid = '7551';
    private $sharedSecret = 'hFH4ToOoftLKno9';
    protected $userID;
    protected $orderID;

    public function __construct()
    {
        $this->connector = \Klarna_Checkout_Connector::create(
            $this->sharedSecret,
            \Klarna_Checkout_Connector::BASE_TEST_URL
        );
    }


    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function orders()
    {
        $orders = Order::all();
        return view('admin.orders', compact('orders'));
    }


    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function showOrder($id)
    {
        $order = Order::findOrFail($id);
        // $orderInfo = Storage::get($order->klarna_reference . '.txt');
        // $orderInfo = json_decode($orderInfo, true);
        // dd( $orderInfo );

        return view('admin/order_details', compact('order'));
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
        $klarnaOrderID = $order->klarna_reference;

        $this->order = new \Klarna_Checkout_Order($this->connector, $klarnaOrderID);

        try {
            $this->order->fetch();
        } catch (\Klarna_Checkout_ApiErrorException $e) {
            var_dump($e->getMessage());
            var_dump($e->getPayload());
        }

        $order->status = $request->status;
        $order->save();

        if ($request->status == 'Levererad') {
            $update['status'] = 'created';
            // $update['merchant_reference'] = array(
            //     "orderid1" => "someUniqueId..."
            // );
            $this->order->update($update);
        }

        return;
    }
}
