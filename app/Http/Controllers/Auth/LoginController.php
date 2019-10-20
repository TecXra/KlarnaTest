<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    protected function postLogin(Request $request)
    {
        // $this->validate($request, [
     //        'email' => 'required|email',
     //        'password' => 'required|min:6',
        // ]);
        $validation = Validator($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        
        if ( $validation->fails() ) {
            $errors = $validation->errors();
            return view('auth.login', compact('errors'));
        } 

        $userCheck = User::where('email', $request->input('email'))
                    ->where('is_active', 1)
                    ->first();

        if(isset($userCheck) && $userCheck->user_type_id !== 5) {
            if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password'), 'is_active' => 1])) {
                // if(Auth::user()->isAdmin()) {
                //     return redirect('admin/panel');
                // }

                return redirect('/');
            }
        }
        // $this->sendFailedLoginResponse($request);
        flash()->error('', 'Fel inloggningsuppgifter!');
        return redirect('login');
    }


    public function getLogin()
    {
        return view('auth.login');
    }

    protected function logout()
    {
        $redirectPath = 'login';
        if(/*Auth::user()->isAdmin()*/ Auth::user()->user_type_id == 5)
             $redirectPath = 'admin/77889';

        Auth::logout();

        return redirect($redirectPath);
    }

    public function postAdminLogin(Request $request)
    {
        $validation = Validator($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        
        if ( $validation->fails() ) {
            $errors = $validation->errors();
            return view('admin.login', compact('errors'));
        } 

        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password'), 'user_type_id' => 5, 'is_active' => 1])) {
            return redirect('admin/ordrar');
        }
        // $this->sendFailedLoginResponse($request);
        // Session::flash('message', 'This is a message!'); 
        flash()->error('', 'Fel inloggningsuppgifter!');

        return redirect('admin/77889');
    }

    public function getAdminLogin()
    {
        return view('admin.login');
    }
}
