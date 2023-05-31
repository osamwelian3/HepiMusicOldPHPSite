<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Redirect;

class IsVerifyEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::user()->email_verified_at) {
            auth()->logout();
            return Redirect::to("login")->withSuccess('You need to confirm your account. We have sent you an activation mail, please check your email.');
        }
        return $next($request);
    }
}
