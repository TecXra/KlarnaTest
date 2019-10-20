<?php

namespace App\Http\Controllers;

use App\AdTracking;
use App\CarData;
use App\CartCalculator;
use App\Http\Controllers\callingSale;
use App\Http\Requests;
use App\Http\Utilities\Country;
use App\Mail\OrderConfirmation;
use App\Mail\OrderConfirmationCC;
use App\Mail\PaymentRejected;
use App\Mail\Recite;
use App\Order;
use App\OrderDetail;
use App\PaymentMethod;
use App\Product;
use App\User;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use SoapClient;

class CheckoutController extends Controller
{
    protected $myConfig;
    protected $myOrder;
    protected $order = null;
    protected $createdOrder;
    private $password;
    protected $userID;
    protected $orderID;
    protected $sveaOrderId;
    protected $currencyMultiplier;
    protected $currencyNotation;
    protected $paymentType;
    protected $campaignCode;
    protected $shippingFee;
    protected $cardType;
    protected $maskedCardNumber;
    protected $message;
    protected $is_company;
    protected $orderStatusId;
    protected $ajust;
    protected $isPaymentRejected = 0;
    private $connector;

    private $merchantId;
    private $token;
    private $wsdl;
    private $path_parts;
    private $redirect_url;
    private $terminal;

    public function __construct()
    {
        if (\App::environment('production')) {
            $this->merchantId = "495897";
            $this->token = "n+3F*Ec4";
            $this->wsdl = "https://epayment.nets.eu/netaxept.svc?wsdl";
            $this->terminal = "https://epayment.nets.eu/terminal/default.aspx";
        } else {
            $this->merchantId = "12001876";
            $this->token = "W(t3~5G";
            $this->wsdl = "https://test.epayment.nets.eu/Netaxept.svc?wsdl";
            $this->terminal = "https://test.epayment.nets.eu/terminal/default.aspx";
        }

        $this->path_parts = pathinfo($_SERVER["PHP_SELF"]);
        $this->redirect_url = url('processNetsPayement');
    }

