<?php

namespace App\Policies;

use App\Models\Exam;
use App\Models\User;

class ExamPolicy
{
    /**
     * تحديد من يمكنه عرض قائمة الاختبارات (index)
     * 
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        // أي مستخدم لديه صلاحية view_any_exam يمكنه عرض القائمة
        return $user->can('view_any_exam');
    }

    /**
     * تحديد من يمكنه عرض اختبار معين (show)
     * 
     * @param User $user
     * @param Exam $exam
     * @return bool
     */
    public function view(User $user, Exam $exam)
    {
        // حالة 1: المدير أو السوبر أدمن يمكنهم رؤية أي اختبار
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // حالة 2: المعلم يمكنه رؤية الاختبار فقط إذا كان هو منشئه
        if ($user->teacher && $user->teacher->id === $exam->teacher_id) {
            return true;
        }

        // حالة 3: الطالب يمكنه رؤية الاختبار فقط إذا كان مسجلاً في المادة
        if ($user->student) {
            // التحقق من وجود الطالب في جدول course_student (إذا كان لديك)
            // هذا يفترض وجود علاقة many-to-many بين Student و Course
            return $exam->course->students()->where('student_id', $user->student->id)->exists();
        }

        // جميع الحالات الأخرى ممنوعة
        return false;
    }

    /**
     * تحديد من يمكنه إنشاء اختبار جديد (create)
     * 
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        // المعلم الذي لديه صلاحية create_exam يمكنه إنشاء اختبار
        return $user->hasRole('teacher') && $user->can('create_exam');
    }

    /**
     * تحديد من يمكنه تعديل اختبار موجود (update)
     * 
     * @param User $user
     * @param Exam $exam
     * @return bool
     */
    public function update(User $user, Exam $exam)
    {
        // حالة 1: المدير يمكنه تعديل أي اختبار
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // حالة 2: المعلم يمكنه تعديل اختباره فقط
        return $user->teacher && 
               $user->teacher->id === $exam->teacher_id && 
               $user->can('update_exam');
    }

    /**
     * تحديد من يمكنه حذف اختبار (delete)
     * 
     * @param User $user
     * @param Exam $exam
     * @return bool
     */
    public function delete(User $user, Exam $exam)
    {
        // فقط المدير أو السوبر أدمن يمكنهم الحذف
        return $user->hasRole(['super-admin', 'admin']) && $user->can('delete_exam');
    }
}