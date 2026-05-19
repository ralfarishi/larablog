<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;
use App\Models\LoginHistory;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
  $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
  // Calculate the active timestamp threshold based on session lifetime
  $lifetime = config('session.lifetime') * 60;
  $activeTimestamp = time() - $lifetime;

  // Get user IDs that have an active session
  $activeUserIds = DB::table('sessions')
    ->where('last_activity', '>=', $activeTimestamp)
    ->pluck('user_id')
    ->filter()
    ->unique();

  // Retrieve the emails for these active users
  $activeEmails = DB::table('users')->whereIn('id', $activeUserIds)->pluck('email')->toArray();

  // Any login history without a logout_at whose email is NOT active
  // means their session has expired or they closed the tab long ago
  LoginHistory::whereNull('logout_at')
    ->whereNotIn('email', $activeEmails)
    ->update([
      'logout_at' => now(),
      'activity' => 'Session expired',
    ]);
})->hourly();
