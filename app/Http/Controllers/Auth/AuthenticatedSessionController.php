<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\LoginHistory;
use App\Providers\RouteServiceProvider;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

use Stevebauman\Location\Facades\Location;

class AuthenticatedSessionController extends Controller
{
	/**
	 * Display the login view.
	 */
	public function create(): View
	{
		return view('auth.login');
	}

	/**
	 * Handle an incoming authentication request.
	 */
	public function store(LoginRequest $request): RedirectResponse
	{
		// $request->authenticate();
		$credentials = $request->validated();

		$isLoginSuccess = Auth::attempt($credentials);

		if (!$isLoginSuccess) {
			$this->storeLoginHistory($request, false, 'Log in failed');

			return back()->with('tdanger', "The credentials does not match in our record!");
		}

		$request->session()->regenerate();

		$this->storeLoginHistory($request, true, 'Logged in');

		return redirect()->intended(RouteServiceProvider::HOME);
	}

	/**
	 * Destroy an authenticated session.
	 */
	public function destroy(Request $request): RedirectResponse
	{
		$userEmail = Auth::user()->email;

		$this->storeLoginHistory($request, true, 'Logged out', $userEmail);

		Auth::guard('web')->logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();

		return redirect('/');
	}

	protected function storeLoginHistory(Request $request, $status, $activity, $userEmail = null)
	{
		$user = Auth::user();

		$email = $userEmail ? optional($user)->email : $request->email;

		$userAgent = $request->header('User-Agent');

		$userIp = app()->environment('local') ? '8.8.8.8' : $request->ip();

		$locationData = Location::get($userIp);

		// try {
		// 	$response = Http::get('https://api.ipify.org?format=json');

		// 	$data = $response->json();

		// 	$publicIp = $data['ip'];

		// 	$locationData = Location::get($publicIp);
		// } catch (\Exception $e) {
		// 	// Tangani kesalahan jika terjadi
		// 	return 'Unable to retrieve IP.';
		// }

		LoginHistory::insert([
			'email' => $email,
			'activity' => $activity,
			'status' => $status,
			'ip_address' => $locationData->ip,
			'user_agent' => $userAgent,
			'city' => $locationData->cityName . ', ' . $locationData->regionName ?? null,
			'latitude' => $locationData->latitude ?? null,
			'longitude' => $locationData->longitude ?? null,
			'created_at' => now(),
		]);
	}
}
