<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RouteWhiteListMiddleware
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
			$whitelist = env('IP_WHITELIST');

			$ipAddresses = explode(';', $whitelist);

			if (!in_array($request->ip(), $ipAddresses)) {
				Log::error('IP address is not whitelisted', ['ip address', $request->ip()]);
			return redirect('/');
		}
			return $next($request);
    }
}