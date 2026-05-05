<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'scope' => \App\Http\Middleware\ScopeDataByRole::class,
            'tenant' => \App\Http\Middleware\IdentifyTenant::class,
            'platform' => \App\Http\Middleware\EnsurePlatformAccess::class,
        ]);
        // Apply tenant identification first, then scope middleware
        $middleware->web(prepend: [
            \App\Http\Middleware\IdentifyTenant::class,
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\ScopeDataByRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
