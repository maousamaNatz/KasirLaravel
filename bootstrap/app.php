<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// File bootstrap utama untuk konfigurasi aplikasi Laravel
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(web: __DIR__.'/../routes/web.php',commands: __DIR__.'/../routes/console.php',health: '/up',)
    ->withMiddleware(function (Middleware $middleware) {
        // Middleware yang dijalankan untuk setiap request web
        $middleware->web([
            \Illuminate\Session\Middleware\StartSession::class, // Memulai session
            \Illuminate\View\Middleware\ShareErrorsFromSession::class, // Share error dari session ke view
        ]);

        // Mendaftarkan alias middleware untuk kemudahan penggunaan
        $middleware->alias([
           'checkroles' => \App\Http\Middleware\CheckRoles::class,
        ]);
    })->withExceptions(function (Exceptions $exceptions) {})->create();
