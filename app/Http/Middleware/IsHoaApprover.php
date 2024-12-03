<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsHoaApprover
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
        if (auth()->user()->isHoaApprover()) {
            return redirect()->route('hoa-approvers3.index');
        }

        return $next($request);
    }
}
