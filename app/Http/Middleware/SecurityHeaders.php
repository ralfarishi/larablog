<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
	private $unwantedHeaders = ['X-Powered-By', 'server', 'Server'];

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function handle(Request $request, Closure $next)
	{
		$response = $next($request);

		if (!app()->environment('testing')) {
			$response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
			$response->headers->set('X-XSS-Protection', '1; mode=block');
			$response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
			$response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' platform.twitter.com plausible.io utteranc.es *.cloudflare.com 'unsafe-inline' 'unsafe-eval' plausible.io/js/plausible.js utteranc.es/client.js; style-src 'self' *.cloudflare.com 'unsafe-inline'; img-src 'self' * data:; font-src 'self' data: ; connect-src 'self' plausible.io/api/event; media-src 'self'; frame-src 'self' platform.twitter.com plausible.io utteranc.es github.com *.youtube.com *.vimeo.com; object-src 'none'; base-uri 'self';");
			$response->headers->set('Expect-CT', 'enforce, max-age=30');
			$response->headers->set('Permissions-Policy', 'autoplay=(self), camera=(), encrypted-media=(self), fullscreen=(), geolocation=(self), gyroscope=(self), magnetometer=(), microphone=(), midi=(), payment=(), sync-xhr=(self), usb=()');
			$response->headers->set('Access-Control-Allow-Origin', '*');
			$response->headers->set('Access-Control-Allow-Methods', 'GET,POST,PUT,PATCH,DELETE,OPTIONS');
			$response->headers->set('Access-Control-Allow-Headers', 'Content-Type,Authorization,X-Requested-With,X-CSRF-Token');

			$this->removeUnwantedHeaders($this->unwantedHeaders);
		}

		return $response;
	}

	/**
	 * @param $headers
	 */
	private function removeUnwantedHeaders($headers)
	{
		foreach ($headers as $header) {
			header_remove($header);
		}
	}
}
