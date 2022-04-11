<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserLoginDetails;
use Carbon\Carbon;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            }
        }
        if($request->email){
            $time = Carbon::now();
                $time = $time->toDateTimeString();
                $ip_address = $request->ip();
                $email = $request->email;
                $type = "User Login";
                $user_login_details=array(
                                    'email'=>$email,
                                    'ip_address'=>$ip_address,
                                    'timezone' =>$time,
                                    'type' =>$type
                                );
                UserLoginDetails::create($user_login_details);
        }

        return $next($request);
    }
}