    public function saveOrderInfo(Request $request)
    {

        $request->ssn = str_replace([' ', '-'], ['', ''], $request->ssn);
        if (strlen($request->ssn) == 12) {
            $request->ssn = substr($request->ssn, 2);
        }

        $request->InputBillingPhoneNumber = str_replace([' ', '-'], ['', ''], $request->InputBillingPhoneNumber);
        $request->InputBillingPostalCode = str_replace([' ', '-'], ['', ''], $request->InputBillingPostalCode);
        $request->InputShippingPhoneNumber = str_replace([' ', '-'], ['', ''], $request->InputShippingPhoneNumber);
        $request->InputShippingPostalCode = str_replace([' ', '-'], ['', ''], $request->InputShippingPostalCode);

        $textEmail = $request->InputEmail;

        $request->session()->put('orderInfo.ssn', $request->ssn);
        $request->session()->put('orderInfo.firstName', $request->InputFirstName);
        $request->session()->put('orderInfo.lastName', $request->InputLastName);
        $request->session()->put('orderInfo.email', $request->InputEmail);
        $request->session()->put('orderInfo.companyName', $request->InputCompanyName);
        $isCompany = isset($request->isCompanyCheckout) ? $request->isCompanyCheckout : 0;
        $request->session()->put('orderInfo.isCompany', $isCompany);
        $request->session()->put('orderInfo.billingFullName', $request->InputFirstName . " " . $request->InputLastName);
        $request->session()->put('orderInfo.billingPhoneNumber', $request->InputBillingPhoneNumber);
        $request->session()->put('orderInfo.billingAddress', $request->InputBillingAddress);
        $request->session()->put('orderInfo.billingPostalCode', $request->InputBillingPostalCode);
        $request->session()->put('orderInfo.billingCity', $request->InputBillingCity);
        $request->session()->put('orderInfo.billingCountry', 'SE');

        if (isset($request->deliveryAdress)) {
            $request->session()->put('orderInfo.shippingFullName', $request->InputShippingFullName);
            $request->session()->put('orderInfo.shippingPhoneNumber', $request->InputShippingPhoneNumber);
            $request->session()->put('orderInfo.shippingAddress', $request->InputShippingAddress);
            $request->session()->put('orderInfo.shippingPostalCode', $request->InputShippingPostalCode);
            $request->session()->put('orderInfo.shippingCity', $request->InputShippingCity);
            // $request->session()->put('orderInfo.shippingCountry', $request->InputShippingCountry);
            $request->session()->put('orderInfo.shippingCountry', 'SE');
        } else {
            $request->session()->put('orderInfo.shippingFullName', $request->InputFirstName . " " . $request->InputLastName);
            $request->session()->put('orderInfo.shippingPhoneNumber', $request->InputBillingPhoneNumber);
            $request->session()->put('orderInfo.shippingAddress', $request->InputBillingAddress);
            $request->session()->put('orderInfo.shippingPostalCode', $request->InputBillingPostalCode);
            $request->session()->put('orderInfo.shippingCity', $request->InputBillingCity);
            // $request->session()->put('orderInfo.shippingCountry', $request->InputBillingCountry);
            $request->session()->put('orderInfo.shippingCountry', 'SE');
        }

        $request->session()->put('orderInfo.regNumber', $request->InputRegNumber);
        $request->session()->put('orderInfo.reference', $request->InputReference);
        $request->session()->put('orderInfo.message', $request->message);
        $request->session()->put('orderInfo.token', bin2hex(openssl_random_pseudo_bytes(16)));
        $request->session()->put('orderInfo.ipNumber', $request->ip());
        $tireQty = $rimsQty = 0;
        $cantOrder = false;
        if (sizeof(Cart::content()) > 0) {
            foreach (Cart::content() as $item) {
                if ($item->options->product_category_id == 1)
                    $tireQty += $item->qty;
                if ($item->options->product_category_id == 2)
                    $rimsQty += $item->qty;
            }
        }
        $cantOrder = ($tireQty > 0 and $tireQty < 4) ? true : $cantOrder;
        $cantOrder = ($rimsQty > 0 and $rimsQty < 4) ? true : $cantOrder;

        if ($cantOrder) {
            session()->flash('error_message', 'Obs! vid beställning av däck/fälg, är minsta tillåtna antal 4 st däck.');
            return [
                'isValidOrder' => false
            ];
        }

        if (Auth::check()) {

            if (Auth::user()->user_type_id == 5) {
                $this->paymentType = "ABSWheels Faktura";
                $this->isPaymentRejected = '';
                $this->cardType = '';
                $this->maskedCardNumber = '';
                $this->orderStatusId = 1;
                $this->saveUser();
                $this->saveOrder();
                $this->saveOrderDetails();
                $this->saveCarData();

                Mail::to(Session::get('orderInfo.email'))->send(new OrderConfirmation($this->order, $this->password));
                Mail::to(env('MAIL_FROM_ADDRESS'))->send(new OrderConfirmationCC($this->order, $this->password));
                Mail::to(Session::get('orderInfo.email'))->send(new Recite($this->order));


                return [
                    'token' => $this->order->token,
                    'isAdmin' => true,
                    'isValidOrder' => true
                ];
            }
        }
        $request->session()->put('orderInfo.deliveryId', 1);
        $cartCalculator = new CartCalculator;

        //$netsPaymentForm = $this->createNetsForm();
        return [
            "paymentMethodOptions" => view('checkout.partials.payment.payment_methods', compact('isCompany', 'textEmail'))->render(),
            // "paymentMethodOptions" => view('checkout.partials.payment.payment_methods', compact('cartCalculator', 'paymentOptions', 'currencyMultiplier', 'currency'))->render(),
            // "paymentMethodOptions" => view('checkout.partials.payment.payment_methods', compact('snippet'))->render(),
            //'netsPaymentForm' => $netsPaymentForm,
            'isAdmin' => false,
            'isValidOrder' => true
        ];
    }
    private function avardaRequest($requestMethod, $data)
    {
        if (\App::environment('production')) {
            $username = 'hjulonline';
            $password = 'Q66h64_746n48=d';
            $host = "https://online.avarda.org/CheckOut2/CheckOut2Api/" . $requestMethod;
        } else {
            $username = 'Hjulonline_TEST';
            $password = 'hjulonline_test';
            $host = "https://stage.avarda.org/CheckOut2/CheckOut2Api/" . $requestMethod;
        }

        $headers[] = 'Authorization: Basic ' . base64_encode($username . ':' . $password);
        $headers[] = 'Content-Type: application/json';


        $apiResponse = $this->CallAPIHeader("POST", $headers, $host, $data);

        return $apiResponse;
    }
    public function avardaPaymentInitialize(Request $request)
    {
        $tempName = explode(" ", $request->session()->get('orderInfo.shippingFullName'));
        $shippingLastName = end($tempName);
        array_splice($tempName, -1);
        $shippingFirstName = implode(" ", $tempName);

        $data = array(
            "Description" => "Hjulonline PaymentInitialize",
            "Phone" => $request->session()->get('orderInfo.billingPhoneNumber'),
            "Mail" => $request->session()->get('orderInfo.email'),
            "IsPhoneAndMailEditable" => false,
            "InvoicingFirstName" => $request->session()->get('orderInfo.firstName'),
            "InvoicingLastName" => $request->session()->get('orderInfo.lastName'),
            "InvoicingAddressLine1" => $request->session()->get('orderInfo.billingAddress'),
            "InvoicingAddressLine2" => '',
            "InvoicingZip" => $request->session()->get('orderInfo.billingPostalCode'),
            "InvoicingCity" => $request->session()->get('orderInfo.billingCity'),
            "IsInvoicingEditable" => false,
            "DeliveryFirstName" => $shippingFirstName,
            "DeliveryLastName" => $shippingLastName,
            "DeliveryAddressLine1" => $request->session()->get('orderInfo.shippingAddress'),
            "DeliveryZip" => $request->session()->get('orderInfo.shippingPostalCode'),
            "DeliveryCity" => $request->session()->get('orderInfo.shippingCity'),
            "IsDeliveryEditable" => true,
            "UseDifferentDeliveryAddress" => false,
            "UserLanguageCode" => "sv-SE",
            "Price" => 0,
            "Items" => array()
        );

        foreach (Cart::content() as $item) {
            $itemPrice = (float) ($item->total);
            $data["Price"] += $itemPrice;
            $itemTaxAmount = $itemPrice - (float) ($item->subtotal);

            $data["Items"][] = array("Amount" => $itemPrice, "TaxCode" => 25, "TaxAmount" => $itemTaxAmount, "Description" => substr($item->name, 0, 35));
        }
        $cartCalculator = new CartCalculator;
        $shippintCost = $cartCalculator->totalPriceShipping();
        $shippintCostTax = $cartCalculator->totalPriceShipping() * 0.2;
        $data["Items"][] = array("Amount" => $shippintCost, "TaxCode" => 25, "TaxAmount" => $shippintCostTax, "Description" => "Fraktkostnad");
        $data["Price"] += $shippintCost;
        //var_dump($data);
        //exit;
        $data = json_encode($data);
        $apiResponse = $this->avardaRequest("InitializePayment", $data);
        $response = json_decode($apiResponse);
        //var_dump($response);
        //exit;
        $avardaHTML = $this->createAvardaHTML($response);

        return [
            'netsPaymentForm' => $avardaHTML,
            'isAdmin' => false,
            'isValidOrder' => true
        ];
    }
    public function createAvardaHTML($response)
    {
        $avardaHTML = '';
        //$callBackUrl =  URL::to("/avarda_callback");
        if (isset($response->CheckOutErrorCode)) {
            foreach ($response->Errors as $errorText)
                $avardaHTML .= '<div class="col-sm-12 text-center text-danger" style="font-size:18px; margin-bottom: 15px;">' . $errorText . '</div>';
        } else {
            $avardaHTML = '<div class="col-sm-12 text-center" style="font-size:18px; margin-bottom: 15px;">
                <div id="checkOutDiv"></div>
                </div><script>var options={divId:"checkOutDiv",purchaseId:"' . $response . '",callbackUrl:"' . URL::to("/avarda_callback") . '",done:function(e){window.location.href="/avarda_success?purchaseId="+e}};AvardaCheckOutClient.init(options);</script>';
        }

        return $avardaHTML;
    }
    public function avardaCallback(Request $request)
    {
        if (sizeof(Cart::content()) > 0 and $request->callback == '2') {
            if (\App::environment('production'))
                echo '<script src="https://online.avarda.org/CheckOut2/Scripts/CheckOutClient.js"></script>';
            else
                echo '<script src="https://stage.avarda.org/CheckOut2/Scripts/CheckOutClient.js"></script>';


            echo $avardaHTML = '<div class="col-sm-12 text-center" style="font-size:18px; margin-bottom: 15px;">
                <div id="checkOutDiv"></div>
                </div><script>var options={divId:"checkOutDiv",purchaseId:"' . $request->purchaseId . '",callbackUrl:"' . URL::to("/avarda_callback") . '",done:function(e){window.location.href="/avarda_success?purchaseId="+e}};AvardaCheckOutClient.init(options);</script>';
        } else {
            return abort(404);
        }
    }
    public function getAvardaPaymentStatus(Request $request)
    {
        $data = array("PurchaseId" => $request->purchaseId);
        $data = json_encode($data);
        $apiResponse = $this->avardaRequest("GetPaymentStatus", $data);
        $response = json_decode($apiResponse);

        $orderExist = Order::where('transaction_id', $request->purchaseId)->get(['id'])->first();
        if (empty($orderExist->id)) {
            $tempAddress = $response->InvoicingAddressLine1 . ($response->InvoicingAddressLine2 != '' ? ' ' . $response->InvoicingAddressLine2 : '');

            $request->session()->put('orderInfo.firstName', $response->InvoicingFirstName);
            $request->session()->put('orderInfo.lastName', $response->InvoicingLastName);
            $request->session()->put('orderInfo.email', $response->Mail);
            $request->session()->put('orderInfo.billingFullName', $response->InvoicingFirstName . " " . $response->InvoicingLastName);
            $request->session()->put('orderInfo.billingPhoneNumber', $response->Phone);
            $request->session()->put('orderInfo.billingAddress', $tempAddress);
            $request->session()->put('orderInfo.billingPostalCode', $response->InvoicingZip);
            $request->session()->put('orderInfo.billingCity', $response->InvoicingCity);
            $request->session()->put('orderInfo.billingCountry', 'SE');

            if (isset($response->DeliveryAddressLine1) and $response->DeliveryAddressLine1 != '') {
                $tempAddress2 = $response->DeliveryAddressLine1 . ($response->DeliveryAddressLine2 != '' ? ' ' . $response->DeliveryAddressLine2 : '');

                $request->session()->put('orderInfo.shippingFullName', $response->DeliveryFirstName . " " . $response->DeliveryLastName);
                $request->session()->put('orderInfo.shippingPhoneNumber', $response->Phone);
                $request->session()->put('orderInfo.shippingAddress', $tempAddress2);
                $request->session()->put('orderInfo.shippingPostalCode', $response->DeliveryZip);
                $request->session()->put('orderInfo.shippingCity', $response->DeliveryCity);
                // $request->session()->put('orderInfo.shippingCountry', $request->InputShippingCountry);
                $request->session()->put('orderInfo.shippingCountry', 'SE');
            } else {
                $request->session()->put('orderInfo.shippingFullName', $response->InvoicingFirstName . " " . $response->InvoicingLastName);
                $request->session()->put('orderInfo.shippingPhoneNumber', $response->Phone);
                $request->session()->put('orderInfo.shippingAddress', $tempAddress);
                $request->session()->put('orderInfo.shippingPostalCode', $response->InvoicingZip);
                $request->session()->put('orderInfo.shippingCity', $response->InvoicingCity);
                // $request->session()->put('orderInfo.shippingCountry', $request->InputBillingCountry);
                $request->session()->put('orderInfo.shippingCountry', 'SE');
            }

            Session::put('orderInfo.deliveryMaxTime', 0);
            foreach (Cart::content() as $item) {

                $this->ajust[$item->id] = "";
                if ($item->options->pcd != null) {
                    $this->ajust[$item->id] = $item->options->pcd;
                }

                $maxNumber = 0;
                if ($item->model->delivery_time) {
                    preg_match_all('!\d+!', $item->model->delivery_time, $number);
                    $maxNumber = max(max($number));
                }

                if (Session::get('orderInfo.deliveryMaxTime') < $maxNumber) {
                    Session::put('orderInfo.deliveryMaxTime', $maxNumber);
                    Session::put('orderInfo.deliveryTime', $item->model->delivery_time);
                }
            }
            Session::put('orderInfo.ajust',  $this->ajust);

            $this->paymentType = "Avarda";
            $this->isPaymentRejected = $response->State != 2 ? 1 : 0;
            $this->cardType = '';
            $this->maskedCardNumber = '';
            $this->orderStatusId = $response->State == 2 ? 1 : 0;
            $this->saveUser();
            $this->saveOrder();

            $order = Order::find(Session::get('orderId'));
            $order->order_status_id = 1;
            $order->authorization_id = '';
            $order->batch_number = '';
            $order->transaction_date = date("Y-m-d H:i:s", strtotime($response->Completed));
            $order->transaction_id = $response->PurchaseId;
            $order->save();
            $this->saveOrderDetails();
            $this->saveCarData();

            $data = array("PurchaseId" => $response->PurchaseId, "OrderNumber" => $order->id);
            $data = json_encode($data);
            $this->avardaRequest("UpdateOrderNumber", $data);

            //Mail::to(Session::get('orderInfo.email'))->send(new OrderConfirmation($this->order, $this->password));
            //Mail::to(env('MAIL_FROM_ADDRESS'))->send(new OrderConfirmationCC($this->order, $this->password));
            //Mail::to(Session::get('orderInfo.email'))->send(new Recite($order));
            Session::forget('password');
            return redirect("orderbekraftelse/" . Session::get('orderInfo.token'));
        } else {
            $data = array("PurchaseId" => $request->purchaseId, "OrderNumber" => $orderExist->id);
            $data = json_encode($data);
            $this->avardaRequest("UpdateOrderNumber", $data);

            return abort(404);
        }
    }
    public function purchaseAvardaPayment(Request $request)
    {
        //echo $request->session()->get('orderInfo.ssn');
        //exit;
        exit;
        $orderID = $request->id;
        $purchaseID = $request->purchaseid;
        $data = array(
            "ExternalId" => $purchaseID,
            "OrderReference" => $orderID,
            "Items" => array()
        );

        $order = Order::findOrFail($orderID);

        foreach ($order->orderDetails as $item) {
            $itemPrice = (float) ($item->total_price_including_tax);
            $itemTaxAmount = $itemPrice - (float) ($item->total_price_excluding_tax);

            $data["Items"][] = array("Amount" => $itemPrice, "TaxCode" => 25, "TaxAmount" => $itemTaxAmount, "Description" => substr($item->product_name, 0, 35));
        }

        $shippintCost = $order->shipping_fee;
        $shippintCostTax = $order->shipping_fee * 0.2;
        $data["Items"][] = array("Amount" => $shippintCost, "TaxCode" => 25, "TaxAmount" => $shippintCostTax, "Description" => "Fraktkostnad");

        //echo '<pre>';
        //print_r($order->carData);
        //echo '</pre>';

        echo '<pre>';
        print_r($data);
        echo '</pre>';
        exit;
        $data = json_encode($data);
        $apiResponse = $this->avardaRequest("Commerce/PurchaseOrder", $data);
        $response = json_decode($apiResponse);
        echo '<pre>';
        print_r($response);
        echo '</pre>';
        exit;
    }
    public function getNetsHTML(Request $request)
    {
        $netsPaymentForm = $this->createNetsForm();
        return [
            'netsPaymentForm' => $netsPaymentForm,
            'isAdmin' => false,
            'isValidOrder' => true
        ];
    }
    public function createNetsForm()
    {
        Session::put('orderInfo.deliveryMaxTime', 0);


        foreach (Cart::content() as $item) {

            $this->ajust[$item->id] = "";
            if ($item->options->pcd != null) {
                $this->ajust[$item->id] = $item->options->pcd;
            }

            $maxNumber = 0;
            if ($item->model->delivery_time) {
                preg_match_all('!\d+!', $item->model->delivery_time, $number);
                $maxNumber = max(max($number));
            }

            if (Session::get('orderInfo.deliveryMaxTime') < $maxNumber) {
                Session::put('orderInfo.deliveryMaxTime', $maxNumber);
                Session::put('orderInfo.deliveryTime', $item->model->delivery_time);
            }
        }
        Session::put('orderInfo.ajust',  $this->ajust);


        $this->paymentType = "Kort";
        $this->isPaymentRejected = '';
        $this->cardType = '';
        $this->maskedCardNumber = '';
        $this->orderStatusId = 0;
        $this->saveUser();
        $this->saveOrder();

        if ($this->password)
            Session::put('password', $this->password);

        // $cartCalculator = new CartCalculator;

        ####  PARAMETERS IN ORDER  ####
        $amount               = $this->order->total_price_including_tax * 100; //$amount; // The amount described as the lowest monetary unit, example: 100,00 NOK is noted as "10000", 9.99 USD is noted as "999".
        $currencyCode         = 'SEK'; //$Currency;  //The currency code, following ISO 4217. Typical examples include "NOK" and "USD".
        $force3DSecure        = null;   // Optional parameter
        $orderNumber          = $this->order->id; //$tempOrderID;
        $UpdateStoredPaymentInfo = null;


        ####  PARAMETERS IN Environment  ####
        $Language             = 'sv_SE'; // Optional parameter
        $OS                   = null; // Optional parameter
        $WebServicePlatform   = 'PHP5'; // Required (for Web Services)

        ####  PARAMETERS IN TERMINAL  ####
        $autoAuth             = null; // Optional parameter
        $paymentMethodList    = null; // Optional parameter
        $language             = 'sv_SE'; // Optional parameter
        $orderDescription     = null; // Optional parameter
        $redirectOnError      = null; // Optional parameter

        ####  PARAMETERS IN REGISTER REQUEST  ####
        $AvtaleGiro           = null; // Optional parameter
        $CardInfo             = null; // Optional parameter
        $Customer             = null; // Optional parameter
        $description          = null; // Optional parameter
        $DnBNorDirectPayment  = null; // Optional parameter
        $Environment          = null; // Optional parameter
        $MicroPayment         = null; // Optional parameter
        $serviceType          = null; // Optional parameter: null ==> default = "B" <=> BBS HOSTED
        $Recurring            = null; // Optional parameter
        $transactionId        = null; // Optional parameter
        $transactionReconRef  = null; // Optional parameter

        ####  ENVIRONMENT OBJECT  ####
        $Environment = new \Nets\Environment($Language, $OS, $WebServicePlatform);

        ####  TERMINAL OBJECT  ####
        $Terminal = new \Nets\Terminal($autoAuth, $paymentMethodList, $language, $orderDescription, $redirectOnError, $this->redirect_url);

        $ArrayOfItem = null; // no goods for Klana ==> normal transaction
        ####  ORDER OBJECT  ####
        $Order = new \Nets\Order($amount, $currencyCode, $force3DSecure, $ArrayOfItem, $orderNumber, $UpdateStoredPaymentInfo);


        ####  START REGISTER REQUEST  ####
        $RegisterRequest = new \Nets\RegisterRequest($AvtaleGiro, $CardInfo, $Customer, $description, $DnBNorDirectPayment, $Environment, $MicroPayment, $Order, $Recurring, $serviceType, $Terminal, $transactionId, $transactionReconRef);


        ####  ARRAY WITH REGISTER PARAMETERS  ####
        $InputParametersOfRegister = array(
            "token"                 => $this->token,
            "merchantId"            => $this->merchantId,
            "request"               => $RegisterRequest
        );


        try {
            if (strpos($_SERVER["HTTP_HOST"], 'uapp') > 0) {
                // Creating new client having proxy
                $client = new \SoapClient($this->wsdl, array('proxy_host' => "isa4", 'proxy_port' => 8080, 'trace' => true, 'exceptions' => true));
            } else {
                // Creating new client without proxy
                $client = new \SoapClient($this->wsdl, array('trace' => true, 'exceptions' => true));
            }

            $OutputParametersOfRegister = $client->__call('Register', array("parameters" => $InputParametersOfRegister));

            // RegisterResult
            $RegisterResult = $OutputParametersOfRegister->RegisterResult;

            $terminal_parameters = "?merchantId=" . $this->merchantId . "&transactionId=" . $RegisterResult->TransactionId;
            $process_parameters = "?transactionId=" . $RegisterResult->TransactionId;
            // error_log('Kreditkort initialized. Ordernr: '.$tempOrderID);

            $netsPaymentForm = '
                <div class="col-sm-12 text-center" style="font-size:18px; margin-bottom: 15px;"><strong>Vi tillhandahåller säker kortbetalning för att hålla ner administrativa avgifter, vilket i sin tur håller ner priserna på dina däck och fälgar.</strong></div>
                <div class="col-sm-12">
                    <iframe src="' . $this->terminal . $terminal_parameters . '" height="400" width="100%" scrolling="auto"></iframe>
                </div><br>
                <div class="col-sm-12 text-center text-danger" style="font-size:18px"><strong>*Viktigt! Ditt kort ska vara öppet för internetköp.</strong></div>';
        } catch (\SoapFault $fault) {
            $netsPaymentForm = '<div class="row">
              <div class="col-sm-12">
                <div class="alert alert-danger">' . $fault->faultstring . '</div>
              </div>
            </div>';
        }

        // dd($netsPaymentForm);

        return $netsPaymentForm;
    }

