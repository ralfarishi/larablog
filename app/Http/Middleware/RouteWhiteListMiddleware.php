<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RouteWhiteListMiddleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
		$whitelist = env('IP_WHITELIST');

		$ipAddresses = explode(';', $whitelist);

		if (!in_array($request->ip(), $ipAddresses)) {
			abort(403);
		}
		return $next($request);
	}
}
