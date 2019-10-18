<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Order;
use App\User;
use Auth;
use App\Http\Utilities\Country;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
{
    public function myAccount()
    {
    	return view('user.my_account');
    }

    public function orderList()
    {
        // Auth::loginUsingId(2);
        $orders = Order::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();

        // dd($orders);
    	return view('user.order_list', compact('orders'));
    }

    public function orderStatus($token)
    {
        $order = Order::where('token', $token)->first();
    	return view('user.order_status', compact('order'));
    }

    public function myAddress()
    {
        $countries = Country::all();
        return view('user.my_address', compact('countries'));
    }

    public function updateAddress(Request $request)
    {
        // dd($request->all());

        $updateUser = User::find(Auth::user()->id);
        $updateUser->billing_full_name = $request->InputBillingFullName;
        $updateUser->billing_street_address = $request->InputBillingAddress;
        $updateUser->billing_postal_code = empty($request->InputBillingPostalCode) ? null :  $request->InputBillingPostalCode;
        $updateUser->billing_city = $request->InputBillingCity;
        $updateUser->billing_country = $request->InputBillingCountry;
        $updateUser->shipping_full_name = $request->InputShippingFullName;
        $updateUser->shipping_street_address = $request->InputShippingAddress;
        $updateUser->shipping_postal_code = empty($request->InputShippingPostalCode) ? null :  $request->InputShippingPostalCode;
        $updateUser->shipping_city = $request->InputShippingCity;
        $updateUser->shipping_country = $request->InputShippingCountry;
        $updateUser->save();
        
        return redirect('adresser');


        $updateUser = User::find(Auth::user()->id);

        // if(isset($request->newShippingAddress)) {
        //     $updateUser->billing_full_name = $request->InputBillingFullName;
        //     $updateUser->billing_street_address = $request->InputBillingAddress;
        //     $updateUser->billing_postal_code = empty($request->InputBillingPostalCode) ? null :  $request->InputBillingPostalCode;
        //     $updateUser->billing_city = $request->InputBillingCity;
        //     $updateUser->billing_country = $request->InputBillingCountry;
        //     $updateUser->shipping_full_name = $request->InputShippingFullName;
        //     $updateUser->shipping_street_address = $request->InputShippingAddress;
        //     $updateUser->shipping_postal_code = empty($request->InputShippingPostalCode) ? null :  $request->InputShippingPostalCode;
        //     $updateUser->shipping_city = $request->InputShippingCity;
        //     $updateUser->shipping_country = $request->InputShippingCountry;
        //     $updateUser->save();
        //     return redirect('adresser');
        // }

        // $updateUser->billing_full_name = $request->InputBillingFullName;
        // $updateUser->billing_street_address = $request->InputBillingAddress;
        // $updateUser->billing_postal_code = empty($request->InputBillingPostalCode) ? null :  $request->InputBillingPostalCode;
        // $updateUser->billing_city = $request->InputBillingCity;
        // $updateUser->billing_country = $request->InputBillingCountry;
        // $updateUser->shipping_full_name = $request->InputBillingFullName;
        // $updateUser->shipping_street_address = $request->InputBillingAddress;
        // $updateUser->shipping_postal_code = empty($request->InputBillingPostalCode) ? null :  $request->InputBillingPostalCode;
        // $updateUser->shipping_city = $request->InputBillingCity;
        // $updateUser->shipping_country = $request->InputBillingCountry;
        // $updateUser->save();
        
        // return redirect('adresser');
    }

    public function updateAccountSettings(Request $request)
    {
        $updateUser = User::find(Auth::user()->id);
        $updateUser->first_name = $request->InputFirstName;
        $updateUser->last_name = $request->InputLastName;
        $updateUser->email = $request->InputEmail;
        $updateUser->billing_phone = $request->InputPhoneNumber;
        $updateUser->save();

        // dd($request->all());
        if(empty($request->InputPasswordCurrent) && empty($request->password) && empty($request->password_confirmation)) {
            flash()->success('', 'Inställningarna är uppdaterade.');
            return redirect('konto_installningar');
        }

        if(!Auth::attempt(['email' => $request->InputEmail,'password' => $request->InputPasswordCurrent])) {
            flash()->error('', 'Fel lösenord');
            return redirect('konto_installningar');
        }

        $validation = Validator($request->all(), [
            'InputPasswordNew' => 'required|min:6|confirmed',
        ]);

        if ( $validation->fails() ) {
            $errors = $validation->errors();
            // return view('user.register', compact('errors'));
            return redirect('konto_installningar')->withErrors($errors);
        }

        $updateUser->password = bcrypt($request->InputPasswordNew);
        $updateUser->save();

        flash()->success('', 'Inställningarna är uppdaterade.');
        return redirect('konto_installningar');
    }

    public function accountSettings()
    {
        return view('user.account_settings');
    }

}