    public function processNetsPayement(Request $request)
    {
        // $this->sveaOrderId = $myResponse->response->transactionId;
        // $this->paymentType = "Kort";
        // $this->cardType = $myResponse->response->cardType;
        // $this->maskedCardNumber = $myResponse->response->maskedCardNumber;
        // $this->orderStatusId = 0;
        // $this->saveUser();
        // $this->saveOrder();
        // $this->saveOrderDetails();
        // $this->saveCarData();

        $transactionId = "";
        if (isset($request->transactionId)) {
            $transactionId = $request->transactionId;
        }

        $responseCode = "";
        if (isset($request->responseCode)) {
            $responseCode = $request->responseCode;
        }

        // this is an example is showing how to add one (or several additional parameters on the terminal)
        $webshopParameter = "";
        if (isset($request->webshopParameter)) {
            $webshopParameter = $request->webshopParameter;
        }

        $processResponse = '';
        ####  Display all parameters  ####
        $processResponse = "<h3><font color='gray'>Terminal returned parameters to the webshop:</font></h3>";

        $processResponse .= "transactionId= " . $transactionId . "<br/>";
        $processResponse .= "responseCode= " . $responseCode . "<br/>";

        $processResponse .= "<h3><font color='gray'>Additional parameter defined by the webshop:</font></h3>";

        $processResponse .= "webshopParameter= " . $webshopParameter . "<br/>";

        $process_parameters = "?transactionId=" .  $transactionId;

        if ($responseCode == "OK") {
            $processResponse .= "<br/>ResponseCode is OK, so you can call manually following action:";

            $processResponse .= "<h3><a href='" . url('callingAuth' . $process_parameters) . "'>Calling Auth</a></h3>";
            $processResponse .= "<h3><a href='" . url('callingSale' . $process_parameters) . "'>Calling Sale</a></h3>";
            $processResponse .= "<h3><a href='" . url('callingQuery' . $process_parameters) . "'>Calling Query</a></h3>";
            // in PRODUCTION environment: you will run a query at this stage
            // and read the AuthenticationInformation in order to decide if you will offer your client to call Auth or Sale
            // e.g. $AuthenticationInformation = $OutputParametersOfQuery->QueryResult->AuthenticationInformation; 
            //      $AuthenticationInformation->AuthenticatedStatus 
            //      $AuthenticationInformation->AuthenticatedWith 
            //      $AuthenticationInformation->ECI

            // $order = Order::find(Session::get('orderId'));
            $order = Order::find(Session::get('orderId'));
            $this->callingSale($transactionId);
            $this->callingQuery($transactionId);

            // $this->password = Session::has('password') ? Session::get('password') : null;
            // Mail::to(Session::get('orderInfo.email'))->send(new OrderConfirmation($order, $this->password));
            // Mail::to(env('MAIL_FROM_ADDRESS'))->send(new OrderConfirmationCC($order, $this->password));
            // Mail::to(Session::get('orderInfo.email'))->send(new Recite($order));
            // Session::forget('password');

            echo "<!DOCTYPE html>";
            echo "<head>";
            echo "<title>Form submitted</title>";
            // echo "<script type='text/javascript'>window.top.location.href = '" . url('callingSale'.$process_parameters) . "';</script>";
            echo "<script type='text/javascript'>window.top.location.href = '" . url('orderbekraftelse/' . $order->token) . "';</script>";
            echo "</head>";
            echo "<body></body></html>";

            // return $processResponse;
            // $this->callingSale($transactionId);
            // redirect('callingSale'.$process_parameters);
        } else {
            // if(Session::has('orderId')) {
            //     $order = Order::find(Session::get('orderId'));
            //     $order->delete();
            // }
            // $request->session()->flash('error_message', 'Betalningen avbröts');
            echo "<!DOCTYPE html>";
            echo "<head>";
            echo "<title>Form submitted</title>";
            echo "<script type='text/javascript'>window.parent.location.reload()</script>";
            echo "</head>";
            echo "<body></body></html>";
            // $processResponse .= "<br/>ResponseCode is not equal OK, call Query to get more information";
            // $processResponse .= "<h3><a href='" . url('callingQuery'.$process_parameters) . "'>Calling Query</a></h3>";
        }
        // return redirect('/varukorg');
    }


