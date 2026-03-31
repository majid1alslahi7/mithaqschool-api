<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Roles
    |--------------------------------------------------------------------------
    */
    'default_roles' => [
        'super-admin' => 'مدير النظام',
        'admin' => 'مدير المدرسة',
        'teacher' => 'معلم',
        'student' => 'طالب',
        'guardian' => 'ولي أمر',
    ],

    /*
    |--------------------------------------------------------------------------
    | Role Permissions Mapping
    |--------------------------------------------------------------------------
    */
    'role_permissions' => [
        'super-admin' => '*', // جميع الصلاحيات
        'admin' => [
            'view_any_user', 'view_user', 'create_user', 'update_user', 'delete_user',
            'view_any_role', 'view_role', 'create_role', 'update_role', 'delete_role',
            'view_any_permission', 'view_permission', 'assign_permission',
            'view_any_student', 'view_student', 'create_student', 'update_student', 'delete_student',
            'view_any_teacher', 'view_teacher', 'create_teacher', 'update_teacher', 'delete_teacher',
            'view_any_guardian', 'view_guardian', 'create_guardian', 'update_guardian', 'delete_guardian',
            'view_any_academic_year', 'create_academic_year', 'update_academic_year', 'delete_academic_year',
            'view_any_semester', 'create_semester', 'update_semester', 'delete_semester',
            'view_any_school_stage', 'create_school_stage', 'update_school_stage', 'delete_school_stage',
            'view_any_grade', 'create_grade', 'update_grade', 'delete_grade',
            'view_any_classroom', 'create_classroom', 'update_classroom', 'delete_classroom',
            'view_any_course', 'create_course', 'update_course', 'delete_course', 'assign_teacher_to_course',
            'view_any_exam', 'create_exam', 'update_exam', 'delete_exam',
            'view_any_exam_type', 'create_exam_type', 'update_exam_type', 'delete_exam_type',
            'view_any_exam_result', 'create_exam_result', 'update_exam_result', 'delete_exam_result',
            'view_any_attendance', 'take_attendance', 'update_attendance',
            'view_any_homework', 'create_homework', 'update_homework', 'delete_homework',
            'view_any_homework_submission', 'grade_homework_submission',
            'view_any_schedule', 'create_schedule', 'update_schedule', 'delete_schedule',
            'send_message', 'view_message', 'send_notification', 'view_notification',
            'view_any_behavior_evaluation', 'create_behavior_evaluation', 'update_behavior_evaluation', 'delete_behavior_evaluation',
            'view_reports', 'generate_student_report', 'generate_financial_report', 'generate_attendance_report',
            'view_any_invoice', 'create_invoice', 'update_invoice', 'delete_invoice', 'manage_fees',
            'view_admin_dashboard', 'manage_general_settings', 'manage_school_settings',
            'view_system_logs', 'perform_backup', 'export_data', 'import_data', 'clear_cache',
        ],
        'teacher' => [
            'view_any_student', 'view_student',
            'view_any_course', 'view_course',
            'take_attendance', 'update_attendance',
            'view_any_exam', 'create_exam', 'update_exam',
            'view_any_exam_result', 'create_exam_result', 'update_exam_result',
            'view_any_homework', 'create_homework', 'update_homework',
            'grade_homework_submission',
            'view_any_monthly_grade', 'create_monthly_grade', 'update_monthly_grade',
            'view_any_semester_grade', 'create_semester_grade', 'update_semester_grade',
            'view_any_final_grade', 'create_final_grade', 'update_final_grade',
            'view_any_behavior_evaluation', 'create_behavior_evaluation', 'update_behavior_evaluation',
            'send_message', 'view_message', 'send_notification',
            'view_schedule', 'view_teacher_dashboard',
        ],
        'student' => [
            'view_student_dashboard',
            'view_schedule',
            'submit_homework',
            'view_message',
            'view_notification',
            'view_own_grades',
            'view_own_attendance',
            'view_own_invoice',
        ],
        'guardian' => [
            'view_parent_dashboard',
            'view_children_grades',
            'view_children_attendance',
            'view_children_homework',
            'view_children_invoices',
            'view_schedule',
            'send_message',
            'view_message',
            'view_notification',
            'process_payment',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'enabled' => true,
        'ttl' => 86400, // 24 hours
        'key' => 'user_permissions',
    ],

    /*
    |--------------------------------------------------------------------------
    | Middleware Settings
    |--------------------------------------------------------------------------
    */
    'middleware' => [
        'check_permission' => [
            'redirect_to' => '/',
            'message' => 'غير مصرح لك بالوصول إلى هذه الصفحة',
        ],
        'check_role' => [
            'redirect_to' => '/',
            'message' => 'غير مصرح لك بالوصول إلى هذه الصفحة',
        ],
    ],

];