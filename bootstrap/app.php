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
    ->withMiddleware(function (Middleware $middleware) {
        
        // Exclude CSRF untuk Callback Midtrans
        $middleware->validateCsrfTokens(except: [
            'midtrans/callback' 
        ]);

        // DAFTAR MIDDLEWARE KITA DISINI
        $middleware->alias([
            // Middleware Role (yang sudah ada sebelumnya)
            'role' => \App\Http\Middleware\RoleMiddleware::class,

            // TAMBAHAN PENTING:
            // Ganti logika 'guest' bawaan dengan file buatan kita tadi
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);
        
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();