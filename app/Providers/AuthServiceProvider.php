<?php

namespace App\Providers;

use App\Models\AcademicYear;
use App\Models\Adjustment;
use App\Models\Attendance;
use App\Models\BehaviorEvaluation;
use App\Models\ClassFee;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\CourseClassroomTeacher;
use App\Models\DailyAttendance;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\ExamType;
use App\Models\FeeType;
use App\Models\FinalyGrades;
use App\Models\Grade;
use App\Models\GradesScale;
use App\Models\Guardian;
use App\Models\Homework;
use App\Models\HomeworkSubmission;
use App\Models\InvoiceItem;
use App\Models\Message;
use App\Models\MonthlyGrade;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\School;
use App\Models\SchoolStage;
use App\Models\Semester;
use App\Models\SemesterGrade;
use App\Models\Session;
use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\StudentInvoice;
use App\Models\Teacher;
use App\Models\User;
use App\Policies\AcademicYearPolicy;
use App\Policies\AdjustmentPolicy;
use App\Policies\AttendancePolicy;
use App\Policies\BehaviorEvaluationPolicy;
use App\Policies\ClassFeePolicy;
use App\Policies\ClassroomPolicy;
use App\Policies\CourseClassroomTeacherPolicy;
use App\Policies\CoursePolicy;
use App\Policies\DailyAttendancePolicy;
use App\Policies\ExamPolicy;
use App\Policies\ExamResultPolicy;
use App\Policies\ExamTypePolicy;
use App\Policies\FeeTypePolicy;
use App\Policies\FinalGradePolicy;
use App\Policies\GradePolicy;
use App\Policies\GradesScalePolicy;
use App\Policies\GuardianPolicy;
use App\Policies\HomeworkPolicy;
use App\Policies\HomeworkSubmissionPolicy;
use App\Policies\InvoiceItemPolicy;
use App\Policies\MessagePolicy;
use App\Policies\MonthlyGradePolicy;
use App\Policies\NotificationPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use App\Policies\SchedulePolicy;
use App\Policies\SchoolPolicy;
use App\Policies\SchoolStagePolicy;
use App\Policies\SemesterGradePolicy;
use App\Policies\SemesterPolicy;
use App\Policies\SessionPolicy;
use App\Policies\StudentGradePolicy;
use App\Policies\StudentInvoicePolicy;
use App\Policies\StudentPolicy;
use App\Policies\TeacherPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // الأساسية
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        Permission::class => PermissionPolicy::class,
        
        // الطلاب والمعلمون وأولياء الأمور
        Student::class => StudentPolicy::class,
        Teacher::class => TeacherPolicy::class,
        Guardian::class => GuardianPolicy::class,
        
        // الأكاديمي
        AcademicYear::class => AcademicYearPolicy::class,
        Semester::class => SemesterPolicy::class,
        SchoolStage::class => SchoolStagePolicy::class,
        Grade::class => GradePolicy::class,
        Classroom::class => ClassroomPolicy::class,
        Course::class => CoursePolicy::class,
        CourseClassroomTeacher::class => CourseClassroomTeacherPolicy::class,
        
        // الامتحانات والدرجات
        Exam::class => ExamPolicy::class,
        ExamType::class => ExamTypePolicy::class,
        ExamResult::class => ExamResultPolicy::class,
        MonthlyGrade::class => MonthlyGradePolicy::class,
        SemesterGrade::class => SemesterGradePolicy::class,
        FinalyGrades::class => FinalGradePolicy::class,
        StudentGrade::class => StudentGradePolicy::class,
        GradesScale::class => GradesScalePolicy::class,
        
        // الواجبات
        Homework::class => HomeworkPolicy::class,
        HomeworkSubmission::class => HomeworkSubmissionPolicy::class,
        
        // الحضور والجداول
        Attendance::class => AttendancePolicy::class,
        DailyAttendance::class => DailyAttendancePolicy::class,
        Schedule::class => SchedulePolicy::class,
        
        // المالية
        StudentInvoice::class => StudentInvoicePolicy::class,
        InvoiceItem::class => InvoiceItemPolicy::class,
        Payment::class => PaymentPolicy::class,
        Adjustment::class => AdjustmentPolicy::class,
        FeeType::class => FeeTypePolicy::class,
        ClassFee::class => ClassFeePolicy::class,
        
        // التواصل
        Message::class => MessagePolicy::class,
        Notification::class => NotificationPolicy::class,
        
        // تقييم السلوك
        BehaviorEvaluation::class => BehaviorEvaluationPolicy::class,
        
        // إعدادات المدرسة
        School::class => SchoolPolicy::class,
        
        // الجلسات
        Session::class => SessionPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}