<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SessionCleanupTest extends TestCase
{
  use RefreshDatabase;

  public function test_session_cleanup_scheduler_marks_expired_sessions(): void
  {
    // Create an active user and an inactive user
    $activeUser = User::factory()->create([
      'email' => 'active@example.com',
    ]);

    $inactiveUser = User::factory()->create([
      'email' => 'inactive@example.com',
    ]);

    // Create login histories for both (both currently logged in, logout_at is null)
    $activeHistory = LoginHistory::create([
      'email' => $activeUser->email,
      'ip_address' => '127.0.0.1',
      'user_agent' => 'PHPUnit',
      'login_at' => now(),
      'activity' => 'Logged in',
    ]);

    $inactiveHistory = LoginHistory::create([
      'email' => $inactiveUser->email,
      'ip_address' => '127.0.0.1',
      'user_agent' => 'PHPUnit',
      'login_at' => now()->subHours(5),
      'activity' => 'Logged in',
    ]);

    // Insert session database entries
    $lifetime = config('session.lifetime') * 60;

    // Active session
    DB::table('sessions')->insert([
      'id' => 'session_active',
      'user_id' => $activeUser->id,
      'ip_address' => '127.0.0.1',
      'user_agent' => 'PHPUnit',
      'payload' => 'payload',
      'last_activity' => time() - 10, // Active just 10 seconds ago
    ]);

    // Expired session (e.g. last activity was longer ago than lifetime)
    DB::table('sessions')->insert([
      'id' => 'session_expired',
      'user_id' => $inactiveUser->id,
      'ip_address' => '127.0.0.1',
      'user_agent' => 'PHPUnit',
      'payload' => 'payload',
      'last_activity' => time() - ($lifetime + 60), // Expired 60 seconds past lifetime
    ]);

    // Resolve scheduler and execute the closure callback
    $schedule = app()->make(Schedule::class);
    $events = collect($schedule->events());

    $cleanupEvent = $events->first(function ($event) {
      return $event->description === null;
    });

    $this->assertNotNull($cleanupEvent, 'Scheduled session cleanup task is not registered.');

    // Safely extract the protected callback property using Reflection
    $reflection = new \ReflectionProperty($cleanupEvent, 'callback');
    $reflection->setAccessible(true);
    $callback = $reflection->getValue($cleanupEvent);

    // Execute the callback
    call_user_func($callback);

    // Assert that inactive user's login history is updated to "Session expired"
    $inactiveHistory->refresh();
    $this->assertNotNull($inactiveHistory->logout_at);
    $this->assertEquals('Session expired', $inactiveHistory->activity);

    // Assert that active user's login history remains intact
    $activeHistory->refresh();
    $this->assertNull($activeHistory->logout_at);
    $this->assertEquals('Logged in', $activeHistory->activity);
  }
}
