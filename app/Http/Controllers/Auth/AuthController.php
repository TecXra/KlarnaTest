<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use Auth;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    // protected $redirectTo = '/konto';
    
    // protected $redirectPath = '/konto';

    // protected $redirectAfterLogout = '/login';

    // protected $loginPath = '/login';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
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
        
        $validation = Validator($request->all(), [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
        
        if ( $validation->fails() ) {
            $errors = $validation->errors();
            return view('auth.register', compact('errors'));
        }

        $user = new User ([
            'user_type_id' => 1,
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' =>bcrypt($request->input('password'))
        ]);
        $user->save();

        flash()->success('', 'Användare skapad.');
        return redirect('login'); // med en bekräftelse om lyckad registrering
    }

    public function getRegistered()
    {
        return view('auth.register');
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

        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            // if(Auth::user()->isAdmin()) {
            //     return redirect('admin/panel');
            // }

            return redirect('konto');
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
        if(Auth::user()->isAdmin())
             $redirectPath = 'admin/login';

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

        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password'), 'user_type_id' => 5])) {
            return redirect('admin/panel');
        }
        // $this->sendFailedLoginResponse($request);
        flash()->error('', 'Fel inloggningsuppgifter!');
        return redirect('admin/login');
    }

    public function getAdminLogin()
    {
        return view('admin.login');
    }
}
