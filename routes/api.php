<?php

use App\Http\Controllers\api;
use Illuminate\Http\Request;
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
use Illuminate\Support\Facades\Route;


// Public route for API documentation or status
Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the MithaqSchool API v1.',
        'documentation_url' => url('/api/documentation'),
    ]);
});

// API Version 1 Routes
Route::prefix('v1')->group(function () {

    // ========== PUBLIC ROUTES (No Authentication) ==========
    Route::post('/login', [api\ApiAuthController::class, 'login'])->name('api.login');
    Route::post('/signup', [api\ApiAuthController::class, 'signUp'])->name('api.signup');
    Route::post('/forgot-password', [api\ApiAuthController::class, 'forgotPassword'])->name('api.password.request');
    Route::post('/reset-password', [api\ApiAuthController::class, 'resetPassword'])->name('api.password.store');

    // Email verification handler
    Route::get('/verify-email/{id}/{hash}', [api\ApiAuthController::class, 'verifyEmail'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('api.verification.verify');

    // ========== AUTHENTICATED ROUTES (Sanctum only, no other middleware) ==========
    Route::middleware(['auth:sanctum'])->name('api.v1.')->group(function () {

        // Auth & Profile
        Route::post('/logout', [api\ApiAuthController::class, 'logout'])->name('api.logout');
        Route::get('/user', [api\UserController::class, 'profile'])->name('user');
        Route::put('/password', [api\ApiAuthController::class, 'updatePassword'])->name('api.password.update');
        Route::post('/email/verification-notification', [api\ApiAuthController::class, 'sendVerificationEmail'])
            ->middleware('throttle:6,1')
            ->name('api.verification.send');

        // Session Management
        Route::get('/sessions', [api\SessionController::class, 'index'])->name('sessions.index');
        Route::delete('/sessions/{id}', [api\SessionController::class, 'destroy'])->name('sessions.destroy');

        // Global Search
        Route::get('/search', SearchController::class)->name('search');

        // Dashboard Counts
        Route::get('/dashboard/counts', [api\DashboardController::class, 'counts'])->name('dashboard.counts');

        // ========== USER MANAGEMENT ==========
        Route::apiResource('users', api\UserController::class);

        // ========== STUDENT MANAGEMENT ==========
        Route::prefix('students')->name('students.')->group(function () {
            Route::get('/', [api\StudentController::class, 'index'])->name('index');
            Route::get('/stats', [api\StudentController::class, 'stats'])->name('stats');
            Route::get('/{student}', [api\StudentController::class, 'show'])->name('show');
            Route::get('/{student}/billing-summary', [api\StudentController::class, 'billingSummary'])->name('billing-summary');
            Route::post('/', [api\StudentController::class, 'store'])->name('store');
            Route::put('/{student}', [api\StudentController::class, 'update'])->name('update');
            Route::delete('/{student}', [api\StudentController::class, 'destroy'])->name('destroy');
            Route::post('/import', [api\StudentController::class, 'import'])->name('import');
            Route::get('/export', [api\StudentController::class, 'export'])->name('export');
            
            // Avatar routes
            Route::prefix('{student}/avatar')->name('avatar.')->group(function () {
                Route::get('/', [api\StudentController::class, 'getAvatar'])->name('show');
                Route::post('/', [api\StudentController::class, 'updateAvatar'])->name('update');
                Route::delete('/', [api\StudentController::class, 'deleteAvatar'])->name('destroy');
            });
        });

        // ========== TEACHER MANAGEMENT ==========
        Route::prefix('teachers')->name('teachers.')->group(function () {
            Route::get('/', [api\TeacherController::class, 'index'])->name('index');
            Route::get('/stats', [api\TeacherController::class, 'stats'])->name('stats');
            Route::get('/{teacher}', [api\TeacherController::class, 'show'])->name('show');
            Route::post('/', [api\TeacherController::class, 'store'])->name('store');
            Route::put('/{teacher}', [api\TeacherController::class, 'update'])->name('update');
            Route::delete('/{teacher}', [api\TeacherController::class, 'destroy'])->name('destroy');
            
            // Avatar routes
            Route::prefix('{teacher}/avatar')->name('avatar.')->group(function () {
                Route::get('/', [api\TeacherController::class, 'getAvatar'])->name('show');
                Route::post('/', [api\TeacherController::class, 'updateAvatar'])->name('update');
                Route::delete('/', [api\TeacherController::class, 'deleteAvatar'])->name('destroy');
            });
        });

        // ========== GUARDIAN MANAGEMENT ==========
        Route::prefix('guardians')->name('guardians.')->group(function () {
            Route::get('/', [api\GuardianController::class, 'index'])->name('index');
            Route::get('/{guardian}', [api\GuardianController::class, 'show'])->name('show');
            Route::post('/', [api\GuardianController::class, 'store'])->name('store');
            Route::put('/{guardian}', [api\GuardianController::class, 'update'])->name('update');
            Route::delete('/{guardian}', [api\GuardianController::class, 'destroy'])->name('destroy');
            
            // Avatar routes
            Route::prefix('{guardian}/avatar')->name('avatar.')->group(function () {
                Route::get('/', [api\GuardianController::class, 'getAvatar'])->name('show');
                Route::post('/', [api\GuardianController::class, 'updateAvatar'])->name('update');
                Route::delete('/', [api\GuardianController::class, 'deleteAvatar'])->name('destroy');
            });
        });

        // ========== ACADEMIC MANAGEMENT ==========
        Route::apiResource('academic-years', api\AcademicYearController::class);
        Route::apiResource('semesters', api\SemesterController::class);
        Route::apiResource('school-stages', api\SchoolStageController::class);
        Route::apiResource('grades', api\GradeController::class);
        Route::apiResource('classrooms', api\ClassroomController::class);
        Route::get('courses/search', [api\CourseController::class, 'search'])->name('courses.search');
        Route::apiResource('courses', api\CourseController::class);
        Route::apiResource('teacher-courses', api\TeacherCourseController::class);
        Route::apiResource('course-classroom-teachers', api\CourseClassroomTeacherController::class);

        // ========== EXAMS & GRADES ==========
        Route::apiResource('exams', api\ExamController::class);
        Route::apiResource('exam-types', api\ExamTypeController::class);
        Route::apiResource('exam-results', api\ExamResultController::class);
        Route::apiResource('grades-scales', api\GradesScaleController::class);
        
        // Monthly Grades
        Route::prefix('monthly-grades')->name('monthly.')->group(function () {
            Route::get('/', [api\MonthlyGradeController::class, 'index'])->name('index');
            Route::get('/student/{enrollment_number}/month/{month}', [api\MonthlyGradeController::class, 'getByStudentAndMonth'])->name('by-student-month');
            Route::get('/{monthlyGrade}', [api\MonthlyGradeController::class, 'show'])->name('show');
            Route::post('/', [api\MonthlyGradeController::class, 'store'])->name('store');
            Route::put('/{monthlyGrade}', [api\MonthlyGradeController::class, 'update'])->name('update');
        });
        
        // Semester Grades
        Route::prefix('semester-grades')->name('semester.')->group(function () {
            Route::get('/', [api\SemesterGradeController::class, 'index'])->name('index');
            Route::get('/student/{enrollment_number}/semester/{semester_id}', [api\SemesterGradeController::class, 'getByStudentAndSemester'])->name('by-student-semester');
            Route::get('/{semesterGrade}', [api\SemesterGradeController::class, 'show'])->name('show');
            Route::post('/', [api\SemesterGradeController::class, 'store'])->name('store');
            Route::put('/{semesterGrade}', [api\SemesterGradeController::class, 'update'])->name('update');
        });
        
        // Final Grades
        Route::prefix('finaly-grades')->name('final.')->group(function () {
            Route::get('/', [api\FinalyGradeController::class, 'index'])->name('index');
            Route::get('/student/{enrollment_number}/year/{academic_year_id}', [api\FinalyGradeController::class, 'getByStudentAndYear'])->name('by-student-year');
            Route::get('/students/{student_number}/grades-by-year', [api\FinalyGradeController::class, 'getStudentGradesByAcademicYear'])->name('by-student-academic');
            Route::get('/{finalyGrade}', [api\FinalyGradeController::class, 'show'])->name('show');
            Route::post('/', [api\FinalyGradeController::class, 'store'])->name('store');
            Route::put('/{finalyGrade}', [api\FinalyGradeController::class, 'update'])->name('update');
        });

        // ========== ATTENDANCE MANAGEMENT ==========
        Route::apiResource('attendances', api\AttendanceController::class);
        Route::apiResource('daily-attendances', api\DailyAttendanceController::class);

        // ========== HOMEWORK MANAGEMENT ==========
        Route::apiResource('homeworks', api\HomeworkController::class);
        Route::apiResource('homework-submissions', api\HomeworkSubmissionController::class);

        // ========== SCHEDULE MANAGEMENT ==========
        Route::apiResource('schedules', api\ScheduleController::class);
        Route::get('schedules/teacher/{teacher}', [api\ScheduleController::class, 'teacher']);
        Route::get('schedules/course/{course}', [api\ScheduleController::class, 'course']);
        Route::get('schedules/classroom/{classroom}', [api\ScheduleController::class, 'classroom']);

        // ========== BEHAVIOR EVALUATION ==========
        Route::apiResource('behavior-evaluations', api\BehaviorEvaluationController::class);

        // ========== FINANCIAL MANAGEMENT ==========
        Route::apiResource('fee-types', api\FeeTypeController::class);
        Route::apiResource('class-fees', api\ClassFeeController::class);
        Route::apiResource('student-invoices', api\StudentInvoiceController::class);
        Route::apiResource('invoice-items', api\InvoiceItemController::class);
        Route::apiResource('payments', api\PaymentController::class);
        Route::apiResource('adjustments', api\AdjustmentController::class);
        Route::post('student-invoices/{invoice}/pay', [api\StudentInvoiceController::class, 'pay']);

        // ========== COMMUNICATION ==========
        Route::apiResource('messages', api\MessageController::class);
        Route::get('my-notifications', [api\NotificationController::class, 'myNotifications']);
        Route::get('notifications/unread', [api\NotificationController::class, 'unread']);
        Route::apiResource('notifications', api\NotificationController::class);

        // ========== ROLES & PERMISSIONS ==========
        Route::apiResource('roles', api\RoleController::class);
        Route::apiResource('permissions', api\PermissionController::class);
        Route::post('roles/assign-permission', [api\RolePermissionController::class, 'assignPermissionToRole']);
        Route::post('roles/revoke-permission', [api\RolePermissionController::class, 'revokePermissionFromRole']);
        Route::post('users/assign-role', [api\RolePermissionController::class, 'assignRoleToUser']);
        Route::post('users/revoke-role', [api\RolePermissionController::class, 'revokeRoleFromUser']);
        Route::post('users/give-permission', [api\RolePermissionController::class, 'givePermissionToUser']);
        Route::post('users/revoke-permission', [api\RolePermissionController::class, 'revokePermissionFromUser']);

        // ========== SCHOOL SETTINGS ==========
        Route::apiResource('schools', api\SchoolController::class);

        // ========== REPORTS ==========
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/students', [api\ReportController::class, 'students']);
            Route::get('/financial', [api\ReportController::class, 'financial']);
            Route::get('/attendance', [api\ReportController::class, 'attendance']);
            Route::get('/exams', [api\ReportController::class, 'exams']);
            Route::get('/teachers', [api\ReportController::class, 'teachers']);
            Route::get('/export/{type}', [api\ReportController::class, 'export']);
        });
    });
});
