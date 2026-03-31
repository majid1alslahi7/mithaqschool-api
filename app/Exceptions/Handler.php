<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Support\ApiErrorResponse;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        //
    }

    /**
     * Customize the response for unauthenticated requests.
     */
    protected function unauthenticated($request, AuthenticationException $exception): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return ApiErrorResponse::make(
                'يجب تسجيل الدخول أولاً للوصول إلى هذا المورد.',
                401,
                [],
                ['challenge' => $this->authenticationChallenge()]
            )->withHeaders([
                'WWW-Authenticate' => $this->authenticationChallenge(),
            ]);
        }

        return parent::unauthenticated($request, $exception);
    }

    /**
     * Return the WWW-Authenticate challenge string that applies to our API.
     */
    private function authenticationChallenge(): string
    {
        return 'Bearer realm="mithaqschool-api"';
    }

}
