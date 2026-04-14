<?php

declare(strict_types=1);

use App\Exceptions\BusinessException;
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
    ->withMiddleware(static function (Middleware $middleware): void {
    })
    ->withExceptions(static function (Exceptions $exceptions): void {
        // display exception error message if exception  instance of BusinessException
        $exceptions->render(static function (Throwable $e) {
            if ($e instanceof BusinessException) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], $e->getCode());
            }
        });
    })->create();
