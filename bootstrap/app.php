<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\SuperAdminGlobalMiddleware;
use App\Http\Middleware\StockManagerMiddleware;
use App\Http\Middleware\CheckTrial; 

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 👇 Enregistre tous tes middlewares ici
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'super_admin_global' => SuperAdminGlobalMiddleware::class,
            'stock.manager' => StockManagerMiddleware::class,
            'check.trial' => CheckTrial::class, 
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();