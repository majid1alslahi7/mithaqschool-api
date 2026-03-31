<?php

use App\Support\ApiErrorResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(function (Request $request): ?string {
            if ($request->is('api/*') || $request->expectsJson()) {
                return null;
            }

            return Route::has('login') ? route('login') : null;
        });

        $middleware->api(prepend: [
            \App\Http\Middleware\SetApiLocale::class,
        ], append: [
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(function (Request $request) {
            return $request->is('api/*') || $request->expectsJson();
        });

        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                $errors = $e->errors();

                return ApiErrorResponse::make(
                    ApiErrorResponse::firstValidationMessage($errors),
                    422,
                    $errors
                );
            }
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return ApiErrorResponse::make(
                    'يجب تسجيل الدخول أولاً للوصول إلى هذا المورد.',
                    401
                );
            }
        });

        $exceptions->render(function (AuthorizationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return ApiErrorResponse::make(
                    'ليس لديك صلاحية لتنفيذ هذا الإجراء.',
                    403
                );
            }
        });

        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return ApiErrorResponse::make(
                    ApiErrorResponse::modelNotFoundMessage($e->getModel()),
                    404
                );
            }
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                $previous = $e->getPrevious();

                if ($previous instanceof ModelNotFoundException) {
                    return ApiErrorResponse::make(
                        ApiErrorResponse::modelNotFoundMessage($previous->getModel()),
                        404
                    );
                }

                return ApiErrorResponse::make(
                    'المسار أو المورد المطلوب غير موجود.',
                    404
                );
            }
        });

        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return ApiErrorResponse::make(
                    'طريقة الطلب غير مدعومة لهذا المسار.',
                    405
                );
            }
        });

        $exceptions->render(function (TooManyRequestsHttpException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                $retryAfter = $e->getHeaders()['Retry-After'] ?? null;

                return ApiErrorResponse::make(
                    'تم إرسال عدد كبير من الطلبات. يرجى المحاولة بعد قليل.',
                    429,
                    [],
                    ['retry_after' => $retryAfter]
                );
            }
        });

        $exceptions->render(function (QueryException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return ApiErrorResponse::make(
                    ApiErrorResponse::queryExceptionMessage($e),
                    ApiErrorResponse::queryExceptionStatus($e)
                );
            }
        });

        $exceptions->render(function (HttpException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return ApiErrorResponse::make(
                    ApiErrorResponse::messageForStatus($e->getStatusCode(), $e->getMessage()),
                    $e->getStatusCode()
                );
            }
        });

        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return ApiErrorResponse::make(
                    'حدث خطأ غير متوقع في الخادم. يرجى المحاولة لاحقاً.',
                    500
                );
            }
        });
    })
    ->create();
