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
        // Custom route-middleware aliases
        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'customer' => \App\Http\Middleware\EnsureCustomer::class,
        ]);

        // Guests hitting the admin area land on the admin login; everyone else
        // (customer account area) is sent to the public customer login.
        $middleware->redirectGuestsTo(function ($request) {
            return $request->is('admin', 'admin/*')
                ? route('admin.login')
                : route('login');
        });

        // Razorpay posts the webhook server-to-server; it carries no CSRF token
        // and is authenticated by its own signature instead.
        $middleware->validateCsrfTokens(except: [
            'razorpay/webhook',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
