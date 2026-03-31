<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\User;

class SchedulePolicy
{
    /**
     * عرض قائمة الجداول
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_schedule');
    }

    /**
     * عرض جدول محدد
     */
    public function view(User $user, Schedule $schedule)
    {
        // المدير: يرى كل الجداول
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // المعلم: يرى جداول المواد التي يدرسها فقط
        if ($user->teacher) {
            return $schedule->teacher_id === $user->teacher->id;
        }

        // الطالب: يرى جدول المواد المسجل فيها فقط
        if ($user->student) {
            return $schedule->course->students()->where('student_id', $user->student->id)->exists();
        }

        return false;
    }

    /**
     * إنشاء جدول جديد
     */
    public function create(User $user)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('create_schedule');
    }

    /**
     * تحديث جدول
     */
    public function update(User $user, Schedule $schedule)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('update_schedule');
    }

    /**
     * حذف جدول
     */
    public function delete(User $user, Schedule $schedule)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('delete_schedule');
    }
}