    public function callingSale($transactionId)
    {
        // $transactionId = '';
        // if($request->transactionId) {
        //     $transactionId = $request->transactionId;
        // }

        $description = "description of SALE operation";
        $operation = "SALE";
        $transactionAmount = "";
        $transactionReconRef = "";

        ####  PROCESS OBJECT  ####
        $ProcessRequest = new \Nets\ProcessRequest(
            $description,
            $operation,
            $transactionAmount,
            $transactionId,
            $transactionReconRef
        );


        $InputParametersOfProcess = array(
            "token"       => $this->token,
            "merchantId"  => $this->merchantId,
            "request"     => $ProcessRequest
        );

        $response = '';
        $response .= "<h3><font color='gray'>Input parameters:</font></h3>";

        $response .= "merchantId= " . $this->merchantId . "<br/>";
        $response .= "token= " . $this->token . "<br/>";

        $response .= "description= " . $description . "<br/>";
        $response .= "operation= " . $operation . "<br/>";
        $response .= "transactionAmount= " . $transactionAmount . "<br/>";
        $response .= "transactionId= " . $transactionId . "<br/>";
        $response .= "transactionReconRef= " . $transactionReconRef . "<br/>";

        /*
          // you can also display this way
          echo "<br/>Parameters in Process<br/>"; 
          echo "<pre>"; 
          echo print_r($InputParametersOfProcess); 
          echo "</pre>";
        */

        ####  START PROCESS CALL  ####
        try {
            if (strpos($_SERVER["HTTP_HOST"], 'uapp') > 0) {
                // Creating new client having proxy
                $client = new \SoapClient($this->wsdl, array('proxy_host' => "isa4", 'proxy_port' => 8080, 'trace' => true, 'exceptions' => true));
            } else {
                // Creating new client without proxy
                $client = new \SoapClient($this->wsdl, array('trace' => true, 'exceptions' => true));
            }

            $OutputParametersOfProcess = $client->__call('Process', array("parameters" => $InputParametersOfProcess));

            $ProcessResult = $OutputParametersOfProcess->ProcessResult;


            $response .= "<h3><font color='gray'>Output parameters:</font></h3>";
            $response .= "<pre>";
            $response .= print_r($ProcessResult, true);
            $response .= "</pre>";

            $response .= "<h3><font color='green'>Process call successfully done.</font></h3>";

            $process_parameters = "?transactionId=" .  $ProcessResult->TransactionId;

            if ($ProcessResult->ResponseCode == "OK") {
                // $this->paymentType = "Kort";
                // $this->isPaymentRejected = 0;
                // $this->cardType = '';
                // $this->maskedCardNumber = '';
                // $this->orderStatusId = 1;
                // $this->saveUser();
                // $this->saveOrder();


                $order = Order::find(Session::get('orderId'));
                $order->order_status_id = 1;
                $order->authorization_id = $ProcessResult->AuthorizationId;
                $order->batch_number = $ProcessResult->BatchNumber;
                $order->transaction_date = $ProcessResult->ExecutionTime;
                $order->transaction_id = $ProcessResult->TransactionId;
                $order->save();
                $this->saveOrderDetails();
                $this->saveCarData();

                $this->password = Session::has('password') ? Session::get('password') : null;
                Mail::to(Session::get('orderInfo.email'))->send(new OrderConfirmation($order, $this->password));
                Mail::to(env('MAIL_FROM_ADDRESS'))->send(new OrderConfirmationCC($order, $this->password));
                Mail::to(Session::get('orderInfo.email'))->send(new Recite($order));
                Session::forget('password');

                $response .= "<br/>ResponseCode is OK, so you can call or Credit:";
                $response .= "<h3><a href='callingCredit.php$process_parameters'>Calling Credit (Dont work)</a></h3>";
                $response .= "<h3><a href='" . url('callingQuery' . $process_parameters) . "'>Calling Query</a></h3>";
                $response .= "<h3><a href='index.php'>Test Webshops</a><h3>";

                // return $response;
                // $this->callingQuery($transactionId);
                // return redirect('callingQuery'.$process_parameters);
            } else {
                // $this->paymentType = "Kort";
                // $this->isPaymentRejected = 1;
                // $this->cardType = '';
                // $this->maskedCardNumber = '';
                // $this->orderStatusId = 3;
                // $this->saveUser();
                // $this->saveOrder();

                $order = Order::find(Session::get('orderId'));
                $order->order_status_id = 3;
                $order->is_payment_rejected = 1;
                $order->comment = "Kortbetalningen godkändes ej.";
                $order->save();

                $this->order->comment = "Kortbetalningen godkändes ej.";
                $this->order->save();

                Session::flash('error_message', 'Betalningen gick inte igenom. Säkerställ att du har angett rätt kortuppgifter och att du har tillräckligt med pengar på kortet. Om du fortfarande inte vet varför betalningen inte går igenom, vänligen kontakta support för hjälp.');
                echo "<!DOCTYPE html>";
                echo "<head>";
                echo "<title>Form submitted</title>";
                echo "<script type='text/javascript'>window.parent.location.reload()</script>";
                echo "</head>";
                echo "<body></body></html>";
            }
        } // End try
        catch (\SoapFault $fault) {
            Session::flash('error_message', 'Error: fel uppstod i betalningsprocessen. Säkerställ att du har angett rätt kortuppgifter och att du har tillräckligt med pengar på kortet. Om du fortfarande inte vet varför betalningen inte går igenom, vänligen kontakta support för hjälp.');

            echo "<!DOCTYPE html>";
            echo "<head>";
            echo "<title>Form submitted</title>";
            echo "<script type='text/javascript'>window.parent.location.reload()</script>";
            echo "</head>";
            echo "<body></body></html>";
        } // End catch
    }

    public function callingQuery($transactionId)
    {
        // $transactionId = '';
        // if($request->transactionId) {
        //     $transactionId = $request->transactionId;
        // }

        $response = '';
        $response = "<h3><font color='gray'>Input parameters:</font></h3>";

        $response .= "merchantId= " . $this->merchantId . "<br/>";
        $response .= "token= " . $this->token . "<br/>";
        $response .= "transactionId= " . $transactionId . "<br/>";


        ####  QUERY OBJECT  ####
        $QueryRequest = new \Nets\QueryRequest(
            $transactionId
        );

        ####  ARRAY WITH QUERY PARAMETERS  ####
        $InputParametersOfQuery = array(
            "token"       => $this->token,
            "merchantId"  => $this->merchantId,
            "request"     => $QueryRequest
        );


        ####  START QUERY CALL  ####
        try {
            if (strpos($_SERVER["HTTP_HOST"], 'uapp') > 0) {
                // Creating new client having proxy
                $client = new \SoapClient($this->wsdl, array('proxy_host' => "isa4", 'proxy_port' => 8080, 'trace' => true, 'exceptions' => true));
            } else {
                // Creating new client without proxy
                $client = new \SoapClient($this->wsdl, array('trace' => true, 'exceptions' => true));
            }


            $OutputParametersOfQuery = $client->__call('Query', array("parameters" => $InputParametersOfQuery));

            $QueryResult = $OutputParametersOfQuery->QueryResult;
            $order = Order::find(Session::get('orderId'));
            $order->masked_card_number = $QueryResult->CardInformation->MaskedPAN;
            $order->card_type = $QueryResult->CardInformation->PaymentMethod;
            $order->save();

            // $response .= "<h3><font color='gray'>Output parameters:</font></h3>";
            // $response .= "<pre>"; 
            // $response .= print_r($OutputParametersOfQuery, true);
            // $response .= "</pre>";

            // $response .= "<h3><font color='green'>Query call successfully done.</font></h3>";
            // $response .= "<h3><a href='index.php'>Test Webshops</a><h3>";
            // return $response;
            // redirect('orderbekraftelse/'. $order->token);

        } // End try
        catch (\SoapFault $fault) {
            ## Do some error handling in here...
            Session::flash('error_message', 'Error: fel uppstod i betalningsprocessen. Säkerställ att du har angett rätt kortuppgifter och att du har tillräckligt med pengar på kortet. Om du fortfarande inte vet varför betalningen inte går igenom, vänligen kontakta support för hjälp.');

            echo "<!DOCTYPE html>";
            echo "<head>";
            echo "<title>Form submitted</title>";
            echo "<script type='text/javascript'>window.parent.location.reload()</script>";
            echo "</head>";
            echo "<body></body></html>";
            // $response .= "<h3><a href='index.php'>Test Webshops</a><h3>";

            // $response .= "<br/><font color='red'>EXCEPTION!";   
            // $response .= "<br/><br/><h3><font color='red'>Query call failed</font></h3>";
            // $response .= "<pre>"; 
            // $response .= print_r($fault, true);
            // $response .= "</pre>";
        } // End catch
        ####  END   QUERY CALL  ####

    }



    public function saveDeliveryInfo(Request $request)
    {
        // dd($request->all());
        if (Cart::count() == 0) {
            Session::flash('error_message', 'Varukorgen är tom');
            return redirect('varukorg');
        }

        // $paymentMethods = PaymentMethod::all();
        $request->session()->put('orderInfo.deliveryId', $request->deliveryId);
        $cartCalculator = new CartCalculator;

        $netsPaymentForm = $this->createNetsForm();

        return [
            // "paymentMethodOptions" => view('checkout.partials.payment.payment_methods', compact('cartCalculator', 'paymentOptions', 'currencyMultiplier', 'currency'))->render(),
            // "paymentMethodOptions" => view('checkout.partials.payment.payment_methods', compact('snippet'))->render(),
            'netsPaymentForm' => $netsPaymentForm,
            'totalPriceShipping' => $cartCalculator->totalPriceShipping(),
            'totalTax' => $cartCalculator->totalTax(),
            'totalPriceExTax' => $cartCalculator->totalPriceExTax(),
            'totalPriceIncTax' => $cartCalculator->totalPriceIncTax()
        ];


        // return [
        //     'paymentMethodOptions' => view('checkout.partials.payment.payment_methods', compact('paymentMethods'))->render(),
        //     'totalPriceShipping' => $cartCalculator->totalPriceShipping(),
        //     'totalTax' => $cartCalculator->totalTax(),
        //     'totalPriceExTax' => $cartCalculator->totalPriceExTax(),
        //     'totalPriceIncTax' => $cartCalculator->totalPriceIncTax()
        // ];
    }

