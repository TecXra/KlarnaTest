<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    // protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    protected function postRegister(Request $request)
    {
        // dd($request->all());
        // $this->validate($request, [
        //     'email' => 'required|email|max:255|unique:users',
        //     'password' => 'required|min:6|confirmed',
        // ]);
        
        if(isset($request->company_name)) {
            $validation = Validator($request->all(), [
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'company_name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|min:6|confirmed',
            ]);
        } else {
            $validation = Validator($request->all(), [
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|min:6|confirmed',
            ]);
        }
        
        if ( $validation->fails() ) {
            $errors = $validation->errors();
            return view('auth.register', compact('errors'));
        }

        $user = new User();
        $user->user_type_id = 1;
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->company_name = isset($request->company_name) ? $request->company_name : null;
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->is_company = $request->isCompany;
        $user->is_active = 1;
        $user->save();

        flash()->success('', 'Användare skapad.');
        return redirect('login'); // med en bekräftelse om lyckad registrering
    }

    public function getRegistered()
    {
        return view('auth.register');
    }
}
