<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
  public function handle(Request $request, Closure $next): Response
  {
    if ($request->user()?->role === 'admin') {
      return $next($request);
    }

    abort(403, 'Unauthorized.');
  }
}
