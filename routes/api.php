<?php

use App\Http\Controllers\api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\AcademicYearController;
use App\Http\Controllers\api\AdjustmentController;
use App\Http\Controllers\api\ApiAuthController;
use App\Http\Controllers\api\AttendanceController;
use App\Http\Controllers\api\BackupController;
use App\Http\Controllers\api\BehaviorEvaluationController;
use App\Http\Controllers\api\CacheController;
use App\Http\Controllers\api\ClassFeeController;
use App\Http\Controllers\api\ClassroomController;
use App\Http\Controllers\api\CourseClassroomTeacherController;
use App\Http\Controllers\api\CourseController;
use App\Http\Controllers\api\DailyAttendanceController;
use App\Http\Controllers\api\DashboardController;
use App\Http\Controllers\api\ExamController;
use App\Http\Controllers\api\ExamResultController;
use App\Http\Controllers\api\ExamTypeController;
use App\Http\Controllers\api\ExternalApiController;
use App\Http\Controllers\api\FeeTypeController;
use App\Http\Controllers\api\FinalyGradeController;
use App\Http\Controllers\api\GradeController;
use App\Http\Controllers\api\GradesScaleController;
use App\Http\Controllers\api\GuardianController;
use App\Http\Controllers\api\HomeworkController;
use App\Http\Controllers\api\HomeworkSubmissionController;
use App\Http\Controllers\api\InvoiceItemController;
use App\Http\Controllers\api\MessageController;
use App\Http\Controllers\api\MonthlyGradeController;
use App\Http\Controllers\api\NotificationController;
use App\Http\Controllers\api\PaymentController;
use App\Http\Controllers\api\PermissionController;
use App\Http\Controllers\api\ReportController;
use App\Http\Controllers\api\RoleController;
use App\Http\Controllers\api\RolePermissionController;
use App\Http\Controllers\api\ScheduleController;
use App\Http\Controllers\api\SchoolController;
use App\Http\Controllers\api\SchoolStageController;
use App\Http\Controllers\api\SearchController;
use App\Http\Controllers\api\SemesterController;
use App\Http\Controllers\api\SemesterGradeController;
use App\Http\Controllers\api\SettingsController;
use App\Http\Controllers\api\StudentController;
use App\Http\Controllers\api\StudentInvoiceController;
use App\Http\Controllers\api\TeacherController;
use App\Http\Controllers\api\TeacherCourseController;
use App\Http\Controllers\api\UserController;

// الصفحة الرئيسية
Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the MithaqSchool API v1.',
        'documentation_url' => url('/api/documentation'),
    ]);
});

// API Version 1
Route::prefix('v1')->group(function () {

    // ✅ ROUTE TEST DATABASE
    Route::get('/debug-db', function () {
        try {
            DB::connection()->getPdo();
            return response()->json([
                'status' => 'success',
                'message' => '✅ Database connected successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    });

    // ========== PUBLIC ROUTES ==========
    Route::post('/login', [api\ApiAuthController::class, 'login'])->name('api.login');
    Route::post('/signup', [api\ApiAuthController::class, 'signUp'])->name('api.signup');
    Route::post('/forgot-password', [api\ApiAuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [api\ApiAuthController::class, 'resetPassword']);

    Route::get('/verify-email/{id}/{hash}', [api\ApiAuthController::class, 'verifyEmail'])
        ->middleware(['signed', 'throttle:6,1']);

    // ========== AUTH ==========
    Route::middleware(['auth:sanctum'])->group(function () {

        Route::post('/logout', [api\ApiAuthController::class, 'logout']);
        Route::get('/user', [api\UserController::class, 'profile']);

        // باقي المسارات كما هي (لم يتم حذف أي شيء)
        Route::apiResource('users', api\UserController::class);
        Route::apiResource('students', api\StudentController::class);
        Route::apiResource('teachers', api\TeacherController::class);
        Route::apiResource('guardians', api\GuardianController::class);

        Route::apiResource('academic-years', api\AcademicYearController::class);
        Route::apiResource('semesters', api\SemesterController::class);
        Route::apiResource('grades', api\GradeController::class);
        Route::apiResource('classrooms', api\ClassroomController::class);

        Route::apiResource('courses', api\CourseController::class);
        Route::apiResource('exams', api\ExamController::class);
        Route::apiResource('exam-results', api\ExamResultController::class);

        Route::apiResource('attendances', api\AttendanceController::class);
        Route::apiResource('homeworks', api\HomeworkController::class);

        Route::apiResource('payments', api\PaymentController::class);
        Route::apiResource('notifications', api\NotificationController::class);

    });
});
