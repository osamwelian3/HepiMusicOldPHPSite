<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Validator,Redirect,Response;
use Illuminate\Http\Request;
use Session;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Helpers\MailerFactory;

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
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $mailer;

    public function __construct(MailerFactory $mailer)
    {
        $this->middleware('guest');
        $this->mailer = $mailer;
    }
    public function register()
    {

        return view('auth.register');
    }

    public function store(Request $request)
    {
        $rules = array(
            'first_name' => 'required|string|max:100',            
            'last_name' => 'required|string|max:100',
            'email' => 'required|string|email|max:50|unique:users|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'password' => 'required|string|min:3|confirmed',
            'password_confirmation' => 'required',
        );

        $request->validate($rules);
        $token = Str::random(64);

        $user = User::create([
            'role_type' => 2,
            'phone' => $request->phone,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 0,
            'remember_token' => $token,
        ]);

        if($user){
            $this->mailer->sendWelcomeEmail($user);
        }

        return Redirect::to("login")->withSuccess('Registration successfull! Please check mail for verification.');
    }

    public function verifyAccount($token)
    {
        $verifyUser = User::where('remember_token', $token)->first();
  
        if(!is_null($verifyUser) ){
            if(!$verifyUser->email_verified_at) {
                $verifyUser->status = 1;
                $verifyUser->role_type = 2;
                $verifyUser->email_verified_at = Carbon::now();
                if($verifyUser->save()){
                    return Redirect::to("login")->withSuccess('Great! Your e-mail is verified. You can now login.');
                }else{
                    return Redirect::to("login")->withError('Something went wrong.');
                }
            } else {
                return Redirect::to("login")->withSuccess('Your e-mail is already verified. You can now login.');
            }
        }
        return Redirect::to("login")->withError('Sorry your email cannot be identified.');
    }
}
