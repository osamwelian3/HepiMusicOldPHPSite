<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Validator,Redirect,Response;
use Illuminate\Http\Request;
use Session;
use App\User;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['logout','logoutEmail']);
    }

    protected function validateLogin(Request $request)
    {        
        $messages = [];
        $this->validate($request, [
            'email' => "required|exists:users,email",
            'password' => 'required',
        ], $messages);
    }
    protected function credentials(Request $request)
    {
        return [
            'email' => $request->get('email'),
            'password' => $request->get('password'),            
        ];
    }
    /*public function authenticate(Request $request)
    {
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $loginUser = Auth::user();

            if ($loginUser->role_type == 1) {
                return redirect()->intended(route('/dashboard'));
            } else {
                return redirect()->intended(route('home'));
            }
        }
        return Redirect::to("login")->withSuccess('Oppes! You have entered invalid credentials');
    }*/
    protected function authenticated(Request $request, $user)
    {
        if ($user->role_type == 1) {
            return redirect('/dashboard');
        } else {            
            if ($user->email_verified_at != '') {
                return redirect('home');
            } else {
                // Session::flush();
                // \Auth::logout();
                //return redirect(route('logout'))->withError('You need to confirm your account. We have sent you an mail, please check your email.');
                return redirect('logoutEmail');
            }
        }
        // return redirect('/');
    }
    
    public function logout() {
        Session::flush();
        \Auth::logout();
        // return Redirect('login');
        return redirect('home');
    }

    public function logoutEmail() {        
        Session::flush();
        \Auth::logout();
        return Redirect::to("login")->withError('You need to confirm your account. We have sent you an mail, please check your email.');
    }
}
