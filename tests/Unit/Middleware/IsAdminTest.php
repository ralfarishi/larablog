<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\IsAdmin;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class IsAdminTest extends TestCase
{
  public function test_it_allows_admin_user(): void
  {
    $middleware = new IsAdmin();
    $user = new User(['role' => 'admin']);
    $request = Request::create('/test', 'GET');
    $request->setUserResolver(fn() => $user);

    $response = $middleware->handle($request, fn() => new Response('allowed'));

    $this->assertEquals('allowed', $response->getContent());
  }

  public function test_it_aborts_for_non_admin_user(): void
  {
    $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
    $this->expectExceptionMessage('Unauthorized.');

    $middleware = new IsAdmin();
    $user = new User(['role' => 'writer']);
    $request = Request::create('/test', 'GET');
    $request->setUserResolver(fn() => $user);

    $middleware->handle($request, fn() => new Response('allowed'));
  }

  public function test_it_aborts_for_unauthenticated_user(): void
  {
    $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
    $this->expectExceptionMessage('Unauthorized.');

    $middleware = new IsAdmin();
    $request = Request::create('/test', 'GET');
    $request->setUserResolver(fn() => null);

    $middleware->handle($request, fn() => new Response('allowed'));
  }
}
