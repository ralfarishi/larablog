<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
  public function register(): void {}

  public function boot(): void
  {
    // ── Event Listeners (consolidated from EventServiceProvider) ──────
    Event::listen(Registered::class, SendEmailVerificationNotification::class);

    // ── Rate Limiting (consolidated from RouteServiceProvider) ────────
    RateLimiter::for('api', function (Request $request) {
      return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
    });
  }
}
