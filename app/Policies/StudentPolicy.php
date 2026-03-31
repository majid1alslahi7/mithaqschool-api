<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;

class StudentPolicy
{
    /**
     * عرض قائمة الطلاب
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_student');
    }

    /**
     * عرض طالب محدد
     */
    public function view(User $user, Student $student)
    {
        // المدير: يرى كل الطلاب
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // المعلم: يرى طلاب المواد التي يدرسها فقط
        if ($user->teacher) {
            // يفترض وجود علاقة بين المعلم والمواد، والمواد والطلاب
            $teacherCourseIds = $user->teacher->courses->pluck('id');
            return $student->courses()->whereIn('course_id', $teacherCourseIds)->exists();
        }

        // الطالب: يرى نفسه فقط
        if ($user->student) {
            return $student->id === $user->student->id;
        }

        // ولي الأمر: يرى أبناءه فقط
        if ($user->guardian) {
            return $student->parent_id === $user->guardian->id;
        }

        return false;
    }

    /**
     * إنشاء طالب جديد
     */
    public function create(User $user)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('create_student');
    }

    /**
     * تحديث بيانات طالب
     */
    public function update(User $user, Student $student)
    {
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }
        
        // المعلم لا يمكنه تحديث بيانات الطالب (فقط المدير)
        return false;
    }

    /**
     * حذف طالب
     */
    public function delete(User $user, Student $student)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('delete_student');
    }
}