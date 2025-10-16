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
        //
    })
    // 👇 ADD THIS BLOCK
    ->withProviders([
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,   // 👈 this one makes your Gates work!
        // (Optional, if you have them)
        // App\Providers\EventServiceProvider::class,
        // App\Providers\RouteServiceProvider::class,
    ])
    // 👆 END OF ADDED BLOCK
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
