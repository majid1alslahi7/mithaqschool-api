<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's route middleware.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        // Laravel default middleware
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        // ========== Spatie Permission Middleware ==========
        'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,

        // ========== Custom Middleware ==========
        'active.user' => \App\Http\Middleware\EnsureUserIsActive::class,
        'set.api.locale' => \App\Http\Middleware\SetApiLocale::class,
        'cors' => \App\Http\Middleware\Cors::class,
        'academic.year' => \App\Http\Middleware\SetAcademicYear::class,
        'prevent.back.history' => \App\Http\Middleware\PreventBackHistory::class,
        'redirect.by.role' => \App\Http\Middleware\RedirectBasedOnRole::class,
        'check.student.ownership' => \App\Http\Middleware\CheckStudentOwnership::class,
        'check.teacher.ownership' => \App\Http\Middleware\CheckTeacherOwnership::class,
        'check.guardian.ownership' => \App\Http\Middleware\CheckGuardianOwnership::class,
        'check.course.teacher' => \App\Http\Middleware\CheckCourseTeacher::class,
        'check.exam.ownership' => \App\Http\Middleware\CheckExamOwnership::class,
        'check.homework.ownership' => \App\Http\Middleware\CheckHomeworkOwnership::class,
        'check.attendance.ownership' => \App\Http\Middleware\CheckAttendanceOwnership::class,
        'check.invoice.ownership' => \App\Http\Middleware\CheckInvoiceOwnership::class,
        'check.grade.ownership' => \App\Http\Middleware\CheckGradeOwnership::class,
        'check.schedule.ownership' => \App\Http\Middleware\CheckScheduleOwnership::class,
        'check.behavior.ownership' => \App\Http\Middleware\CheckBehaviorEvaluationOwnership::class,
        'check.academic.year.active' => \App\Http\Middleware\CheckAcademicYearActive::class,
        'check.permission' => \App\Http\Middleware\CheckPermission::class,
        'log.activity' => \App\Http\Middleware\LogUserActivity::class,
        'maintenance.mode' => \App\Http\Middleware\CheckMaintenanceMode::class,
        'throttle.custom' => \App\Http\Middleware\ThrottleRequests::class,
    ];

    /**
     * The application's middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\SetAcademicYear::class,
            \App\Http\Middleware\PreventBackHistory::class,
            \App\Http\Middleware\LogUserActivity::class,
        ],

        'api' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\SetApiLocale::class,
            \App\Http\Middleware\Cors::class,
        ],
    ];
}