    public function handleCustomer()
    {
        $customerFullName = Session::get('orderInfo.billingFullName');
        $customerAddress = Session::get('orderInfo.billingAddress');
        $customerZipCode = Session::get('orderInfo.billingPostalCode');
        $customerCity = Session::get('orderInfo.billingCity');
        $customerCountry = Session::get('orderInfo.billingCountry');
        $customerEmail = Session::get('orderInfo.email');
        $ssn = Session::get('orderInfo.ssn');
        $customerType = Session::get('orderInfo.isCompany') ? "Business" : "Person";

        // if($customerCountry == 'SE') {
        $this->myOrder->setCountryCode($customerCountry);
        $this->myOrder->setCurrency("SEK");
        $currencyMultiplier = 1;
        $currency = "kr";
        // }

        // if($customerCountry == 'FI') {
        //     $this->myOrder->setCountryCode("FI");
        //     $this->myOrder->setCurrency("SEK");
        //     $currencyMultiplier = 1;
        //     $currency = "kr";
        //     // $this->myOrder->setCurrency("EUR");
        //     // $currencyMultiplier = 0.102710;
        //     // $currency = "€";
        // }

        // if($customerCountry == 'NO') {
        //     $this->myOrder->setCountryCode("NO");
        //     $this->myOrder->setCurrency("NOK");
        //     $currencyMultiplier = 1;
        //     $currency = "kr";
        //     // $this->myOrder->setCurrency("NOK");
        //     // $currencyMultiplier = 0.928602;
        //     // $currency = "Nkr";
        // }

        $this->myOrder->setOrderDate(Carbon::now()->format('Y-m-d'));
        $this->myOrder->setClientOrderNumber("order #" . Carbon::now()->format('Y-m-d H:i:s'));

        if ($customerType == "Business") {
            $myCustomerInformation = \WebPayItem::companyCustomer();
            $this->is_company = 1;
            $myCustomerInformation->setCompanyName($customerFullName);
        } else {
            $myCustomerInformation = \WebPayItem::individualCustomer();
            $this->is_company = 0;

            $customerFullName = explode(' ', $customerFullName);
            $customerFirstName = $customerFullName[0];
            $customerLastName = "";
            for ($i = 1; $i < count($customerFullName); $i++) {
                $customerLastName .= $customerFullName[$i] . " ";
            }
            $customerLastName = trim($customerLastName, ' ');

            $myCustomerInformation->setName($customerFirstName, $customerLastName);
        }

        if ($ssn) {
            $myCustomerInformation->setNationalIdNumber($ssn);
            $myCustomerInformation->setPhoneNumber(Session::get('orderInfo.billingPhoneNumber'));
        }

        $sveaAddress = \Svea\Helper::splitStreetAddress($customerAddress);
        $myCustomerInformation->setStreetAddress($sveaAddress[0], $sveaAddress[1]);
        $myCustomerInformation->setZipCode($customerZipCode)->setLocality($customerCity);
        $myCustomerInformation->setEmail($customerEmail);
        $this->myOrder->addCustomerDetails($myCustomerInformation);

        return;
    }


    public function handleOrderItems()
    {
        Session::put('orderInfo.deliveryMaxTime', 0);

        $tax = 25;
        $this->currencyMultiplier = 1;
        $taxMultiplier = 1;
        // if(Session::get('orderInfo.billingCountry') == "NO") {
        //     $tax = 0;
        //     $taxMultiplier = 0.8;
        // }

        if (sizeof(Cart::content()) > 0) {
            foreach (Cart::content() as $item) {
                $amount = $item->price * $this->currencyMultiplier * $taxMultiplier;
                $boughtItem = \WebPayItem::orderRow();
                // $boughtItem->setAmountExVat( 500.99 );
                $boughtItem->setAmountIncVat((float) $amount);
                $boughtItem->setVatPercent($tax);
                $boughtItem->setQuantity((int) $item->qty);
                $boughtItem->setDescription($item->name);
                $boughtItem->setArticleNumber($item->id);


                // Add firstBoughtItem to order row
                $this->myOrder->addOrderRow($boughtItem);

                $this->ajust[$item->id] = "";
                if ($item->options->et && strpos($item->name, 'Blank') !== false) {
                    $this->ajust[$item->id] = "Justera till ET: {$item->options->et} <br>";
                }

                if ($item->options->pcd != null) {
                    $this->ajust[$item->id] .= "PCD: {$item->options->pcd}";
                }

                $maxNumber = 0;
                if ($item->model->delivery_time) {
                    preg_match_all('!\d+!', $item->model->delivery_time, $number);
                    $maxNumber = max(max($number));
                }

                if (Session::get('orderInfo.deliveryMaxTime') < $maxNumber) {
                    Session::put('orderInfo.deliveryMaxTime', $maxNumber);
                    Session::put('orderInfo.deliveryTime', $item->model->delivery_time);
                }
            }
        }

        $cartCalculator = new CartCalculator;
        $shippingCost = $cartCalculator->totalPriceShipping();
        $shippingFee = \WebPayItem::shippingFee();
        $shippingFee->setVatPercent($tax);
        $shippingFee->setAmountIncVat((float) ($shippingCost * $this->currencyMultiplier * $taxMultiplier));
        $shippingFee->setName('shippingCost');
        $this->myOrder->addFee($shippingFee);

        if (Session::has('campaign.discount')) {
            $fixedDiscount = \WebPayItem::fixedDiscount();
            $fixedDiscount->setAmountIncVat(Session::get('campaign.discount') * $this->currencyMultiplier * $taxMultiplier); // optional, Float, use precisely two of the price specification methods
            // $fixedDiscount->setAmountExVat(1.0); // optional, Float, recommended, use precisely two of the price specification methods
            $fixedDiscount->setVatPercent($tax); // optional, Integer, recommended, use precisely two of the price specification methods
            $fixedDiscount->setName("FixedDiscount"); // optional, invoice & payment plan orders will merge "name" with "description", String(256) for card and direct
            $fixedDiscount->setDescription("BlackFriday"); // optional, String(40) for invoice & payment plan orders will merge "name" with "description" , String(512) for card and direct
            $this->myOrder->addDiscount($fixedDiscount);
        }

        return;
    }



    public function storeCardPayment(Request $request)
    {
        // dd($request->all());
        $countryCode = Session::get('orderInfo.billingCountry'); // should match request countryCode

        // the raw request response is posted to the returnurl (this page) from Svea.
        $rawResponse = $request->all();

        $this->handleCustomer();
        $this->handleOrderItems();

        // decode the raw response by passing it through the SveaResponse class
        $myResponse = new \SveaResponse($rawResponse, $countryCode, $this->myConfig);

        // dd($myResponse->response, $this->myOrder, Session::get('orderInfo'));
        if ($myResponse->response->accepted) {
            //all is good
            $this->sveaOrderId = $myResponse->response->transactionId;
            $this->paymentType = "Kort";
            $this->cardType = $myResponse->response->cardType;
            $this->maskedCardNumber = $myResponse->response->maskedCardNumber;
            $this->orderStatusId = 1;
            $this->saveUser();
            $this->saveOrder();
            $this->saveCarData();
            $this->saveOrderDetails();
            Mail::to(Session::get('orderInfo.email'))->send(new OrderConfirmation($this->order, $this->password));
            Mail::to(env('MAIL_FROM_ADDRESS'))->send(new OrderConfirmationCC($this->order, $this->password));
            return redirect("orderbekraftelse/" . Session::get('orderInfo.token'));
        }

        // Storage::append('svea.log', 
        //     '['.Carbon::now().'] Errorkod: '. $myResponse->response->resultcode .
        //     ". paymentMethod: ". $myResponse->response->paymentMethod.', Accepted: '.json_encode($myResponse->response->accepted).', cardType: '.json_encode($myResponse->response->cardType).', maskedCardNumber: '.json_encode($myResponse->response->maskedCardNumber).', transactionId: '.json_encode($myResponse->response->transactionId).', clientOrderNumber: '.json_encode($myResponse->response->clientOrderNumber).
        //     ". \nMessage: ". $myResponse->response->errormessage. 
        //     ". \nOrderInfo: ". json_encode($this->myOrder)
        // );

        session()->flash('error_message', "Betalningen gick inte igenom. Var snäll och försök igen eller kontakta " . App\Setting::getSupportMail());

        // session()->flash('myResponse', $myResponse->response);
        // session()->flash('myOrder', $this->myOrder);
        // session()->flash('countryCode', $countryCode);
        return redirect('varukorg');
    }


    public function invoicePayment()
    {
        if (Cart::count() == 0) {
            Session::flash('error_message', 'Varukorgen är tom');
            return redirect('varukorg');
        }

        $customerCountry = Session::get('orderInfo.billingCountry');

        // if($customerCountry == 'SE') {
        $this->myOrder->setCountryCode($customerCountry);
        $this->myOrder->setCurrency("SEK");
        $currencyMultiplier = 1;
        $currency = "kr";
        // }

        // if($customerCountry == 'FI') {
        //     $this->myOrder->setCountryCode("FI");
        //     $this->myOrder->setCurrency("SEK");
        //     $currencyMultiplier = 1;
        //     $currency = "kr";
        //     // $this->myOrder->setCurrency("EUR");
        //     // $currencyMultiplier = 0.102710;
        //     // $currency = "€";
        // }

        // if($customerCountry == 'NO') {
        //     $this->myOrder->setCountryCode("NO");
        //     $this->myOrder->setCurrency("NOK");
        //     $currencyMultiplier = 1;
        //     $currency = "kr";
        //     // $this->myOrder->setCurrency("NOK");
        //     // $currencyMultiplier = 0.928602;
        //     // $currency = "Nkr";
        // }

        $cartCalculator = new CartCalculator;

        return view('checkout.invoice_payment', compact('cartCalculator', 'currencyMultiplier', 'currency'));
    }

