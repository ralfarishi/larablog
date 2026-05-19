<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityTest extends TestCase
{
  use RefreshDatabase;

  /**
   * OWASP A01: Broken Access Control
   * Guests must be redirected to login when trying to access restricted dashboard routes.
   */
  public function test_guests_cannot_access_any_dashboard_routes(): void
  {
    $restrictedRoutes = [
      '/dashboard',
      '/dashboard/article',
      '/dashboard/comment',
      '/dashboard/user',
      '/dashboard/category',
      '/dashboard/login-history',
      '/dashboard/analytics',
    ];

    foreach ($restrictedRoutes as $route) {
      $response = $this->get($route);
      $response->assertRedirect('/login');
    }
  }

  /**
   * OWASP A01: Broken Access Control
   * Authenticated readers must be blocked (403 Forbidden) from all dashboard routes.
   */
  public function test_readers_cannot_access_any_dashboard_routes(): void
  {
    $reader = User::factory()->create([
      'role' => 'reader',
    ]);

    $restrictedRoutes = [
      '/dashboard',
      '/dashboard/article',
      '/dashboard/comment',
      '/dashboard/user',
      '/dashboard/category',
      '/dashboard/login-history',
      '/dashboard/analytics',
    ];

    foreach ($restrictedRoutes as $route) {
      $response = $this->actingAs($reader)->get($route);
      $response->assertStatus(403);
    }
  }

  /**
   * OWASP A01: Broken Access Control
   * Authenticated writers can access general dashboard, articles, and comments,
   * but are forbidden (403) from admin-only routes (categories, login-history, analytics).
   */
  public function test_writers_can_access_general_dashboard_but_not_admin_only_routes(): void
  {
    $writer = User::factory()->create([
      'role' => 'writer',
    ]);

    // Allowed routes
    $allowedRoutes = ['/dashboard', '/dashboard/article', '/dashboard/comment', '/dashboard/user'];

    foreach ($allowedRoutes as $route) {
      $response = $this->actingAs($writer)->get($route);
      $response->assertStatus(200);
    }

    // Admin-only routes
    $adminOnlyRoutes = ['/dashboard/category', '/dashboard/login-history', '/dashboard/analytics'];

    foreach ($adminOnlyRoutes as $route) {
      $response = $this->actingAs($writer)->get($route);
      $response->assertStatus(403);
    }
  }

  /**
   * OWASP A01: Broken Access Control & Mass Assignment
   * Verify that public registration ignores the 'role' field and always default-assigns 'reader'.
   */
  public function test_registration_ignores_role_mass_assignment(): void
  {
    $response = $this->post('/register', [
      'name' => 'Evil Hacker',
      'email' => 'hacker@example.com',
      'password' => 'HackerPassword123',
      'password_confirmation' => 'HackerPassword123',
      'role' => 'admin', // Attack vector: trying to escalate privilege via mass assignment
    ]);

    // Registration redirects to /login as per project architecture
    $response->assertRedirect('/login');

    // Retrieve user and assert they were registered with 'reader' role instead of 'admin'
    $user = User::where('email', 'hacker@example.com')->firstOrFail();
    $this->assertEquals('reader', $user->role);
  }

  /**
   * OWASP A03: Injection
   * Verify that SQL Injection payloads passed to queries do not trigger database syntax errors or leak data.
   */
  public function test_sql_injection_defense_on_slug_lookup(): void
  {
    // Attack vector payload: ' OR 1=1 --
    $sqliPayload = "' OR '1'='1";

    // Attempt to view a post with a SQLi payload slug
    $response = $this->get('/article/' . urlencode($sqliPayload));

    // Should return a standard 404 Not Found rather than a 500 SQL syntax crash or leaking posts
    $response->assertStatus(404);
  }
}
