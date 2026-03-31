<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;

class AttendancePolicy
{
    /**
     * عرض قائمة الحضور
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_attendance');
    }

    /**
     * عرض تسجيل حضور محدد
     */
    public function view(User $user, Attendance $attendance)
    {
        // المدير: يرى كل الحضور
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // المعلم: يرى حضور المواد التي يدرسها فقط
        if ($user->teacher) {
            return $attendance->course->teacher_id === $user->teacher->id;
        }

        // الطالب: يرى حضوره فقط
        if ($user->student) {
            return $attendance->student_id === $user->student->id;
        }

        // ولي الأمر: يرى حضور أبنائه فقط
        if ($user->guardian) {
            $childrenIds = $user->guardian->students->pluck('id')->toArray();
            return in_array($attendance->student_id, $childrenIds);
        }

        return false;
    }

    /**
     * تسجيل حضور جديد (المعلم يسجل حضور طلابه)
     */
    public function take(User $user)
    {
        // المعلم يمكنه تسجيل الحضور
        return $user->hasRole('teacher') && $user->can('take_attendance');
    }

    /**
     * تحديث تسجيل حضور
     */
    public function update(User $user, Attendance $attendance)
    {
        // المعلم يمكنه تحديث حضور المواد التي يدرسها فقط
        return $user->teacher && 
               $user->teacher->id === $attendance->course->teacher_id && 
               $user->can('update_attendance');
    }
}