<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Sentry\Laravel\Integration;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    api: __DIR__ . '/../routes/api.php',
    commands: __DIR__ . '/../routes/console.php',
    channels: __DIR__ . '/../routes/channels.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware) {
    // Global web middleware
    $middleware->web(
      append: [
        //
      ],
    );

    // Middleware aliases (previously $middlewareAliases in Kernel.php)
    $middleware->alias([
      'is.admin' => \App\Http\Middleware\IsAdmin::class,
      'role' => \App\Http\Middleware\RoleMiddleware::class,
    ]);
  })
  ->withExceptions(function (Exceptions $exceptions) {
    Integration::handles($exceptions);
  })
  ->create();
