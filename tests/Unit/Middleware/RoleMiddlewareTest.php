<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\RoleMiddleware;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
  public function test_it_allows_user_with_correct_role(): void
  {
    $middleware = new RoleMiddleware();
    $user = new User(['role' => 'admin']);
    $request = Request::create('/test', 'GET');
    $request->setUserResolver(fn() => $user);

    $response = $middleware->handle($request, fn() => new Response('allowed'), 'admin', 'writer');

    $this->assertEquals('allowed', $response->getContent());
  }

  public function test_it_aborts_for_unauthenticated_user(): void
  {
    $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
    $this->expectExceptionMessage('Unauthorized.');

    $middleware = new RoleMiddleware();
    $request = Request::create('/test', 'GET');
    $request->setUserResolver(fn() => null);

    $middleware->handle($request, fn() => new Response('allowed'), 'admin');
  }

  public function test_it_aborts_for_user_with_incorrect_role(): void
  {
    $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
    $this->expectExceptionMessage('Unauthorized.');

    $middleware = new RoleMiddleware();
    $user = new User(['role' => 'reader']);
    $request = Request::create('/test', 'GET');
    $request->setUserResolver(fn() => $user);

    $middleware->handle($request, fn() => new Response('allowed'), 'admin', 'writer');
  }
}
