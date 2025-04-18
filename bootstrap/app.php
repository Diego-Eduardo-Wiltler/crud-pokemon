<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $exception) {
            Log::error($exception->getMessage(), [
                'exception' => $exception,
            ]);

            if($exception instanceof NotFoundHttpException){
                return response()->json([
                    'message' => 'Something is not right.',
                    'errors' => $exception->getMessage(),
                    'errorResponse' => 30,
                ], 500);
            }

            if($exception instanceof Exception){
                return response()->json([
                    'message' => 'Something is not right.',
                    'error' => null,
                    'errorResponse' => 30,
                ],500);
            }

            return response()->json([
                'message' => 'Something went wrong. Please try again later.',
                'erros' => $exception->getMessage()
            ], 500);
        });
    })->create();
