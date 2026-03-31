<?php

namespace App\Policies;

use App\Models\CourseClassroomTeacher;
use App\Models\User;

class CourseClassroomTeacherPolicy
{
    /**
     * عرض قائمة العلاقات
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_course');
    }

    /**
     * عرض علاقة محددة
     */
    public function view(User $user, CourseClassroomTeacher $relation)
    {
        // المدير: يرى كل العلاقات
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // المعلم: يرى علاقاته فقط
        if ($user->teacher) {
            return $relation->teacher_id === $user->teacher->id;
        }

        return false;
    }

    /**
     * إنشاء علاقة جديدة (تعيين معلم لمادة في فصل)
     */
    public function create(User $user)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('assign_teacher_to_course');
    }

    /**
     * تحديث علاقة
     */
    public function update(User $user, CourseClassroomTeacher $relation)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('assign_teacher_to_course');
    }

    /**
     * حذف علاقة
     */
    public function delete(User $user, CourseClassroomTeacher $relation)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('assign_teacher_to_course');
    }
}