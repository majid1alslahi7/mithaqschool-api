<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    /**
     * عرض قائمة المواد
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_course');
    }

    /**
     * عرض مادة محددة
     */
    public function view(User $user, Course $course)
    {
        // المدير: يرى كل المواد
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // المعلم: يرى المواد التي يدرسها فقط
        if ($user->teacher) {
            return $course->teacher_id === $user->teacher->id;
        }

        // الطالب: يرى المواد المسجل فيها فقط
        if ($user->student) {
            return $course->students()->where('student_id', $user->student->id)->exists();
        }

        return false;
    }

    /**
     * إنشاء مادة جديدة
     */
    public function create(User $user)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('create_course');
    }

    /**
     * تحديث مادة
     */
    public function update(User $user, Course $course)
    {
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }
        
        // المعلم يمكنه تحديث المواد التي يدرسها فقط (مع صلاحية)
        return $user->teacher && 
               $user->teacher->id === $course->teacher_id && 
               $user->can('update_course');
    }

    /**
     * حذف مادة
     */
    public function delete(User $user, Course $course)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('delete_course');
    }

    /**
     * تعيين معلم للمادة
     */
    public function assignTeacher(User $user, Course $course)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('assign_teacher_to_course');
    }
}