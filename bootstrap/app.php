<?php

use App\Http\Middleware\owner;
use App\Http\Middleware\supplierOwner;
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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'owner' => \App\Http\Middleware\owner::class,
        ]);

        $middleware->alias([
            'ulasanOwner' => \App\Http\Middleware\ulasanOwner::class,
        ]);

        $middleware->alias([
            'supplierowner' => \App\Http\Middleware\supplierOwner::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
