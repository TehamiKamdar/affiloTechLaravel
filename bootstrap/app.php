<?php

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
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'super_admin_n_admin' => \App\Http\Middleware\AdminMiddleware::class,
            'publisher' => \App\Http\Middleware\PublisherMiddleware::class,
            'advertiser' => \App\Http\Middleware\AdvertiserMiddleware::class,
            'publisher.status' => \App\Http\Middleware\PublisherStatus::class,
            'webhook' => \App\Http\Middleware\WebhookMiddleware::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            "/webhooks/incoming"
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
