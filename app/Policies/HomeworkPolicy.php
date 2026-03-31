<?php

namespace App\Policies;

use App\Models\Homework;
use App\Models\User;

class HomeworkPolicy
{
    /**
     * عرض قائمة الواجبات
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_homework');
    }

    /**
     * عرض واجب محدد
     */
    public function view(User $user, Homework $homework)
    {
        // المدير: يرى كل الواجبات
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // المعلم: يرى واجباته فقط
        if ($user->teacher) {
            return $homework->teacher_id === $user->teacher->id;
        }

        // الطالب: يرى واجباته فقط
        if ($user->student) {
            return $homework->student_id === $user->student->id;
        }

        // ولي الأمر: يرى واجبات أبنائه فقط
        if ($user->guardian) {
            $childrenIds = $user->guardian->students->pluck('id')->toArray();
            return in_array($homework->student_id, $childrenIds);
        }

        return false;
    }

    /**
     * إنشاء واجب جديد
     */
    public function create(User $user)
    {
        // المعلم يمكنه إنشاء واجبات
        return $user->hasRole('teacher') && $user->can('create_homework');
    }

    /**
     * تعديل واجب موجود
     */
    public function update(User $user, Homework $homework)
    {
        // المعلم يمكنه تعديل واجباته فقط
        return $user->teacher && 
               $user->teacher->id === $homework->teacher_id && 
               $user->can('update_homework');
    }

    /**
     * حذف واجب
     */
    public function delete(User $user, Homework $homework)
    {
        // فقط المدير يمكنه الحذف
        return $user->hasRole(['super-admin', 'admin']) && $user->can('delete_homework');
    }

    /**
     * تسليم واجب (خاص بالطالب)
     * 
     * هذه دالة إضافية لتحقق من صلاحية تسليم الواجب
     */
    public function submit(User $user, Homework $homework)
    {
        // الطالب يمكنه تسليم الواجب المخصص له فقط
        return $user->student && 
               $user->student->id === $homework->student_id && 
               $user->can('submit_homework');
    }
}