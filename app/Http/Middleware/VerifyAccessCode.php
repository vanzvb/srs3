<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyAccessCode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $accessCode = $request->header('access-code'); // Or use $request->input('access_code') for query parameters or body

        // Replace 'your-secret-code' with the desired code
        if ($accessCode !== '4901') {
            return response()->json(['error' => 'Unauthorized: Invalid access code'], 403);
        }

        return $next($request);
    }
}
