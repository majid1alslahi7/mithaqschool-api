<?php

use App\Http\Controllers\api;
use App\Http\Controllers\api\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

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

    // ========== AUTHENTICATED ROUTES (Sanctum) ==========
    Route::middleware(['auth:sanctum', 'active.user', 'set.api.locale'])->name('api.v1.')->group(function () {

        // Auth & Profile
        Route::post('/logout', [api\ApiAuthController::class, 'logout'])->name('api.logout');
        Route::get('/user', [api\UserController::class, 'profile'])->name('user');
        Route::put('/password', [api\ApiAuthController::class, 'updatePassword'])->name('api.password.update');
        Route::post('/email/verification-notification', [api\ApiAuthController::class, 'sendVerificationEmail'])
            ->middleware('throttle:6,1')
            ->name('api.verification.send');

        // Session Management (Admin only)
        Route::middleware(['role:super-admin|admin'])->group(function () {
            Route::get('/sessions', [api\SessionController::class, 'index'])->name('sessions.index');
            Route::delete('/sessions/{id}', [api\SessionController::class, 'destroy'])->name('sessions.destroy');
        });

        // Global Search (All authenticated users)
        Route::get('/search', SearchController::class)->name('search');

        // ========== DASHBOARDS (Based on Role) ==========
        // Admin Dashboard
        Route::middleware(['role:super-admin|admin', 'permission:view_admin_dashboard'])
            ->get('/dashboard/admin', [api\DashboardController::class, 'adminDashboard'])
            ->name('dashboard.admin');

        // Teacher Dashboard
        Route::middleware(['role:teacher', 'permission:view_teacher_dashboard'])
            ->get('/dashboard/teacher', [api\DashboardController::class, 'teacherDashboard'])
            ->name('dashboard.teacher');

        // Student Dashboard
        Route::middleware(['role:student', 'permission:view_student_dashboard'])
            ->get('/dashboard/student', [api\DashboardController::class, 'studentDashboard'])
            ->name('dashboard.student');

        // Guardian Dashboard
        Route::middleware(['role:guardian', 'permission:view_parent_dashboard'])
            ->get('/dashboard/parent', [api\DashboardController::class, 'parentDashboard'])
            ->name('dashboard.parent');

        // General Dashboard Counts (All authenticated)
        Route::get('/dashboard/counts', [api\DashboardController::class, 'counts'])->name('dashboard.counts');

        // ========== USER MANAGEMENT (Admin only) ==========
        Route::middleware(['role:super-admin|admin'])->prefix('admin')->name('admin.')->group(function () {
            Route::apiResource('users', api\UserController::class);
            
            // Roles & Permissions Management
            Route::apiResource('roles', api\RoleController::class);
            Route::apiResource('permissions', api\PermissionController::class);
            
            // Assign/Revoke permissions & roles
            Route::post('roles/assign-permission', [api\RolePermissionController::class, 'assignPermissionToRole']);
            Route::post('roles/revoke-permission', [api\RolePermissionController::class, 'revokePermissionFromRole']);
            Route::post('users/assign-role', [api\RolePermissionController::class, 'assignRoleToUser']);
            Route::post('users/revoke-role', [api\RolePermissionController::class, 'revokeRoleFromUser']);
            Route::post('users/give-permission', [api\RolePermissionController::class, 'givePermissionToUser']);
            Route::post('users/revoke-permission', [api\RolePermissionController::class, 'revokePermissionFromUser']);
        });

        // ========== STUDENT MANAGEMENT ==========
        // Public student data (all authenticated can view)
        Route::prefix('students')->name('students.')->group(function () {
            Route::get('/', [api\StudentController::class, 'index'])
                ->middleware('permission:view_any_student')
                ->name('index');
            
            Route::get('/stats', [api\StudentController::class, 'stats'])
                ->middleware('permission:view_any_student')
                ->name('stats');
            
            // Student with ownership check
            Route::get('/{student}', [api\StudentController::class, 'show'])
                ->middleware(['permission:view_student', 'check.student.ownership'])
                ->name('show');
            
            // Student billing summary
            Route::get('/{student}/billing-summary', [api\StudentController::class, 'billingSummary'])
                ->middleware(['permission:view_invoice', 'check.student.ownership'])
                ->name('billing-summary');
            
            // Admin only operations
            Route::middleware(['role:super-admin|admin'])->group(function () {
                Route::post('/', [api\StudentController::class, 'store'])
                    ->middleware('permission:create_student')
                    ->name('store');
                
                Route::put('/{student}', [api\StudentController::class, 'update'])
                    ->middleware(['permission:update_student', 'check.student.ownership'])
                    ->name('update');
                
                Route::delete('/{student}', [api\StudentController::class, 'destroy'])
                    ->middleware(['permission:delete_student', 'check.student.ownership'])
                    ->name('destroy');
                
                Route::post('/import', [api\StudentController::class, 'import'])
                    ->middleware('permission:import_students')
                    ->name('import');
                
                Route::get('/export', [api\StudentController::class, 'export'])
                    ->middleware('permission:export_students')
                    ->name('export');
            });
            
            // Avatar routes
            Route::prefix('{student}/avatar')->name('avatar.')->group(function () {
                Route::get('/', [api\StudentController::class, 'getAvatar'])->name('show');
                Route::post('/', [api\StudentController::class, 'updateAvatar'])
                    ->middleware(['auth:sanctum', 'check.student.ownership'])
                    ->name('update');
                Route::delete('/', [api\StudentController::class, 'deleteAvatar'])
                    ->middleware(['auth:sanctum', 'check.student.ownership'])
                    ->name('destroy');
            });
        });

        // ========== TEACHER MANAGEMENT ==========
        Route::prefix('teachers')->name('teachers.')->group(function () {
            Route::get('/', [api\TeacherController::class, 'index'])
                ->middleware('permission:view_any_teacher')
                ->name('index');
            
            Route::get('/stats', [api\TeacherController::class, 'stats'])
                ->middleware('permission:view_any_teacher')
                ->name('stats');
            
            Route::get('/{teacher}', [api\TeacherController::class, 'show'])
                ->middleware(['permission:view_teacher', 'check.teacher.ownership'])
                ->name('show');
            
            Route::middleware(['role:super-admin|admin'])->group(function () {
                Route::post('/', [api\TeacherController::class, 'store'])
                    ->middleware('permission:create_teacher')
                    ->name('store');
                
                Route::put('/{teacher}', [api\TeacherController::class, 'update'])
                    ->middleware(['permission:update_teacher', 'check.teacher.ownership'])
                    ->name('update');
                
                Route::delete('/{teacher}', [api\TeacherController::class, 'destroy'])
                    ->middleware(['permission:delete_teacher', 'check.teacher.ownership'])
                    ->name('destroy');
            });
            
            // Avatar routes
            Route::prefix('{teacher}/avatar')->name('avatar.')->group(function () {
                Route::get('/', [api\TeacherController::class, 'getAvatar'])->name('show');
                Route::post('/', [api\TeacherController::class, 'updateAvatar'])
                    ->middleware(['auth:sanctum', 'check.teacher.ownership'])
                    ->name('update');
                Route::delete('/', [api\TeacherController::class, 'deleteAvatar'])
                    ->middleware(['auth:sanctum', 'check.teacher.ownership'])
                    ->name('destroy');
            });
        });

        // ========== GUARDIAN MANAGEMENT ==========
        Route::prefix('guardians')->name('guardians.')->group(function () {
            Route::get('/', [api\GuardianController::class, 'index'])
                ->middleware('permission:view_any_guardian')
                ->name('index');
            
            Route::get('/{guardian}', [api\GuardianController::class, 'show'])
                ->middleware(['permission:view_guardian', 'check.guardian.ownership'])
                ->name('show');
            
            Route::middleware(['role:super-admin|admin'])->group(function () {
                Route::post('/', [api\GuardianController::class, 'store'])
                    ->middleware('permission:create_guardian')
                    ->name('store');
                
                Route::put('/{guardian}', [api\GuardianController::class, 'update'])
                    ->middleware(['permission:update_guardian', 'check.guardian.ownership'])
                    ->name('update');
                
                Route::delete('/{guardian}', [api\GuardianController::class, 'destroy'])
                    ->middleware(['permission:delete_guardian', 'check.guardian.ownership'])
                    ->name('destroy');
            });
            
            // Avatar routes
            Route::prefix('{guardian}/avatar')->name('avatar.')->group(function () {
                Route::get('/', [api\GuardianController::class, 'getAvatar'])->name('show');
                Route::post('/', [api\GuardianController::class, 'updateAvatar'])
                    ->middleware(['auth:sanctum', 'check.guardian.ownership'])
                    ->name('update');
                Route::delete('/', [api\GuardianController::class, 'deleteAvatar'])
                    ->middleware(['auth:sanctum', 'check.guardian.ownership'])
                    ->name('destroy');
            });
        });

        // ========== ACADEMIC MANAGEMENT ==========
        Route::prefix('academics')->name('academics.')->group(function () {
            // Academic Years
            Route::apiResource('academic-years', api\AcademicYearController::class)
                ->middleware('permission:view_any_academic_year');
            
            // Semesters
            Route::apiResource('semesters', api\SemesterController::class)
                ->middleware('permission:view_any_semester');
            
            // School Stages
            Route::apiResource('school-stages', api\SchoolStageController::class)
                ->middleware('permission:view_any_school_stage');
            
            // Grades
            Route::apiResource('grades', api\GradeController::class)
                ->middleware('permission:view_any_grade');
            
            // Classrooms
            Route::apiResource('classrooms', api\ClassroomController::class)
                ->middleware('permission:view_any_classroom');
            
            // Courses
            Route::get('courses/search', [api\CourseController::class, 'search'])
                ->middleware('permission:view_any_course')
                ->name('courses.search');
            
            Route::apiResource('courses', api\CourseController::class)
                ->middleware('permission:view_any_course');
            
            // Teacher Course Assignment
            Route::apiResource('teacher-courses', api\TeacherCourseController::class)
                ->middleware('permission:assign_teacher_to_course');
            
            // Course Classroom Teacher Relation
            Route::apiResource('course-classroom-teachers', api\CourseClassroomTeacherController::class)
                ->middleware('permission:assign_teacher_to_course');
        });

        // ========== EXAMS & GRADES ==========
        Route::prefix('exams')->name('exams.')->group(function () {
            // Exam Types (Admin only for create/update/delete)
            Route::get('exam-types', [api\ExamTypeController::class, 'index'])
                ->middleware('permission:view_any_exam_type')
                ->name('exam-types.index');
            
            Route::get('exam-types/{examType}', [api\ExamTypeController::class, 'show'])
                ->middleware('permission:view_exam_type')
                ->name('exam-types.show');
            
            Route::middleware(['role:super-admin|admin'])->group(function () {
                Route::post('exam-types', [api\ExamTypeController::class, 'store'])
                    ->middleware('permission:create_exam_type')
                    ->name('exam-types.store');
                
                Route::put('exam-types/{examType}', [api\ExamTypeController::class, 'update'])
                    ->middleware('permission:update_exam_type')
                    ->name('exam-types.update');
                
                Route::delete('exam-types/{examType}', [api\ExamTypeController::class, 'destroy'])
                    ->middleware('permission:delete_exam_type')
                    ->name('exam-types.destroy');
            });
            
            // Exams
            Route::get('/', [api\ExamController::class, 'index'])
                ->middleware('permission:view_any_exam')
                ->name('index');
            
            Route::get('/{exam}', [api\ExamController::class, 'show'])
                ->middleware(['permission:view_exam', 'check.exam.ownership'])
                ->name('show');
            
            Route::middleware(['role:teacher'])->group(function () {
                Route::post('/', [api\ExamController::class, 'store'])
                    ->middleware(['permission:create_exam', 'check.course.teacher'])
                    ->name('store');
                
                Route::put('/{exam}', [api\ExamController::class, 'update'])
                    ->middleware(['permission:update_exam', 'check.exam.ownership'])
                    ->name('update');
            });
            
            Route::delete('/{exam}', [api\ExamController::class, 'destroy'])
                ->middleware(['role:super-admin|admin', 'permission:delete_exam'])
                ->name('destroy');
            
            // Exam Results
            Route::get('exam-results', [api\ExamResultController::class, 'index'])
                ->middleware('permission:view_any_exam_result')
                ->name('results.index');
            
            Route::get('exam-results/{examResult}', [api\ExamResultController::class, 'show'])
                ->middleware('permission:view_exam_result')
                ->name('results.show');
            
            Route::middleware(['role:teacher'])->group(function () {
                Route::post('exam-results', [api\ExamResultController::class, 'store'])
                    ->middleware(['permission:create_exam_result', 'check.course.teacher'])
                    ->name('results.store');
                
                Route::put('exam-results/{examResult}', [api\ExamResultController::class, 'update'])
                    ->middleware(['permission:update_exam_result', 'check.course.teacher'])
                    ->name('results.update');
            });
            
            // Grades Scales
            Route::apiResource('grades-scales', api\GradesScaleController::class)
                ->middleware('permission:view_any_grade_scale');
            
            // Monthly Grades
            Route::prefix('monthly-grades')->name('monthly.')->group(function () {
                Route::get('/', [api\MonthlyGradeController::class, 'index'])
                    ->middleware('permission:view_any_monthly_grade')
                    ->name('index');
                
                Route::get('/student/{enrollment_number}/month/{month}', [api\MonthlyGradeController::class, 'getByStudentAndMonth'])
                    ->middleware('permission:view_monthly_grade')
                    ->name('by-student-month');
                
                Route::get('/{monthlyGrade}', [api\MonthlyGradeController::class, 'show'])
                    ->middleware('permission:view_monthly_grade')
                    ->name('show');
                
                Route::middleware(['role:teacher'])->group(function () {
                    Route::post('/', [api\MonthlyGradeController::class, 'store'])
                        ->middleware(['permission:create_monthly_grade', 'check.course.teacher'])
                        ->name('store');
                    
                    Route::put('/{monthlyGrade}', [api\MonthlyGradeController::class, 'update'])
                        ->middleware(['permission:update_monthly_grade', 'check.course.teacher'])
                        ->name('update');
                });
            });
            
            // Semester Grades
            Route::prefix('semester-grades')->name('semester.')->group(function () {
                Route::get('/', [api\SemesterGradeController::class, 'index'])
                    ->middleware('permission:view_any_semester_grade')
                    ->name('index');
                
                Route::get('/student/{enrollment_number}/semester/{semester_id}', [api\SemesterGradeController::class, 'getByStudentAndSemester'])
                    ->middleware('permission:view_semester_grade')
                    ->name('by-student-semester');
                
                Route::get('/{semesterGrade}', [api\SemesterGradeController::class, 'show'])
                    ->middleware('permission:view_semester_grade')
                    ->name('show');
                
                Route::middleware(['role:teacher'])->group(function () {
                    Route::post('/', [api\SemesterGradeController::class, 'store'])
                        ->middleware(['permission:create_semester_grade', 'check.course.teacher'])
                        ->name('store');
                    
                    Route::put('/{semesterGrade}', [api\SemesterGradeController::class, 'update'])
                        ->middleware(['permission:update_semester_grade', 'check.course.teacher'])
                        ->name('update');
                });
            });
            
            // Final Grades
            Route::prefix('finaly-grades')->name('final.')->group(function () {
                Route::get('/', [api\FinalyGradeController::class, 'index'])
                    ->middleware('permission:view_any_final_grade')
                    ->name('index');
                
                Route::get('/student/{enrollment_number}/year/{academic_year_id}', [api\FinalyGradeController::class, 'getByStudentAndYear'])
                    ->middleware('permission:view_final_grade')
                    ->name('by-student-year');
                
                Route::get('/students/{student_number}/grades-by-year', [api\FinalyGradeController::class, 'getStudentGradesByAcademicYear'])
                    ->middleware('permission:view_final_grade')
                    ->name('by-student-academic');
                
                Route::get('/{finalyGrade}', [api\FinalyGradeController::class, 'show'])
                    ->middleware('permission:view_final_grade')
                    ->name('show');
                
                Route::middleware(['role:teacher'])->group(function () {
                    Route::post('/', [api\FinalyGradeController::class, 'store'])
                        ->middleware(['permission:create_final_grade', 'check.course.teacher'])
                        ->name('store');
                    
                    Route::put('/{finalyGrade}', [api\FinalyGradeController::class, 'update'])
                        ->middleware(['permission:update_final_grade', 'check.course.teacher'])
                        ->name('update');
                });
            });
        });

        // ========== ATTENDANCE MANAGEMENT ==========
        Route::prefix('attendance')->name('attendance.')->group(function () {
            // Attendances
            Route::get('/', [api\AttendanceController::class, 'index'])
                ->middleware('permission:view_any_attendance')
                ->name('index');
            
            Route::get('/{attendance}', [api\AttendanceController::class, 'show'])
                ->middleware(['permission:view_attendance', 'check.attendance.ownership'])
                ->name('show');
            
            Route::middleware(['role:teacher'])->group(function () {
                Route::post('/', [api\AttendanceController::class, 'store'])
                    ->middleware(['permission:take_attendance', 'check.course.teacher'])
                    ->name('store');
                
                Route::put('/{attendance}', [api\AttendanceController::class, 'update'])
                    ->middleware(['permission:update_attendance', 'check.attendance.ownership'])
                    ->name('update');
            });
            
            // Daily Attendance
            Route::apiResource('daily-attendances', api\DailyAttendanceController::class)
                ->middleware('permission:view_any_attendance');
        });

        // ========== HOMEWORK MANAGEMENT ==========
        Route::prefix('homeworks')->name('homeworks.')->group(function () {
            // Homeworks
            Route::get('/', [api\HomeworkController::class, 'index'])
                ->middleware('permission:view_any_homework')
                ->name('index');
            
            Route::get('/{homework}', [api\HomeworkController::class, 'show'])
                ->middleware(['permission:view_homework', 'check.homework.ownership'])
                ->name('show');
            
            Route::middleware(['role:teacher'])->group(function () {
                Route::post('/', [api\HomeworkController::class, 'store'])
                    ->middleware(['permission:create_homework', 'check.course.teacher'])
                    ->name('store');
                
                Route::put('/{homework}', [api\HomeworkController::class, 'update'])
                    ->middleware(['permission:update_homework', 'check.homework.ownership'])
                    ->name('update');
            });
            
            Route::delete('/{homework}', [api\HomeworkController::class, 'destroy'])
                ->middleware(['role:super-admin|admin', 'permission:delete_homework'])
                ->name('destroy');
            
            // Homework Submissions
            Route::prefix('submissions')->name('submissions.')->group(function () {
                Route::get('/', [api\HomeworkSubmissionController::class, 'index'])
                    ->middleware('permission:view_any_homework_submission')
                    ->name('index');
                
                Route::get('/{submission}', [api\HomeworkSubmissionController::class, 'show'])
                    ->middleware('permission:view_homework_submission')
                    ->name('show');
                
                Route::post('/', [api\HomeworkSubmissionController::class, 'store'])
                    ->middleware(['permission:submit_homework', 'check.homework.ownership'])
                    ->name('store');
                
                Route::post('/{submission}/grade', [api\HomeworkSubmissionController::class, 'grade'])
                    ->middleware(['role:teacher', 'permission:grade_homework_submission'])
                    ->name('grade');
            });
        });

        // ========== SCHEDULE MANAGEMENT ==========
        Route::prefix('schedules')->name('schedules.')->group(function () {
            Route::get('/', [api\ScheduleController::class, 'index'])
                ->middleware('permission:view_any_schedule')
                ->name('index');
            
            Route::get('/teacher/{teacher}', [api\ScheduleController::class, 'teacher'])
                ->middleware('permission:view_schedule')
                ->name('by-teacher');
            
            Route::get('/course/{course}', [api\ScheduleController::class, 'course'])
                ->middleware('permission:view_schedule')
                ->name('by-course');
            
            Route::get('/classroom/{classroom}', [api\ScheduleController::class, 'classroom'])
                ->middleware('permission:view_schedule')
                ->name('by-classroom');
            
            Route::get('/{schedule}', [api\ScheduleController::class, 'show'])
                ->middleware(['permission:view_schedule', 'check.schedule.ownership'])
                ->name('show');
            
            Route::middleware(['role:super-admin|admin'])->group(function () {
                Route::post('/', [api\ScheduleController::class, 'store'])
                    ->middleware('permission:create_schedule')
                    ->name('store');
                
                Route::put('/{schedule}', [api\ScheduleController::class, 'update'])
                    ->middleware('permission:update_schedule')
                    ->name('update');
                
                Route::delete('/{schedule}', [api\ScheduleController::class, 'destroy'])
                    ->middleware('permission:delete_schedule')
                    ->name('destroy');
            });
        });

        // ========== BEHAVIOR EVALUATION ==========
        Route::prefix('behavior-evaluations')->name('behavior.')->group(function () {
            Route::get('/', [api\BehaviorEvaluationController::class, 'index'])
                ->middleware('permission:view_any_behavior_evaluation')
                ->name('index');
            
            Route::get('/{evaluation}', [api\BehaviorEvaluationController::class, 'show'])
                ->middleware(['permission:view_behavior_evaluation', 'check.behavior.ownership'])
                ->name('show');
            
            Route::middleware(['role:teacher'])->group(function () {
                Route::post('/', [api\BehaviorEvaluationController::class, 'store'])
                    ->middleware(['permission:create_behavior_evaluation', 'check.course.teacher'])
                    ->name('store');
                
                Route::put('/{evaluation}', [api\BehaviorEvaluationController::class, 'update'])
                    ->middleware(['permission:update_behavior_evaluation', 'check.behavior.ownership'])
                    ->name('update');
            });
            
            Route::delete('/{evaluation}', [api\BehaviorEvaluationController::class, 'destroy'])
                ->middleware(['role:super-admin|admin', 'permission:delete_behavior_evaluation'])
                ->name('destroy');
        });

        // ========== FINANCIAL MANAGEMENT ==========
        Route::prefix('financial')->name('financial.')->group(function () {
            // Fee Types
            Route::apiResource('fee-types', api\FeeTypeController::class)
                ->middleware('permission:manage_fees');
            
            // Class Fees
            Route::apiResource('class-fees', api\ClassFeeController::class)
                ->middleware('permission:manage_fees');
            
            // Student Invoices
            Route::prefix('invoices')->name('invoices.')->group(function () {
                Route::get('/', [api\StudentInvoiceController::class, 'index'])
                    ->middleware('permission:view_any_invoice')
                    ->name('index');
                
                Route::get('/{invoice}', [api\StudentInvoiceController::class, 'show'])
                    ->middleware(['permission:view_invoice', 'check.invoice.ownership'])
                    ->name('show');
                
                Route::post('/', [api\StudentInvoiceController::class, 'store'])
                    ->middleware(['role:super-admin|admin', 'permission:create_invoice'])
                    ->name('store');
                
                Route::put('/{invoice}', [api\StudentInvoiceController::class, 'update'])
                    ->middleware(['role:super-admin|admin', 'permission:update_invoice'])
                    ->name('update');
                
                Route::delete('/{invoice}', [api\StudentInvoiceController::class, 'destroy'])
                    ->middleware(['role:super-admin|admin', 'permission:delete_invoice'])
                    ->name('destroy');
                
                Route::post('/{invoice}/pay', [api\StudentInvoiceController::class, 'pay'])
                    ->middleware(['permission:process_payment', 'check.invoice.ownership'])
                    ->name('pay');
            });
            
            // Invoice Items
            Route::apiResource('invoice-items', api\InvoiceItemController::class)
                ->middleware('permission:view_any_invoice');
            
            // Payments
            Route::prefix('payments')->name('payments.')->group(function () {
                Route::get('/', [api\PaymentController::class, 'index'])
                    ->middleware('permission:view_transactions')
                    ->name('index');
                
                Route::get('/{payment}', [api\PaymentController::class, 'show'])
                    ->middleware('permission:view_transactions')
                    ->name('show');
                
                Route::post('/', [api\PaymentController::class, 'store'])
                    ->middleware('permission:process_payment')
                    ->name('store');
                
                Route::post('/{payment}/refund', [api\PaymentController::class, 'refund'])
                    ->middleware(['role:super-admin|admin', 'permission:refund_payment'])
                    ->name('refund');
            });
            
            // Adjustments
            Route::apiResource('adjustments', api\AdjustmentController::class)
                ->middleware('permission:manage_fees');
        });

        // ========== COMMUNICATION ==========
        Route::prefix('communication')->name('communication.')->group(function () {
            // Messages
            Route::apiResource('messages', api\MessageController::class)
                ->middleware('permission:view_message');
            
            // Notifications
            Route::prefix('notifications')->name('notifications.')->group(function () {
                Route::get('/', [api\NotificationController::class, 'index'])
                    ->middleware('permission:view_notification')
                    ->name('index');
                
                Route::get('/my', [api\NotificationController::class, 'myNotifications'])
                    ->middleware('permission:view_notification')
                    ->name('my');
                
                Route::get('/unread', [api\NotificationController::class, 'unread'])
                    ->middleware('permission:view_notification')
                    ->name('unread');
                
                Route::post('/', [api\NotificationController::class, 'store'])
                    ->middleware('permission:send_notification')
                    ->name('store');
                
                Route::put('/{notification}/read', [api\NotificationController::class, 'markAsRead'])
                    ->middleware('permission:view_notification')
                    ->name('read');
            });
        });

        // ========== SCHOOL SETTINGS (Admin only) ==========
        Route::prefix('school')->name('school.')->middleware(['role:super-admin|admin'])->group(function () {
            Route::apiResource('settings', api\SchoolController::class);
            
            // General Settings
            Route::get('/general-settings', [api\SettingsController::class, 'general'])
                ->middleware('permission:manage_general_settings')
                ->name('general');
            
            Route::put('/general-settings', [api\SettingsController::class, 'updateGeneral'])
                ->middleware('permission:manage_general_settings')
                ->name('general.update');
            
            // System Settings
            Route::get('/system-settings', [api\SettingsController::class, 'system'])
                ->middleware('permission:manage_system_settings')
                ->name('system');
            
            Route::put('/system-settings', [api\SettingsController::class, 'updateSystem'])
                ->middleware('permission:manage_system_settings')
                ->name('system.update');
            
            // Backup
            Route::post('/backup', [api\BackupController::class, 'create'])
                ->middleware('permission:perform_backup')
                ->name('backup.create');
            
            Route::post('/restore', [api\BackupController::class, 'restore'])
                ->middleware('permission:restore_from_backup')
                ->name('backup.restore');
            
            // Cache
            Route::post('/clear-cache', [api\CacheController::class, 'clear'])
                ->middleware('permission:clear_cache')
                ->name('cache.clear');
        });

        // ========== REPORTS ==========
        Route::prefix('reports')->name('reports.')->middleware('permission:view_reports')->group(function () {
            Route::get('/students', [api\ReportController::class, 'students'])
                ->middleware('permission:generate_student_report')
                ->name('students');
            
            Route::get('/financial', [api\ReportController::class, 'financial'])
                ->middleware('permission:generate_financial_report')
                ->name('financial');
            
            Route::get('/attendance', [api\ReportController::class, 'attendance'])
                ->middleware('permission:generate_attendance_report')
                ->name('attendance');
            
            Route::get('/exams', [api\ReportController::class, 'exams'])
                ->middleware('permission:generate_exam_report')
                ->name('exams');
            
            Route::get('/teachers', [api\ReportController::class, 'teachers'])
                ->middleware('permission:generate_teacher_report')
                ->name('teachers');
            
            Route::get('/export/{type}', [api\ReportController::class, 'export'])
                ->middleware('permission:export_reports')
                ->name('export');
        });

        // ========== API ACCESS (For external applications) ==========
        Route::prefix('external')->name('external.')->middleware('permission:access_api')->group(function () {
            Route::get('/students', [api\ExternalApiController::class, 'getStudents']);
            Route::get('/students/{student}', [api\ExternalApiController::class, 'getStudent']);
            Route::get('/courses', [api\ExternalApiController::class, 'getCourses']);
            Route::get('/grades/{student}', [api\ExternalApiController::class, 'getGrades']);
        });
    });
});
