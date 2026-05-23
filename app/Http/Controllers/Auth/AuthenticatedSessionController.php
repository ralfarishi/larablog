<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\LoginHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    $credentials = $request->validated();

    $isLoginSuccess = Auth::attempt($credentials);

    if (!$isLoginSuccess) {
      $this->storeLoginHistory($request, false, 'Log in failed');

      return back()->with('tdanger', 'The credentials does not match in our record!');
    }

    $request->session()->regenerate();

    $this->storeLoginHistory($request, true, 'Logged in');

    return redirect()->intended('/');
  }

  /**
   * Destroy an authenticated session.
   */
  public function destroy(Request $request): RedirectResponse
  {
    $userEmail = Auth::user()->email;

    LoginHistory::where('email', $userEmail)
      ->whereNull('logout_at')
      ->latest('id')
      ->first()
      ?->update(['logout_at' => now()]);

    Auth::guard('web')->logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/');
  }

  protected function storeLoginHistory(
    Request $request,
    bool $status,
    string $activity,
    ?string $userEmail = null,
  ): void {
    $user = Auth::user();

    $email = $userEmail ? optional($user)->email : $request->email;

    $userAgent = $request->header('User-Agent');

    $userIp = app()->environment('local') ? '8.8.8.8' : $request->ip();

    $locationData = Location::get($userIp);

    LoginHistory::insert([
      'email' => $email,
      'activity' => $activity,
      'status' => $status,
      'ip_address' => $locationData ? $locationData->ip : $userIp,
      'user_agent' => $userAgent,
      'city' => $locationData ? $locationData->cityName . ', ' . $locationData->regionName : null,
      'latitude' => $locationData ? $locationData->latitude : null,
      'longitude' => $locationData ? $locationData->longitude : null,
      'created_at' => now(),
    ]);
  }
}
