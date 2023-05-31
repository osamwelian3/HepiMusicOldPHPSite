<?php

namespace App\Http\Controllers\Auth; 
  
use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use DB; 
use Carbon\Carbon; 
use App\User; 
use Mail; 
use Hash;
use Illuminate\Support\Str;
use Redirect;
  
class ForgotPasswordController extends Controller
{
      /**
       * Write code on Method
       *
       * @return response()
       */
    public function showForgotPasswordForm()
    {
        return view('auth.passwords.email');
    }
  
      /**
       * Write code on Method
       *
       * @return response()
       */
    public function submitForgotPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email, 
            'token' => $token, 
            'created_at' => Carbon::now()
        ]);

        $user = DB::table('users')->where([
            'email' => $request->email
        ])->first();
        $name = $user->first_name.' '.$user->last_name;

        Mail::send('emails.forgotPassword', ['token' => $token, 'name' => $name], function($message) use($request){
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return Redirect::to("login")->withSuccess('We have e-mailed your password reset link!');
    }
      /**
       * Write code on Method
       *
       * @return response()
       */
    public function showResetPasswordForm($token) { 
        return view('auth.passwords.reset', ['token' => $token]);
    }
  
      /**
       * Write code on Method
       *
       * @return response()
       */
    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:3|confirmed',
            'password_confirmation' => 'required'
        ]);
  
        $updatePassword = DB::table('password_resets')->where([
            'email' => $request->email, 
            'token' => $request->token
        ])->first();
  
        if(!$updatePassword){
            return Redirect::to("login")->withError('Invalid token!');
        }
  
        $user = User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);
 
        DB::table('password_resets')->where(['email'=> $request->email])->delete();
  
        return Redirect::to("login")->withSuccess('Your password has been changed!');
    }
}
