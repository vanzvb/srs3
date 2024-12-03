<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserIsOnline
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->check()) {
            $expiresAt = now()->addMinutes(5);
            Cache::put('user-is-online-' . auth()->id() , true, $expiresAt);
        }
  
        $exceptUsers = [
            'itqa@atomitsoln.com',
            'srsadmin@atomitsoln.com',
            'lito.tampis@atomitsoln.com',
            'mike.sujeco@atomitsoln.com',
        ];

        // check if user is not in the exceptUsers and is_logged_in is 0
        if(!in_array(Auth::user()->email, $exceptUsers) && Auth::user()->is_logged_in == 0){
            Cache::forget('user-is-online-' . auth()->id());

            Auth::logout();
 
            $request->session()->invalidate();
        
            $request->session()->regenerateToken();
        
            return redirect('/');
        }
        
        return $next($request);
    }
}
