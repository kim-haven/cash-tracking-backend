<?php

use App\Http\Middleware\EnsureUserHasRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => EnsureUserHasRole::class,
        ]);

        // API-only: no web "login" route. Without this, unauthenticated requests that
        // do not send Accept: application/json hit route('login') and throw 500.
        $middleware->redirectGuestsTo(fn () => null);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Always return JSON errors for /api/* so AuthenticationException becomes 401, not a redirect to route('login').
        $exceptions->shouldRenderJsonWhen(function ($request, Throwable $e): bool {
            return $request->is('api/*') || $request->expectsJson();
        });
    })->create();
