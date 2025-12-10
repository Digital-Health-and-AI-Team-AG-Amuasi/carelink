<?php

declare(strict_types=1);

use App\Http\Middleware\AuthApiKeyMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        apiPrefix: 'api/',
    )
    ->withMiddleware(static function (Middleware $middleware) {
        $middleware->append(
            Illuminate\Http\Middleware\TrustProxies::class,
        );

        $middleware->api(prepend: [
            AuthApiKeyMiddleware::class,
        ]);
    })
    ->withExceptions(static function (Exceptions $exceptions) {
        //
    })->create();
