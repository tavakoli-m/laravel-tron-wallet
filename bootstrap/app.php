<?php

use App\Api\ApiResponse\Facades\ApiResponse;
use App\Http\Middleware\RequireJsonHeader;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Auth\Access\AuthorizationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            RequireJsonHeader::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // 422 status code
        $exceptions->render(function (ValidationException $exception, Request $request) {
            return ApiResponse::withStatus(422)
                ->withErrors($exception->errors())
                ->withMessage($exception->getMessage())
                ->send();
        });

        // 401 status code (Sanctum)
        $exceptions->render(function (AuthenticationException $exception, Request $request) {
            return ApiResponse::withStatus(401)
                ->withMessage($exception->getMessage())
                ->send();
        });

        // 403 status code
        $exceptions->render(function (AccessDeniedHttpException $exception, Request $request) {
            if ($exception->getPrevious() instanceof AuthorizationException) {
                return ApiResponse::withStatus(403)
                    ->withMessage("Unauthorized.")
                    ->send();
            }
        });

        // 404 status code
        $exceptions->render(function (NotFoundHttpException $exception, Request $request) {
            return ApiResponse::withStatus(404)
                ->withMessage("Not Found !!")
                ->send();
        });

        // 405 status code
        $exceptions->render(function (MethodNotAllowedHttpException $exception, Request $request) {
            return ApiResponse::withStatus(405)
                ->withMessage("Method Not Allowed !!")
                ->send();
        });

        $exceptions->shouldRenderJsonWhen(function (Request $request) {
            return true;
        });
    })->create();
