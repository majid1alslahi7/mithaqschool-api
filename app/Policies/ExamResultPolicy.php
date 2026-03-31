<?php

namespace App\Policies;

use App\Models\ExamResult;
use App\Models\User;

class ExamResultPolicy
{
    /**
     * عرض قائمة النتائج
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_exam_result');
    }

    /**
     * عرض نتيجة محددة
     */
    public function view(User $user, ExamResult $examResult)
    {
        // المدير: يرى كل النتائج
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // المعلم: يرى نتائج الامتحانات التي قام بتدريسها
        if ($user->teacher) {
            return $examResult->exam->teacher_id === $user->teacher->id;
        }

        // الطالب: يرى نتيجته فقط
        if ($user->student) {
            return $examResult->student_id === $user->student->id;
        }

        // ولي الأمر: يرى نتائج أبنائه فقط
        if ($user->guardian) {
            $childrenIds = $user->guardian->students->pluck('id')->toArray();
            return in_array($examResult->student_id, $childrenIds);
        }

        return false;
    }

    /**
     * إنشاء نتيجة جديدة (تسجيل درجة طالب)
     */
    public function create(User $user)
    {
        // المعلم يمكنه تسجيل نتائج
        return $user->hasRole('teacher') && $user->can('create_exam_result');
    }

    /**
     * تعديل نتيجة موجودة
     */
    public function update(User $user, ExamResult $examResult)
    {
        // المعلم يمكنه تعديل نتائج امتحاناته فقط
        return $user->teacher && 
               $user->teacher->id === $examResult->exam->teacher_id && 
               $user->can('update_exam_result');
    }

    /**
     * حذف نتيجة
     */
    public function delete(User $user, ExamResult $examResult)
    {
        // فقط المدير يمكنه الحذف
        return $user->hasRole(['super-admin', 'admin']) && $user->can('delete_exam_result');
    }
}