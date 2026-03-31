<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * قائمة الصلاحيات مقسمة حسب المجموعات
     * لتسهيل الصيانة والفهم
     */
    protected $permissionsGroups = [
        // ========== مجموعة إدارة المستخدمين ==========
        'User Management' => [
            'view_any_user' => 'عرض كل المستخدمين',
            'view_user' => 'عرض مستخدم',
            'create_user' => 'إنشاء مستخدم',
            'update_user' => 'تحديث مستخدم',
            'delete_user' => 'حذف مستخدم',
        ],

        // ========== مجموعة إدارة الأدوار والصلاحيات ==========
        'Role & Permission Management' => [
            'view_any_role' => 'عرض كل الأدوار',
            'view_role' => 'عرض دور',
            'create_role' => 'إنشاء دور',
            'update_role' => 'تحديث دور',
            'delete_role' => 'حذف دور',
            'view_any_permission' => 'عرض كل الصلاحيات',
            'view_permission' => 'عرض صلاحية',
            'assign_permission' => 'تعيين صلاحية',
        ],

        // ========== مجموعة إدارة الطلاب ==========
        'Student Management' => [
            'view_any_student' => 'عرض كل الطلاب',
            'view_student' => 'عرض طالب',
            'create_student' => 'إنشاء طالب',
            'update_student' => 'تحديث طالب',
            'delete_student' => 'حذف طالب',
            'import_students' => 'استيراد طلاب من ملف',
            'export_students' => 'تصدير بيانات الطلاب',
        ],

        // ========== مجموعة إدارة المعلمين ==========
        'Teacher Management' => [
            'view_any_teacher' => 'عرض كل المعلمين',
            'view_teacher' => 'عرض معلم',
            'create_teacher' => 'إنشاء معلم',
            'update_teacher' => 'تحديث معلم',
            'delete_teacher' => 'حذف معلم',
            'import_teachers' => 'استيراد معلمين من ملف',
            'export_teachers' => 'تصدير بيانات المعلمين',
        ],

        // ========== مجموعة إدارة أولياء الأمور ==========
        'Guardian Management' => [
            'view_any_guardian' => 'عرض كل أولياء الأمور',
            'view_guardian' => 'عرض ولي أمر',
            'create_guardian' => 'إنشاء ولي أمر',
            'update_guardian' => 'تحديث ولي أمر',
            'delete_guardian' => 'حذف ولي أمر',
        ],

        // ========== مجموعة الإدارة الأكاديمية ==========
        'Academic Management' => [
            'view_any_academic_year' => 'عرض كل السنوات الدراسية',
            'view_academic_year' => 'عرض سنة دراسية',
            'create_academic_year' => 'إنشاء سنة دراسية',
            'update_academic_year' => 'تحديث سنة دراسية',
            'delete_academic_year' => 'حذف سنة دراسية',
            'view_any_semester' => 'عرض كل الفصول الدراسية',
            'view_semester' => 'عرض فصل دراسي',
            'create_semester' => 'إنشاء فصل دراسي',
            'update_semester' => 'تحديث فصل دراسي',
            'delete_semester' => 'حذف فصل دراسي',
            'view_any_school_stage' => 'عرض كل المراحل الدراسية',
            'view_school_stage' => 'عرض مرحلة دراسية',
            'create_school_stage' => 'إنشاء مرحلة دراسية',
            'update_school_stage' => 'تحديث مرحلة دراسية',
            'delete_school_stage' => 'حذف مرحلة دراسية',
            'view_any_grade' => 'عرض كل الصفوف',
            'view_grade' => 'عرض صف',
            'create_grade' => 'إنشاء صف',
            'update_grade' => 'تحديث صف',
            'delete_grade' => 'حذف صف',
            'view_any_classroom' => 'عرض كل الفصول',
            'view_classroom' => 'عرض فصل',
            'create_classroom' => 'إنشاء فصل',
            'update_classroom' => 'تحديث فصل',
            'delete_classroom' => 'حذف فصل',
        ],

        // ========== مجموعة المواد الدراسية ==========
        'Course Management' => [
            'view_any_course' => 'عرض كل المواد الدراسية',
            'view_course' => 'عرض مادة دراسية',
            'create_course' => 'إنشاء مادة دراسية',
            'update_course' => 'تحديث مادة دراسية',
            'delete_course' => 'حذف مادة دراسية',
            'assign_teacher_to_course' => 'تعيين معلم لمادة دراسية',
        ],

        // ========== مجموعة الامتحانات ==========
        'Exam Management' => [
            'view_any_exam' => 'عرض كل الاختبارات',
            'view_exam' => 'عرض اختبار',
            'create_exam' => 'إنشاء اختبار',
            'update_exam' => 'تحديث اختبار',
            'delete_exam' => 'حذف اختبار',
            'view_any_exam_type' => 'عرض كل أنواع الاختبارات',
            'view_exam_type' => 'عرض نوع اختبار',
            'create_exam_type' => 'إنشاء نوع اختبار',
            'update_exam_type' => 'تحديث نوع اختبار',
            'delete_exam_type' => 'حذف نوع اختبار',
            'view_any_exam_result' => 'عرض كل نتائج الاختبارات',
            'view_exam_result' => 'عرض نتيجة اختبار',
            'create_exam_result' => 'إنشاء نتيجة اختبار',
            'update_exam_result' => 'تحديث نتيجة اختبار',
            'delete_exam_result' => 'حذف نتيجة اختبار',
            'import_exam_results' => 'استيراد نتائج الاختبارات',
            'export_exam_results' => 'تصدير نتائج الاختبارات',
        ],

        // ========== مجموعة الدرجات والتقييم ==========
        'Grades & Marks' => [
            'view_any_grade_scale' => 'عرض كل سلالم التقديرات',
            'view_grade_scale' => 'عرض سلم تقديرات',
            'create_grade_scale' => 'إنشاء سلم تقديرات',
            'update_grade_scale' => 'تحديث سلم تقديرات',
            'delete_grade_scale' => 'حذف سلم تقديرات',
            'view_any_monthly_grade' => 'عرض كل الدرجات الشهرية',
            'view_monthly_grade' => 'عرض درجة شهرية',
            'create_monthly_grade' => 'إنشاء درجة شهرية',
            'update_monthly_grade' => 'تحديث درجة شهرية',
            'delete_monthly_grade' => 'حذف درجة شهرية',
            'view_any_semester_grade' => 'عرض كل درجات الفصل الدراسي',
            'view_semester_grade' => 'عرض درجة فصل دراسي',
            'create_semester_grade' => 'إنشاء درجة فصل دراسي',
            'update_semester_grade' => 'تحديث درجة فصل دراسي',
            'delete_semester_grade' => 'حذف درجة فصل دراسي',
            'view_any_final_grade' => 'عرض كل الدرجات النهائية',
            'view_final_grade' => 'عرض درجة نهائية',
            'create_final_grade' => 'إنشاء درجة نهائية',
            'update_final_grade' => 'تحديث درجة نهائية',
            'delete_final_grade' => 'حذف درجة نهائية',
        ],

        // ========== مجموعة الحضور ==========
        'Attendance Management' => [
            'view_any_attendance' => 'عرض كل الحضور',
            'view_attendance' => 'عرض الحضور',
            'take_attendance' => 'تسجيل الحضور',
            'update_attendance' => 'تحديث الحضور',
            'export_attendance' => 'تصدير تقرير الحضور',
        ],

        // ========== مجموعة الواجبات المنزلية ==========
        'Homework Management' => [
            'view_any_homework' => 'عرض كل الواجبات المنزلية',
            'view_homework' => 'عرض واجب منزلي',
            'create_homework' => 'إنشاء واجب منزلي',
            'update_homework' => 'تحديث واجب منزلي',
            'delete_homework' => 'حذف واجب منزلي',
            'view_any_homework_submission' => 'عرض كل تسليمات الواجبات',
            'view_homework_submission' => 'عرض تسليم واجب',
            'grade_homework_submission' => 'تقييم تسليم واجب',
            'submit_homework' => 'تسليم واجب منزلي',
        ],

        // ========== مجموعة الجداول الدراسية ==========
        'Schedule Management' => [
            'view_any_schedule' => 'عرض كل الجداول',
            'view_schedule' => 'عرض جدول',
            'create_schedule' => 'إنشاء جدول',
            'update_schedule' => 'تحديث جدول',
            'delete_schedule' => 'حذف جدول',
        ],

        // ========== مجموعة التواصل ==========
        'Communication' => [
            'send_message' => 'إرسال رسالة',
            'view_message' => 'عرض رسالة',
            'send_notification' => 'إرسال إشعار',
            'view_notification' => 'عرض إشعار',
            'manage_announcements' => 'إدارة الإعلانات',
        ],

        // ========== مجموعة تقييم السلوك ==========
        'Behavior Evaluation' => [
            'view_any_behavior_evaluation' => 'عرض كل تقييمات السلوك',
            'view_behavior_evaluation' => 'عرض تقييم سلوك',
            'create_behavior_evaluation' => 'إنشاء تقييم سلوك',
            'update_behavior_evaluation' => 'تحديث تقييم سلوك',
            'delete_behavior_evaluation' => 'حذف تقييم سلوك',
        ],

        // ========== مجموعة التقارير ==========
        'Reports' => [
            'view_reports' => 'عرض التقارير',
            'generate_student_report' => 'إنشاء تقرير طالب',
            'generate_financial_report' => 'إنشاء تقرير مالي',
            'generate_attendance_report' => 'إنشاء تقرير حضور',
            'generate_exam_report' => 'إنشاء تقرير امتحانات',
            'generate_teacher_report' => 'إنشاء تقرير معلمين',
            'export_reports' => 'تصدير التقارير',
        ],

        // ========== مجموعة الإدارة المالية ==========
        'Financial Management' => [
            'view_any_invoice' => 'عرض كل الفواتير',
            'view_invoice' => 'عرض فاتورة',
            'create_invoice' => 'إنشاء فاتورة',
            'update_invoice' => 'تحديث فاتورة',
            'delete_invoice' => 'حذف فاتورة',
            'manage_fees' => 'إدارة الرسوم الدراسية',
            'view_transactions' => 'عرض المعاملات المالية',
            'process_payment' => 'معالجة الدفع',
            'refund_payment' => 'استرداد دفعة',
        ],

        // ========== مجموعة لوحات التحكم ==========
        'Dashboards' => [
            'view_admin_dashboard' => 'عرض لوحة تحكم المدير',
            'view_teacher_dashboard' => 'عرض لوحة تحكم المعلم',
            'view_student_dashboard' => 'عرض لوحة تحكم الطالب',
            'view_parent_dashboard' => 'عرض لوحة تحكم ولي الأمر',
        ],

        // ========== مجموعة الإعدادات ==========
        'Settings' => [
            'manage_general_settings' => 'إدارة الإعدادات العامة',
            'manage_school_settings' => 'إدارة إعدادات المدرسة',
            'manage_system_settings' => 'إدارة إعدادات النظام',
        ],

        // ========== مجموعة إدارة النظام ==========
        'System Management' => [
            'view_system_logs' => 'عرض سجلات النظام',
            'perform_backup' => 'إجراء نسخ احتياطي',
            'restore_from_backup' => 'استعادة من نسخة احتياطية',
            'access_api' => 'الوصول إلى API',
            'export_data' => 'تصدير البيانات',
            'import_data' => 'استيراد البيانات',
            'clear_cache' => 'مسح ذاكرة التخزين المؤقت',
        ],

        // ========== مجموعة مكتبة المدرسة ==========
        'Library Management' => [
            'view_any_book' => 'عرض كل الكتب',
            'view_book' => 'عرض كتاب',
            'create_book' => 'إضافة كتاب جديد',
            'update_book' => 'تحديث معلومات كتاب',
            'delete_book' => 'حذف كتاب',
            'issue_book' => 'إعارة كتاب',
            'return_book' => 'استلام كتاب مُعار',
        ],

        // ========== مجموعة النقل المدرسي ==========
        'Transportation Management' => [
            'view_any_transport_route' => 'عرض كل خطوط النقل',
            'manage_transport_routes' => 'إدارة خطوط النقل',
            'view_any_vehicle' => 'عرض كل المركبات',
            'manage_vehicles' => 'إدارة المركبات',
            'assign_student_to_route' => 'تسجيل طالب في خط نقل',
        ],

        // ========== مجموعة الفعاليات ==========
        'Event Management' => [
            'view_any_event' => 'عرض كل الفعاليات',
            'create_event' => 'إنشاء فعالية',
            'update_event' => 'تحديث فعالية',
            'delete_event' => 'حذف فعالية',
            'view_school_calendar' => 'عرض تقويم المدرسة',
        ],
    ];

    /**
     * قائمة الصلاحيات الخاصة بالطلاب (يتم منحها لطلاب فقط)
     */
    protected $studentPermissions = [
        'view_student_dashboard',
        'view_schedule',
        'submit_homework',
        'view_message',
        'view_notification',
        'view_own_grades',      // سيتم إضافتها بشكل منفصل
        'view_own_attendance',  // سيتم إضافتها بشكل منفصل
        'view_own_invoice',     // سيتم إضافتها بشكل منفصل
    ];

    /**
     * قائمة الصلاحيات الخاصة بولي الأمر (يتم منحها لأولياء الأمور)
     */
    protected $guardianPermissions = [
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
    ];

    /**
     * قائمة الصلاحيات الخاصة بالمعلم (يتم منحها للمعلمين)
     */
    protected $teacherPermissions = [
        'view_teacher_dashboard',
        'view_any_student', 'view_student',
        'view_any_course', 'view_course',
        'take_attendance', 'update_attendance', 'export_attendance',
        'view_any_exam', 'create_exam', 'update_exam',
        'view_any_exam_result', 'create_exam_result', 'update_exam_result', 'import_exam_results',
        'view_any_homework', 'create_homework', 'update_homework',
        'grade_homework_submission',
        'view_any_monthly_grade', 'create_monthly_grade', 'update_monthly_grade',
        'view_any_semester_grade', 'create_semester_grade', 'update_semester_grade',
        'view_any_final_grade', 'create_final_grade', 'update_final_grade',
        'view_any_behavior_evaluation', 'create_behavior_evaluation', 'update_behavior_evaluation',
        'send_message', 'view_message', 'send_notification',
        'view_schedule',
    ];

    /**
     * قائمة الصلاحيات الخاصة بالمدير (يتم منحها لمدير المدرسة)
     */
    protected $adminPermissions = [
        // إدارة المستخدمين
        'view_any_user', 'view_user', 'create_user', 'update_user', 'delete_user',
        
        // إدارة الطلاب والمعلمين وأولياء الأمور
        'view_any_student', 'view_student', 'create_student', 'update_student', 'delete_student', 'import_students', 'export_students',
        'view_any_teacher', 'view_teacher', 'create_teacher', 'update_teacher', 'delete_teacher', 'import_teachers', 'export_teachers',
        'view_any_guardian', 'view_guardian', 'create_guardian', 'update_guardian', 'delete_guardian',
        
        // الإدارة الأكاديمية
        'view_any_academic_year', 'create_academic_year', 'update_academic_year', 'delete_academic_year',
        'view_any_semester', 'create_semester', 'update_semester', 'delete_semester',
        'view_any_school_stage', 'create_school_stage', 'update_school_stage', 'delete_school_stage',
        'view_any_grade', 'create_grade', 'update_grade', 'delete_grade',
        'view_any_classroom', 'create_classroom', 'update_classroom', 'delete_classroom',
        
        // المواد
        'view_any_course', 'create_course', 'update_course', 'delete_course', 'assign_teacher_to_course',
        
        // الامتحانات والدرجات
        'view_any_exam', 'create_exam', 'update_exam', 'delete_exam',
        'view_any_exam_type', 'create_exam_type', 'update_exam_type', 'delete_exam_type',
        'view_any_exam_result', 'create_exam_result', 'update_exam_result', 'delete_exam_result',
        'view_any_grade_scale', 'create_grade_scale', 'update_grade_scale', 'delete_grade_scale',
        'view_any_monthly_grade', 'create_monthly_grade', 'update_monthly_grade', 'delete_monthly_grade',
        'view_any_semester_grade', 'create_semester_grade', 'update_semester_grade', 'delete_semester_grade',
        'view_any_final_grade', 'create_final_grade', 'update_final_grade', 'delete_final_grade',
        
        // الحضور والواجبات
        'view_any_attendance', 'take_attendance', 'update_attendance', 'export_attendance',
        'view_any_homework', 'create_homework', 'update_homework', 'delete_homework',
        'view_any_homework_submission', 'grade_homework_submission',
        
        // الجداول
        'view_any_schedule', 'create_schedule', 'update_schedule', 'delete_schedule',
        
        // التواصل
        'send_message', 'view_message', 'send_notification', 'view_notification', 'manage_announcements',
        
        // تقييم السلوك
        'view_any_behavior_evaluation', 'create_behavior_evaluation', 'update_behavior_evaluation', 'delete_behavior_evaluation',
        
        // التقارير
        'view_reports', 'generate_student_report', 'generate_financial_report', 'generate_attendance_report',
        'generate_exam_report', 'generate_teacher_report', 'export_reports',
        
        // المالية
        'view_any_invoice', 'create_invoice', 'update_invoice', 'delete_invoice', 'manage_fees', 'view_transactions',
        
        // لوحة التحكم
        'view_admin_dashboard',
        
        // الإعدادات
        'manage_general_settings', 'manage_school_settings',
        
        // النظام
        'view_system_logs', 'perform_backup', 'export_data', 'import_data', 'clear_cache',
        
        // المكتبة
        'view_any_book', 'create_book', 'update_book', 'delete_book', 'issue_book', 'return_book',
        
        // النقل
        'view_any_transport_route', 'manage_transport_routes', 'view_any_vehicle', 'manage_vehicles', 'assign_student_to_route',
        
        // الفعاليات
        'view_any_event', 'create_event', 'update_event', 'delete_event', 'view_school_calendar',
    ];

    /**
     * الصلاحيات الإضافية التي يحتاجها الطالب
     * (سيتم إنشاؤها إذا لم تكن موجودة)
     */
    protected $extraPermissions = [
        'view_own_grades' => 'عرض درجاته الخاصة',
        'view_own_attendance' => 'عرض حضوره الخاص',
        'view_own_invoice' => 'عرض فاتورته الخاصة',
        'view_children_grades' => 'عرض درجات الأبناء',
        'view_children_attendance' => 'عرض حضور الأبناء',
        'view_children_homework' => 'عرض واجبات الأبناء',
        'view_children_invoices' => 'عرض فواتير الأبناء',
    ];

    public function run()
    {
        // مسح الكاش الموجود
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ========== 1. إنشاء جميع الصلاحيات ==========
        $this->createAllPermissions();

        // ========== 2. إنشاء الأدوار ومنح الصلاحيات ==========
        $this->createRolesAndAssignPermissions();

        // ========== 3. إنشاء المستخدمين التجريبيين (اختياري) ==========
        $this->createDemoUsers();
    }

    /**
     * إنشاء جميع الصلاحيات من المجموعات
     */
    protected function createAllPermissions()
    {
        $allPermissions = [];

        // جمع جميع الصلاحيات من المجموعات
        foreach ($this->permissionsGroups as $group => $permissions) {
            foreach ($permissions as $name => $label) {
                $allPermissions[$name] = $label;
            }
        }

        // إضافة الصلاحيات الإضافية
        foreach ($this->extraPermissions as $name => $label) {
            $allPermissions[$name] = $label;
        }

        // إنشاء كل صلاحية
        foreach ($allPermissions as $name => $label) {
            Permission::updateOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
                ['label' => $label]
            );
        }

        $this->command->info('✅ تم إنشاء ' . count($allPermissions) . ' صلاحية بنجاح');
    }

    /**
     * إنشاء الأدوار ومنح الصلاحيات
     */
    protected function createRolesAndAssignPermissions()
    {
        // 1. دور super-admin (مدير النظام) - جميع الصلاحيات
        $superAdmin = Role::updateOrCreate(
            ['name' => 'super-admin', 'guard_name' => 'web'],
            ['label' => 'مدير النظام (صلاحيات كاملة)']
        );
        $superAdmin->syncPermissions(Permission::all());
        $this->command->info('✅ تم إنشاء دور super-admin مع ' . Permission::count() . ' صلاحية');

        // 2. دور admin (مدير المدرسة)
        $admin = Role::updateOrCreate(
            ['name' => 'admin', 'guard_name' => 'web'],
            ['label' => 'مدير المدرسة']
        );
        
        // الحصول على الصلاحيات المخصصة للمدير
        $adminPermissionsList = $this->adminPermissions;
        $adminPermissions = Permission::whereIn('name', $adminPermissionsList)->get();
        $admin->syncPermissions($adminPermissions);
        $this->command->info('✅ تم إنشاء دور admin مع ' . $adminPermissions->count() . ' صلاحية');

        // 3. دور teacher (معلم)
        $teacher = Role::updateOrCreate(
            ['name' => 'teacher', 'guard_name' => 'web'],
            ['label' => 'معلم']
        );
        
        $teacherPermissions = Permission::whereIn('name', $this->teacherPermissions)->get();
        $teacher->syncPermissions($teacherPermissions);
        $this->command->info('✅ تم إنشاء دور teacher مع ' . $teacherPermissions->count() . ' صلاحية');

        // 4. دور student (طالب)
        $student = Role::updateOrCreate(
            ['name' => 'student', 'guard_name' => 'web'],
            ['label' => 'طالب']
        );
        
        $studentPermissionsList = $this->studentPermissions;
        $studentPermissions = Permission::whereIn('name', $studentPermissionsList)->get();
        $student->syncPermissions($studentPermissions);
        $this->command->info('✅ تم إنشاء دور student مع ' . $studentPermissions->count() . ' صلاحية');

        // 5. دور guardian (ولي أمر)
        $guardian = Role::updateOrCreate(
            ['name' => 'guardian', 'guard_name' => 'web'],
            ['label' => 'ولي أمر']
        );
        
        $guardianPermissions = Permission::whereIn('name', $this->guardianPermissions)->get();
        $guardian->syncPermissions($guardianPermissions);
        $this->command->info('✅ تم إنشاء دور guardian مع ' . $guardianPermissions->count() . ' صلاحية');
    }

    /**
     * إنشاء مستخدمين تجريبيين للاختبار
     * (يمكنك تعطيل هذه الدالة إذا كنت لا تريد مستخدمين تجريبيين)
     */
    protected function createDemoUsers()
    {
        // استخدام DB transaction لضمان عدم وجود مشاكل
        DB::transaction(function () {
            // مستخدم Super Admin
            $superAdminUser = \App\Models\User::updateOrCreate(
                ['email' => 'superadmin@school.com'],
                [
                    'username' => 'superadmin',
                    'phone' => '01000000001',
                    'password' => bcrypt('password'),
                    'is_active' => true,
                ]
            );
            $superAdminUser->assignRole('super-admin');
            $this->command->info('👤 مستخدم Super Admin: superadmin@school.com / password');

            // مستخدم Admin (مدير مدرسة)
            $adminUser = \App\Models\User::updateOrCreate(
                ['email' => 'admin@school.com'],
                [
                    'username' => 'admin',
                    'phone' => '01000000002',
                    'password' => bcrypt('password'),
                    'is_active' => true,
                ]
            );
            $adminUser->assignRole('admin');
            $this->command->info('👤 مستخدم Admin: admin@school.com / password');

            // مستخدم Teacher (معلم)
            $teacherUser = \App\Models\User::updateOrCreate(
                ['email' => 'teacher@school.com'],
                [
                    'username' => 'teacher',
                    'phone' => '01000000003',
                    'password' => bcrypt('password'),
                    'is_active' => true,
                ]
            );
            $teacherUser->assignRole('teacher');
            $this->command->info('👤 مستخدم Teacher: teacher@school.com / password');

            // مستخدم Student (طالب) - ستحتاج إلى إنشاء طالب مرتبط
            $studentUser = \App\Models\User::updateOrCreate(
                ['email' => 'student@school.com'],
                [
                    'username' => 'student',
                    'phone' => '01000000004',
                    'password' => bcrypt('password'),
                    'is_active' => true,
                ]
            );
            $studentUser->assignRole('student');
            $this->command->info('👤 مستخدم Student: student@school.com / password');

            // مستخدم Guardian (ولي أمر)
            $guardianUser = \App\Models\User::updateOrCreate(
                ['email' => 'guardian@school.com'],
                [
                    'username' => 'guardian',
                    'phone' => '01000000005',
                    'password' => bcrypt('password'),
                    'is_active' => true,
                ]
            );
            $guardianUser->assignRole('guardian');
            $this->command->info('👤 مستخدم Guardian: guardian@school.com / password');
        });

        $this->command->info('✅ تم إنشاء المستخدمين التجريبيين بنجاح');
    }
}