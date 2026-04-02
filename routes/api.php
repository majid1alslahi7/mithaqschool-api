<?php

use App\Http\Controllers\api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// Temporary route to clear cache and reload configs (for Render)
Route::get('/clear-cache-temp', function() {
    try {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('config:cache');

        return response()->json([
            'success' => true,
            'message' => 'تم تنظيف الكاش وإعادة تحميل الإعدادات بنجاح.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
});

// Public route for API documentation or status
Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the MithaqSchool API v1.',
        'documentation_url' => url('/api/documentation'),
    ]);
});

// API Version 1 Routes
Route::prefix('v1')->group(function () {

    // ========== PUBLIC ROUTES ==========
    Route::post('/login', [api\ApiAuthController::class, 'login'])->name('api.login');
    Route::post('/signup', [api\ApiAuthController::class, 'signUp'])->name('api.signup');
    Route::post('/forgot-password', [api\ApiAuthController::class, 'forgotPassword'])->name('api.password.request');
    Route::post('/reset-password', [api\ApiAuthController::class, 'resetPassword'])->name('api.password.store');
    Route::get('/verify-email/{id}/{hash}', [api\ApiAuthController::class, 'verifyEmail'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('api.verification.verify');

    // ========== AUTHENTICATED ROUTES ==========
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/logout', [api\ApiAuthController::class, 'logout'])->name('api.logout');
        Route::get('/user', [api\UserController::class, 'profile'])->name('user');

        // User management
        Route::apiResource('users', api\UserController::class);

        // Students
        Route::prefix('students')->name('students.')->group(function () {
            Route::get('/', [api\StudentController::class, 'index'])->name('index');
            Route::get('/stats', [api\StudentController::class, 'stats'])->name('stats');
            Route::get('/{student}', [api\StudentController::class, 'show'])->name('show');
            Route::post('/', [api\StudentController::class, 'store'])->name('store');
            Route::put('/{student}', [api\StudentController::class, 'update'])->name('update');
            Route::delete('/{student}', [api\StudentController::class, 'destroy'])->name('destroy');
            Route::post('/import', [api\StudentController::class, 'import'])->name('import');
            Route::get('/export', [api\StudentController::class, 'export'])->name('export');

            // Avatar
            Route::prefix('{student}/avatar')->name('avatar.')->group(function () {
                Route::get('/', [api\StudentController::class, 'getAvatar'])->name('show');
                Route::post('/', [api\StudentController::class, 'updateAvatar'])->name('update');
                Route::delete('/', [api\StudentController::class, 'deleteAvatar'])->name('destroy');
            });
        });

        // Teachers
        Route::prefix('teachers')->name('teachers.')->group(function () {
            Route::get('/', [api\TeacherController::class, 'index'])->name('index');
            Route::get('/stats', [api\TeacherController::class, 'stats'])->name('stats');
            Route::get('/{teacher}', [api\TeacherController::class, 'show'])->name('show');
            Route::post('/', [api\TeacherController::class, 'store'])->name('store');
            Route::put('/{teacher}', [api\TeacherController::class, 'update'])->name('update');
            Route::delete('/{teacher}', [api\TeacherController::class, 'destroy'])->name('destroy');

            // Avatar
            Route::prefix('{teacher}/avatar')->name('avatar.')->group(function () {
                Route::get('/', [api\TeacherController::class, 'getAvatar'])->name('show');
                Route::post('/', [api\TeacherController::class, 'updateAvatar'])->name('update');
                Route::delete('/', [api\TeacherController::class, 'deleteAvatar'])->name('destroy');
            });
        });

        // Guardians
        Route::prefix('guardians')->name('guardians.')->group(function () {
            Route::get('/', [api\GuardianController::class, 'index'])->name('index');
            Route::get('/{guardian}', [api\GuardianController::class, 'show'])->name('show');
            Route::post('/', [api\GuardianController::class, 'store'])->name('store');
            Route::put('/{guardian}', [api\GuardianController::class, 'update'])->name('update');
            Route::delete('/{guardian}', [api\GuardianController::class, 'destroy'])->name('destroy');

            // Avatar
            Route::prefix('{guardian}/avatar')->name('avatar.')->group(function () {
                Route::get('/', [api\GuardianController::class, 'getAvatar'])->name('show');
                Route::post('/', [api\GuardianController::class, 'updateAvatar'])->name('update');
                Route::delete('/', [api\GuardianController::class, 'deleteAvatar'])->name('destroy');
            });
        });

        // Academic management
        Route::apiResource('academic-years', api\AcademicYearController::class);
        Route::apiResource('semesters', api\SemesterController::class);
        Route::apiResource('school-stages', api\SchoolStageController::class);
        Route::apiResource('grades', api\GradeController::class);
        Route::apiResource('classrooms', api\ClassroomController::class);
        Route::get('courses/search', [api\CourseController::class, 'search'])->name('courses.search');
        Route::apiResource('courses', api\CourseController::class);
        Route::apiResource('teacher-courses', api\TeacherCourseController::class);
        Route::apiResource('course-classroom-teachers', api\CourseClassroomTeacherController::class);

        // Exams & Grades
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

        // باقي مساراتك (attendance, homeworks, schedules, behavior-evaluations, fee-types, payments, messages, notifications, roles, reports)...
        // يمكنك إضافتها بنفس النمط السابق.
    });
});
