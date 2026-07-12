<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\BlockRoleAreaAccess;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        channels: __DIR__.'/../routes/channels.php',
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            BlockRoleAreaAccess::class,
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withMiddleware(function (Middleware $middleware): void {
        
        $middleware->alias([
            'customer' => \App\Http\Middleware\EnsureCustomer::class,
            'restaurant' => \App\Http\Middleware\EnsureRestaurant::class,
        ]);
//
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