    public function storeInvoicePayment(Request $request)
    {
        // $requestAddress = \WebPay::getAddresses($this->myConfig)
        //     ->setCountryCode(Session::get('orderInfo.billingCountry'))
        //     ->setCustomerIdentifier(Session::get('orderInfo.ssn')); 
        // ->setCustomerIdentifier($request->ssn); 

        // $responseAddress = $requestAddress->getIndividualAddresses()->doRequest();

        // if($responseAddress->resultcode !== "Accepted") {
        //     return redirect('faktura');
        // }

        // $person = $responseAddress->customerIdentity[0];
        // $request->session()->put('orderInfo.billingFirstName', $person->firstName);
        // $request->session()->put('orderInfo.billingLastName', $person->lastName);
        // $request->session()->put('orderInfo.billingAddress', $person->street);
        // $request->session()->put('orderInfo.billingPostalCode', $person->zipCode);
        // $request->session()->put('orderInfo.billingCity', $person->locality);
        // $request->session()->put('orderInfo.phoneNumber', $person->phoneNumber);
        // $request->session()->put('orderInfo.ssn', $request->ssn);

        $this->handleCustomer();
        $this->handleOrderItems();

        /************* Invoice paymentmethod ***************/
        $requestPayment = $this->myOrder->useInvoicePayment();
        // Then send the request to Svea, and immediately receive the service response object
        $responsePayment = $requestPayment->doRequest();
        // dd($requestPayment, $responsePayment);


        if ($responsePayment->accepted) {
            //all is good
            $person = $responsePayment->customerIdentity;
            // $nameArr = explode(' ', $person->fullName);
            // $firstName = "";
            // $lastName = $nameArr[0];
            // for ($i=1; $i < count($nameArr); $i++) { 
            //     $firstName .= $nameArr[$i] . " ";
            // }
            // $firstName = trim($firstName, ' ');

            $request->session()->put('orderInfo.billingFullName', $person->fullName);
            $request->session()->put('orderInfo.billingAddress', $person->street);
            $request->session()->put('orderInfo.billingPostalCode', $person->zipCode);
            $request->session()->put('orderInfo.billingCity', $person->locality);
            // $request->session()->put('orderInfo.phoneNumber', $person->phoneNumber);

            $this->sveaOrderId = $responsePayment->sveaOrderId;
            $this->paymentType = "Svea faktura";
            $this->orderStatusId = 1;
            $this->saveUser();
            $this->saveOrder();
            $this->saveOrderDetailsSvea();
            $this->saveCarData();
            // $order = Order::find($this->orderID);
            Mail::to(Session::get('orderInfo.email'))->send(new OrderConfirmation($this->order, $this->password));
            Mail::to(env('MAIL_FROM_ADDRESS'))->send(new OrderConfirmationCC($this->order, $this->password));
            return redirect("orderbekraftelse/" . Session::get('orderInfo.token'));
        }

        Storage::append(
            'svea.log',
            '[' . Carbon::now() . '] Errorkod: ' . $responsePayment->resultcode .
                ". OrderType: " . $this->myOrder->orderType . ', Accepted: ' . json_encode($responsePayment->accepted) . ', sveaWillBuyOrder: ' . json_encode($responsePayment->sveaWillBuyOrder) .
                ". \nMessage: " . $responsePayment->errormessage .
                ". \nUserInfo: " . json_encode($this->myOrder->customerIdentity)
        );

        // if($responsePayment->resultcode == 40002) {
        //     session()->flash('error_message', "Du angav ett icke giltigt personnummer. Var snäll och försök igen eller kontakta ".App\Setting::getSupportMail());
        //     return redirect('faktura');
        // }

        // if($responsePayment->resultcode == 30003) {
        //     session()->flash('error_message', "Ingen privatperson funnen med det angivna personnumret. Var snäll och försök igen eller kontakta ".App\Setting::getSupportMail());
        //     return redirect('faktura');
        // }

        $this->paymentType = "Svea faktura";
        $this->orderStatusId = 3;
        $this->isPaymentRejected = 1;
        $this->saveUser();
        $this->saveOrder();
        $this->saveOrderDetailsSvea();
        $this->saveCarData();
        // $order = Order::find($this->orderID);
        Mail::to(Session::get('orderInfo.email'))->send(new PaymentRejected($this->order, $responsePayment->resultcode, $this->password));

        session()->flash('error_message', "Vi beklagar men er order {$this->order->id} har ej gått igenom. Det kan finnas flera anledningar till varför en order nekas. Vill du komma i kontakt med Svea, så har du kontaktuppgifterna här: 08-514 931 13. Ange Sveas följande errorkod för snabb handledning med Svea: {$responsePayment->resultcode} 
            <br> 
            <br> 
            Vid nekad kredit kan du välja att betala mot Plusgiro: ???, Bankgiro: ??? eller direkt kortbetalning i kassan. Denna information har även skickats till angiven E-post adress.");
        return redirect('faktura');

        // return view('checkout.confirmOrder');
    }

    // public function inStorePayment()
    // {
    //     $cartCalculator = new CartCalculator;
    //     return view('checkout.in_store_payment', compact('cartCalculator'));
    // }

    // public function storeInStorePayment()
    // {
    //     $this->handleCustomer();
    //     $this->handleOrderItems();

    //     $requestPayment = $this->myOrder->useInvoicePayment();

    //     $this->paymentType = "Betala i butik";
    //     $this->orderStatusId = 1;
    //     $this->saveUser();
    //     $this->saveOrder();
    //     $this->saveOrderDetails();
    //     $this->saveCarData();
    //     // $order = Order::find($this->orderID);
    // Mail::to(Session::get('orderInfo.email'))->send(new OrderConfirmation($this->order, $this->password));
    //     return redirect("orderbekraftelse/".Session::get('orderInfo.token'));
    // }


    public function createOrder(Request $request)
    {
        // dd($request->all());
        if (Cart::count() == 0) {
            Session::flash('error_message', 'Varukorgen är tom');
            return redirect('varukorg');
        }

        if (Session::get('orderInfo.regNumber') == null) {
            return redirect('varukorg');
        }

        $cartCalculator = new CartCalculator;
        Session::put('orderInfo.deliveryMaxTime', 0);

        foreach (Cart::content() as $item) {

            // Funkar inte helt med klarna och fraktkostnader. Fixas senare med annan kund
            // $unit_price_with_moms = ((int) $item->price + ($shippingCost/ (int) $item->qty) ) * 100;
            $cart[] = [
                'reference' => $item->id,
                'name' => $item->name,
                'quantity' => (int) $item->qty,
                'discount_rate' => 0,
                'unit_price' => (int) $item->price * 100,
                'total_price_excluding_tax' => (int) $item->price * 80,
                'total_price_including_tax' => (int) $item->price * 100,
                'total_tax_amount' => (int) $item->price * 20,
                'tax_rate' => 2500
            ];

            $this->ajust[$item->id] = "";
            if ($item->options->pcd != null) {
                $this->ajust[$item->id] = $item->options->pcd;
            }

            $maxNumber = 0;
            if ($item->model->delivery_time) {
                preg_match_all('!\d+!', $item->model->delivery_time, $number);
                $maxNumber = max(max($number));
            }

            if (Session::get('orderInfo.deliveryMaxTime') < $maxNumber) {
                Session::put('orderInfo.deliveryMaxTime', $maxNumber);
                Session::put('orderInfo.deliveryTime', $item->model->delivery_time);
            }
        }

        // $cart[] = [
        //     'type' => 'shipping_fee',
        //     'reference' => 'SHIPPING',
        //     'name' => 'Shipping Fee',
        //     'quantity' => 1,
        //     'unit_price' => $cartCalculator->totalPriceShipping() * 100,
        //     'total_price_excluding_tax' => (int) $item->price * 80,
        //     'total_price_including_tax' => (int) $item->price * 100,
        //     'total_tax_amount' => (int) $item->price * 20,
        //     'tax_rate' => 2500
        // ];

        $this->order['cart']['items'] = $cart;

        $customerType = "Person";

        if (Session::has('orderInfo.isCompany')) {
            if (Session::get('orderInfo.isCompany') == 1) {
                $customerType = "Business";
            }
        }

        if (isset($request->isCompanyCheck)) {
            if ($request->isCompanyCheck == 1) {
                $customerType = "Business";
            }
        }

        $this->order['customer']['date_of_birth'] = $request->ssn;
        $this->order['customer']['organization_registration_id'] = $request->ssn;
        $this->order['customer']['type'] = $customerType;;
        $this->order['billing_address']['given_name'] = $request->firstName;
        $this->order['billing_address']['family_name'] = $request->lastName;
        $this->order['billing_address']['email'] = $request->email;
        $this->order['billing_address']['phone'] = $request->billingPhone;
        $this->order['billing_address']['street_address'] = $request->billingStreetAddress;
        $this->order['billing_address']['postal_code'] = $request->billingPostalCode;
        $this->order['billing_address']['city'] = $request->billingCity;
        $this->order['billing_address']['country'] = $request->billingCountry;
        $this->order['shipping_address']['given_name'] = $request->firstName;
        $this->order['shipping_address']['family_name'] = $request->lastName;
        $this->order['shipping_address']['email'] = $request->email;
        $this->order['shipping_address']['phone'] = $request->billingPhone;
        $this->order['shipping_address']['street_address'] = $request->billingStreetAddress;
        $this->order['shipping_address']['postal_code'] = $request->billingPostalCode;
        $this->order['shipping_address']['city'] = $request->billingCity;
        $this->order['shipping_address']['country'] = $request->billingCountry;

        if (isset($request->isOrderShippingAddress)) {
            $firstName = strtok($request->fullName, " ");
            $lastName = trim($request->fullName, $firstName);
            $this->order['shipping_address']['given_name'] = $firstName;
            $this->order['shipping_address']['family_name'] = $lastName;
            $this->order['shipping_address']['email'] = $request->email;
            $this->order['shipping_address']['phone'] = $request->shippingPhone;
            $this->order['shipping_address']['street_address'] = $request->shippingStreetAddress;
            $this->order['shipping_address']['postal_code'] = $request->shippingPostalCode;
            $this->order['shipping_address']['city'] = $request->shippingCity;
            $this->order['shipping_address']['country'] = $request->shippingCountry;
        }

        $request->session()->put('orderInfo.token', bin2hex(openssl_random_pseudo_bytes(16)));

        $this->saveUser();
        $this->saveOrder();
        $this->saveOrderDetailsSvea();
        $this->saveCarData();

        Mail::to($this->order['billing_address']['email'])->send(new OrderConfirmation($this->createdOrder, $this->password));


        return redirect("orderbekraftelse/" . Session::get('orderInfo.token'));
    }




    public function confirmOrder($token)
    {
        $order = Order::where('token', $token)->first();
        $orderDetails = OrderDetail::where('order_id', $order->id)->get();
        // dd($order, $orderDetails);
        if (isset($order) <= 0) {
            session()->flash('error_message', "Finns ingen order att bekräfta. Var snäll och beställ igen eller kontakta " . App\Setting::getSupportMail());
            return redirect('varukorg');
        }

        //Empty cart
        $cookie = json_decode(Cookie::get('cookie'), true);
        $adTracktion = isset($cookie['adTracktion']) ? $cookie['adTracktion'] : null;
        // dd($cookie, $cookie['adTracktion'], $adTracktion, $orderDetails);
        // unset($cookie['adTracktion']);
        unset($cookie['addMount']);
        unset($cookie['addTPMS']);
        $cookie = json_encode($cookie);
        Cookie::queue('cookie', $cookie, 60 * 24 * 7);
        Cart::destroy();

        Session::forget('orderInfo');
        Session::forget('campaign');

        return view('checkout.confirm_order', compact('order', 'adTracktion'));
    }

    private function saveUser()
    {
        $temp = "alihaider123go@gmail.com";
        $updateUser = User::where('email', Session::get('orderInfo.email'))->first();

        if (isset($updateUser)) {
            $updateUser->org_number = Session::get('orderInfo.ssn') === null ?: Session::get('orderInfo.ssn');
            $updateUser->billing_full_name = Session::get('orderInfo.billingFullName');
            $updateUser->billing_phone = Session::get('orderInfo.billingPhoneNumber');
            $updateUser->billing_street_address = Session::get('orderInfo.billingAddress');
            $updateUser->billing_postal_code = Session::get('orderInfo.billingPostalCode');
            $updateUser->billing_city = Session::get('orderInfo.billingCity');
            $updateUser->billing_country = Session::get('orderInfo.billingCountry');
            // $updateUser->billing_country = 'SE';
            $updateUser->shipping_full_name = Session::get('orderInfo.shippingFullName');
            $updateUser->shipping_phone = Session::get('orderInfo.shippingPhoneNumber');
            $updateUser->shipping_street_address = Session::get('orderInfo.shippingAddress');
            $updateUser->shipping_postal_code = Session::get('orderInfo.shippingPostalCode');
            $updateUser->shipping_city = Session::get('orderInfo.shippingCity');
            $updateUser->shipping_country = Session::get('orderInfo.shippingCountry');
            // $updateUser->shipping_country = 'SE';

            $updateUser->save();
            $this->userID = $updateUser->id;
            return;
        }

        $this->password = str_random(8);

        $createUser = new User;
        $createUser->user_type_id = 1;
        $createUser->first_name = Session::get('orderInfo.firstName');
        $createUser->last_name = Session::get('orderInfo.lastName');
        $createUser->company_name = Session::get('orderInfo.companyName');
        $createUser->email = Session::get('orderInfo.email');
        $createUser->password = bcrypt($this->password);
        $createUser->org_number = Session::get('orderInfo.ssn') === null ?: Session::get('orderInfo.ssn');
        $createUser->billing_full_name = Session::get('orderInfo.billingFullName');
        $createUser->billing_phone = Session::get('orderInfo.billingPhoneNumber');
        $createUser->billing_street_address = Session::get('orderInfo.billingAddress');
        $createUser->billing_postal_code = Session::get('orderInfo.billingPostalCode');
        $createUser->billing_city = Session::get('orderInfo.billingCity');
        $createUser->billing_country = Session::get('orderInfo.billingCountry');
        // $createUser->billing_country = 'SE';
        $createUser->shipping_full_name = Session::get('orderInfo.shippingFullName');
        $createUser->shipping_phone = Session::get('orderInfo.shippingPhoneNumber');
        $createUser->shipping_street_address = Session::get('orderInfo.shippingAddress');
        $createUser->shipping_postal_code = Session::get('orderInfo.shippingPostalCode');
        $createUser->shipping_city = Session::get('orderInfo.shippingCity');
        $createUser->shipping_country = Session::get('orderInfo.shippingCountry');
        // $createUser->shipping_country = 'SE';
        // $createUser->phone = Session::get('orderInfo.phoneNumber');
        $createUser->is_company = Session::get('orderInfo.isCompany');
        $createUser->is_active = 1;
        $createUser->save();
        $this->userID = DB::getPdo()->lastInsertId();
        // dd($order);
        return;
    }

    private function saveOrder()
    {
        // dd($this->myOrder);
        $createOrder = Order::find(Session::get('orderId'));

        if (!isset($createOrder)) {
            $createOrder = new Order;
        }

        $cartCalculator = new CartCalculator;
        $cartCalculator->setCurrency();


        $createOrder->user_id = $this->userID;
        $createOrder->ip_number = Session::get('orderInfo.ipNumber');
        // $createOrder->svea_order_id = $this->sveaOrderId;
        $createOrder->order_status_id = $this->orderStatusId;
        $createOrder->is_payment_rejected = $this->isPaymentRejected;
        $createOrder->payment_type = $this->paymentType;
        $createOrder->card_type = $this->cardType;
        $createOrder->masked_card_number = $this->maskedCardNumber;
        $createOrder->campaign_code = !isset($this->campaignCode) ?: $this->campaignCode;
        $createOrder->delivery_method_id = Session::has('orderInfo.deliveryId') ? Session::get('orderInfo.deliveryId') : 1;
        // $createOrder->payment_method_id = Session::get('orderInfo.paymentId');
        $createOrder->payment_method_id = 0;
        $createOrder->shipping_fee = $cartCalculator->totalPriceShipping(); //round($this->shippingFee, 2);
        $createOrder->total_price_excluding_tax = $cartCalculator->totalPriceExTax(); //round(($price * 0.8), 2);
        $createOrder->total_price_including_tax = $cartCalculator->totalPriceIncTax(); //round($price, 2);
        $createOrder->total_tax_amount = $cartCalculator->totalTax(); //round(($price * 0.2), 2);
        $createOrder->discount =  Session::get('campaign.discount') * $this->currencyMultiplier;
        $createOrder->currency = 'SEK';
        $createOrder->currency_notation =  "kr";
        $createOrder->billing_full_name =  Session::get('orderInfo.billingFullName');
        $createOrder->billing_phone = Session::get('orderInfo.billingPhoneNumber');
        $createOrder->billing_street_address = Session::get('orderInfo.billingAddress');
        $createOrder->billing_postal_code = Session::get('orderInfo.billingPostalCode');
        $createOrder->billing_city = Session::get('orderInfo.billingCity');
        $createOrder->billing_country = Session::get('orderInfo.billingCountry');
        // $createOrder->billing_country = 'SE';
        $createOrder->shipping_full_name = Session::get('orderInfo.shippingFullName');
        $createOrder->shipping_phone = Session::get('orderInfo.shippingPhoneNumber');
        $createOrder->shipping_street_address = Session::get('orderInfo.shippingAddress');
        $createOrder->shipping_postal_code = Session::get('orderInfo.shippingPostalCode');
        $createOrder->shipping_city = Session::get('orderInfo.shippingCity');
        $createOrder->shipping_country = Session::get('orderInfo.shippingCountry');
        // $createOrder->shipping_country = 'SE';
        $createOrder->delivery_time = Session::get('orderInfo.deliveryTime') !== null ? Session::get('orderInfo.deliveryTime') : "";
        $createOrder->reference = Session::get('orderInfo.reference');
        $createOrder->message = Session::get('orderInfo.message');
        $createOrder->org_number = Session::get('orderInfo.ssn');
        $createOrder->is_company = Session::get('orderInfo.isCompany');

        $dt = Carbon::now();
        $dt->subWeek();
        $adTracking = AdTracking::where('ip_number', $createOrder->ip_number)->where('created_at', '>=', $dt)->first();
        $createOrder->is_affiliate_customer = isset($adTracking) > 0 ? 1 : 0;
        $createOrder->token = Session::get('orderInfo.token');
        $createOrder->save();
        $this->orderID = DB::getPdo()->lastInsertId();
        $this->order = $createOrder;
        // $this->createdOrder = $createOrder;
        Session::put('orderId', DB::getPdo()->lastInsertId());

        // dd($order);
        return $createOrder;
    }

    private function saveOrderDetails()
    {
        $customerCountry = Session::get('orderInfo.billingCountry');
        $currency = "kr";
        $currencyMultiplier = 1;
        $taxMultiplier = 1;

        // For AdTracktion
        // $gotRims = false;
        // $gotTires = false;
        $rimQty = 0;
        $tireQty = 0;
        $gotCompleteRims = false;
        $gotCompleteTires = false;

        $this->ajust = Session::has('orderInfo.ajust') ? Session::get('orderInfo.ajust') : '';

        if (sizeof(Cart::content()) > 0) {
            foreach (Cart::content() as $item) {
                if (!isset($item->id))
                    continue;

                $amount = $item->price * $currencyMultiplier * $taxMultiplier;
                $product = Product::findOrFail($item->id);
                // dd($this->ajust[$product->id], $item->articleNumber, $product->id);

                $createOrderDetails = new OrderDetail;
                $createOrderDetails->order_id = Session::get('orderId');
                $createOrderDetails->product_id = $item->id;
                $createOrderDetails->main_supplier_product_id = $product->main_supplier_product_id;
                $createOrderDetails->product_name = $product->product_name;
                $createOrderDetails->quantity = $item->qty;
                $createOrderDetails->discount = $item->discountPercent;
                $createOrderDetails->net_price = $product->price * $currencyMultiplier;
                $createOrderDetails->unit_price = $amount;
                $createOrderDetails->total_price_excluding_tax = $amount * $item->qty * 0.8 * $currencyMultiplier;
                $createOrderDetails->total_price_including_tax = $amount * $item->qty * $currencyMultiplier;
                $createOrderDetails->total_tax_amount = $amount * $item->qty * 0.2 * $currencyMultiplier;
                $createOrderDetails->tax = $item->vatPercent * $currencyMultiplier;
                $createOrderDetails->currency = 'SEK';
                $createOrderDetails->ajust = isset($this->ajust[$product->id]) ? $this->ajust[$product->id] : null;
                $createOrderDetails->save();

                if ($product->product_category_id <= 2 && $product->quantity > 0) {
                    $product->quantity = $product->quantity - $item->qty;
                    $product->save();
                }

                // For AdTracktion
                if ($item->options->product_category_id == 2) {
                    // $gotRims = true;
                    $rimQty += $item->qty;
                }

                if ($item->options->product_category_id == 1) {
                    // $gotTires = true;
                    $tireQty += $item->qty;
                }

                // if($item->options->product_category_id == 2 && $item->qty >= 4)
                //     $gotCompleteRims = true;

                // if($item->options->product_category_id == 1 && $item->qty >= 4)
                //     $gotCompleteTires = true;
            }
        }

        $cookie = json_decode(Cookie::get('cookie'), true);
        $cookie['adTracktion']['rimQty'] = $rimQty;
        $cookie['adTracktion']['tireQty'] = $tireQty;
        $cookie = json_encode($cookie);
        Cookie::queue('cookie', $cookie, 60 * 24 * 7);

        return;
    }

    public function saveCarData()
    {
        $carSearch = json_decode(Cookie::get('carSearch'), true);
        $searchData = $carSearch['searchData'];

        if (empty($carSearch['searchData'])) {
            $createCarData = new CarData;
            $createCarData->order_id = Session::get('orderId');
            $createCarData->reg_number = Session::get('orderInfo.regNumber');
            $createCarData->save();
            return;
        }

        $createCarData = new CarData;
        $createCarData->order_id = Session::get('orderId');
        $createCarData->reg_number = $searchData['RegNumber'];
        $createCarData->car_model = $searchData['Manufacturer'] . " " . $searchData['ModelName'] . " " . $searchData['FoundYear'];
        $createCarData->front_tire = $searchData['FoundDackFront'];
        $createCarData->pcd = $searchData['PCD'];
        $createCarData->offset = $searchData['Offset'];
        $createCarData->nav = $searchData['ShowCenterBore'];
        $createCarData->oe_type = $searchData['OE_Type'];
        $createCarData->save();

        return;
    }


    public function pushOrder()
    {
        $username = env('API_SEARCH_USER');
        $password = env('API_SEARCH_PASS');

        $headers[] = 'Authorization: Basic ' .
            base64_encode($username . ':' . $password);
        $headers[] = 'Content-Type: application/json';

        $orders = Order::whereIn('order_status_id', [1, 2, 4])->where('is_pushed', 0)->get();

        // $order = Order::find(49);

        foreach ($orders as $order) {

            $different_shipping = 1;
            if (
                $order->billing_full_name == $order->shipping_full_name &&
                $order->billing_phone == $order->shipping_phone &&
                $order->billing_street_address == $order->shipping_street_address &&
                $order->billing_postal_code == $order->shipping_postal_code &&
                $order->billing_city == $order->shipping_city &&
                $order->billing_country == $order->shipping_country
            ) {
                $different_shipping = 0;
            }

            $items = [];
            foreach ($order->orderDetails as $item) {
                $itemType = '';

                if ($item->product->product_category_id == 1)
                    $itemType = 'dack';

                if ($item->product->product_category_id == 2)
                    $itemType = 'falgar';

                if ($item->product->product_category_id == 3 && $item->product->product_type_id != 15)
                    $itemType = 'tilbehor';

                if ($item->product->product_type_id == 15)
                    $itemType = 'MB';

                $items[] = [
                    'item_id'              =>  $item->main_supplier_product_id,
                    'item_type'            =>  $itemType,
                    'item_qty'             =>  $item->quantity,
                    'item_price'           =>  $item->unit_price * 0.8,
                ];
            }

            // dd($items);

            $deliveryType = 5;
            // if($order->delivery_method_id == 2) {
            //     $deliveryType = 1;
            // }

            $data = [
                'order_reference'   => $order->id,
                'devliery_type'     => $deliveryType,
                'shipping_charges'  => $order->shipping_fee,
                'order_regno'       => $order->carData->reg_number,
                'order_message'     => $order->comment,
                'coupon_code'       => null,
                'coupon_price'      => null,
                'payment_detail'    => [
                    'payment_method'    => ($order->payment_type == "Kort" ? "CC" : ($order->payment_type == "Avarda" ? "AV" : "AF")),
                    'transaction_id'    => $order->transaction_id,
                    'reservation_id'    => null
                ],
                'customer'          => [
                    'personal_number'      =>  $order->org_number,
                    'user_email'           =>  $order->user->email,
                    'is_company'           =>  $order->is_company,
                    'different_shipping'   =>  $different_shipping,
                    'company_name'         =>  $order->is_company ? $order->billing_full_name : null,
                    'billing_fname'        =>  !$order->is_company ? $order->user->first_name : null,
                    'billing_lname'        =>  !$order->is_company ? $order->user->last_name : null,
                    'billing_phone'        =>  $order->billing_phone,
                    'billing_address'      =>  $order->billing_street_address,
                    'billing_postal_code'  =>  $order->billing_postal_code,
                    'billing_city'         =>  $order->billing_city,
                    'billing_country'      =>  'SE',
                    'shipping_name'        =>  $different_shipping ? $order->shipping_full_name : null,
                    'shipping_phone'       =>  $different_shipping ? $order->shipping_phone : null,
                    'shipping_address'     =>  $different_shipping ? $order->shipping_street_address : null,
                    'shipping_postal_code' =>  $different_shipping ? $order->shipping_postal_code : null,
                    'shipping_city'        =>  $different_shipping ? $order->shipping_city : null,
                    'shipping_country'     =>  $different_shipping ? 'SE' : null
                ],
                'items'             => $items
            ];
            // dd($data);

            $data = json_encode($data);
            $host = "https://slimapi.abswheels.se/createOrder/";
            $apiResponse = $this->CallAPIHeader("POST", $headers, $host, $data);
            $response = json_decode($apiResponse, true);

            if ($response['status'] == 'Ok') {
                $order->is_pushed = 1;
                $order->abs_order_id = $response['data']['OrderID'];
                $order->save();
                Storage::append(
                    'pushOrder.log',
                    '[' . Carbon::now() . '] OrderID: ' . $order->id .
                        "\nSuccess response: " . json_encode($response) .
                        "\nSent data: " . json_encode($data)
                );

                // dd($response, $data);
            } else {
                Storage::append(
                    'pushOrder.log',
                    '[' . Carbon::now() . '] OrderID: ' . $order->id .
                        '. ErrorMessage: ' . $response['ErrorMessage']
                );
            }
            // $status = $response['status'] == 'Ok' ? true : false;
            // dd($response, $response['status'], $status);
        }
        // dd($data);
    }

    public function getAddress(Request $request)
    {
        // dd($request->all());
        // $service_url = "url";
        // $JSONResponse = file_get_contents($service_url);
        // $customerInfo = json_decode($JSONResponse, true);


        //Noteringar: Förhindra Enter-knapp, Fixa meddelande ruta om fel info fylldes in.

        $username = 'ptest';
        $password = 'ptest';
        $headers[] = 'Authorization: Basic ' .
            base64_encode($username . ':' . $password);
        $headers[] = 'Content-Type: application/json';
        $host = "https://slimapi.abswheels.se/getUserDetail/$request->ssn/";
        $apiResponse = $this->CallAPIHeader("GET", $headers, $host);
        $apiResponse = json_decode($apiResponse);
        // dd($apiResponse);
        // return $apiResponse;
        $message = false;
        $customerInfoList = [];

        if (isset($apiResponse)) {

            if ($apiResponse->status == "Ok") {
                foreach ($apiResponse->data as $customerData) {
                    $customerInfoList[] = [
                        'firstName' => $customerData->FirstName,
                        'lastName' => $customerData->LastName,
                        'companyName' => $customerData->CompanyName,
                        'isCompany' => $customerData->IsCompany,
                        'address' => $customerData->StreetAddress,
                        'postalCode' => $customerData->PostalCode,
                        'city' => $customerData->City,
                    ];
                }
                Session::put('customerListInfo', $customerInfoList);
            } else {
                $message = $apiResponse->ErrorMessage;
            }
        }



        if (!empty($customerInfoList)) {
            $customerInfo = end($customerInfoList);
        } else {
            $customerInfo = [
                'firstName' => "",
                'lastName' => "",
                'fullName' => "",
                'companyName' => "",
                'isCompany' => 1,
                'address' => "",
                'postalCode' => "",
                'city' => "",
                'country' => "",
            ];
        }

        // if($request->alternativeAddressId) {
        //     $customerInfo = $customerInfoList[$request->alternativeAddressId];
        // }

        return [
            'orderForm' => view('checkout.partials.form.order_form', compact('customerInfoList'))->render(),
            'customerInfo' => $customerInfo,
            'message' => $message,
        ];
    }

    public function getAlternativeAddress(Request $request)
    {
        // dd($request->all());
        // $service_url = "url";
        // $JSONResponse = file_get_contents($service_url);
        // $companyInfo = json_decode($JSONResponse, true);

        if (isset($request->alternativeAddressId) && Session::has('customerListInfo')) {
            $addressInfo = Session::get('customerListInfo')[$request->alternativeAddressId];
        } else {
            $addressInfo = end($customerInfoList);
        }

        return [
            'addressInfo' => $addressInfo,
        ];
    }

    public function CallAPIHeader($method, $headers, $url, $data = false)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
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

    //////////////////////////// Klarna Integration 

    public function klarnaPayment(Request $request)
    {
        $order_amount = 0;
        $order_tax_amount = 0;
        foreach (Cart::content() as $item) {
            $cart[] = [
                "type" => "physical",
                "reference" => "123050",
                'name' => $item->name,
                'quantity' => (int) $item->qty,
                "quantity_unit" => "kg",
                'unit_price' => (int) $item->price * 100,
                "tax_rate" => 2500,
                "total_amount" => (int) $item->price * (int) $item->qty * 100,
                "total_tax_amount" => (int) $item->price * (int) $item->qty * 20
            ];
            $order_amount = $order_amount + (int) $item->price * (int) $item->qty * 100;
            $order_tax_amount = $order_tax_amount + (int) $item->price * (int) $item->qty * 20;
        }
        $data = [
            "purchase_country" => "gb",
            "purchase_currency" => "gbp",
            "locale" => "en-gb",
            "order_amount" => $order_amount,
            "order_tax_amount" => $order_tax_amount,
            "order_lines" => $cart,
            "merchant_urls" => [
                "terms" => "https://www.example.com/terms.html",
                "cancellation_terms" => "https://www.example.com/terms/cancellation.html",
                "checkout" => "https://www.example.com/checkout.html",
                "confirmation" => url('klarna_confirmation'),
                "push" => "https://www.example.com/api/push",
            ]
        ];

        $data = json_encode($data);
        $username = config("hjulonline.klarna_merchant_id");
        $password = config("hjulonline.klarna_shared_secret");
        $headers[] = 'Authorization: Basic ' . base64_encode($username . ':' . $password);

        $headers[] = 'Content-Type: application/json';

        $host = config("hjulonline.klarna_host") . "/checkout/v3/orders";

        $apiResponse = $this->CallAPIHeader("POST", $headers, $host, $data);
        $response = json_decode($apiResponse);

        Session::put('klarna_order_id', $response->order_id);
        return [
            'netsPaymentForm' => $response->html_snippet,
            'isAdmin' => false,
            'isValidOrder' => true
        ];
    }

    public function klarnaConfirmation()
    {
        $order_id = Session::get('klarna_order_id');
        $username = config("hjulonline.klarna_merchant_id");
        $password = config("hjulonline.klarna_shared_secret");
        $headers[] = 'Authorization: Basic ' . base64_encode($username . ':' . $password);
        $headers[] = 'Content-Type: application/json';
        $host = config("hjulonline.klarna_host") . "/checkout/v3/orders/" . $order_id;
        $apiResponse = $this->CallAPIHeader("Get", $headers, $host);
        $response = json_decode($apiResponse);

        $this->paymentType = "Klarna";
        $this->orderStatusId = 1;
        $this->saveUser();
        $order = $this->saveOrder();
        // $order = Order::find(Session::get('orderId'));
        $order->klarna_status = $response->status;
        $order->total_price_including_tax = 0;
        $order->total_tax_amount = 0;
        foreach ($order->orderDetails as $item) {
            $order->total_price_excluding_tax += $item->total_price_excluding_tax;
            $order->total_price_including_tax += $item->total_price_including_tax;
            $order->total_tax_amount += $item->total_tax_amount;
        }
        $order->save();
        $this->saveOrderDetails();
        $this->saveCarData();
        $snippet = $response->html_snippet;
        return redirect("orderbekraftelse/" . Session::get('orderInfo.token'));
    }
}
