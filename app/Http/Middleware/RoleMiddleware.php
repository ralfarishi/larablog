<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
  public function handle(Request $request, Closure $next, string ...$roles): Response
  {
    $user = $request->user();

    if (!$user || !in_array($user->role, $roles, strict: true)) {
      abort(403, 'Unauthorized.');
    }

    return $next($request);
  }
}
