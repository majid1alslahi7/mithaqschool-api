<?php

namespace App\Policies;

use App\Models\DailyAttendance;
use App\Models\User;

class DailyAttendancePolicy
{
    /**
     * عرض قائمة الحضور اليومي
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_attendance');
    }

    /**
     * عرض تسجيل حضور محدد
     */
    public function view(User $user, DailyAttendance $dailyAttendance)
    {
        // المدير: يرى كل الحضور
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // المعلم: يرى حضور الطلاب الذين يدرسهم
        if ($user->teacher) {
            $teacherStudentIds = $user->teacher->courses()
                ->with('students')
                ->get()
                ->pluck('students')
                ->flatten()
                ->pluck('id')
                ->unique()
                ->toArray();
            return in_array($dailyAttendance->student_id, $teacherStudentIds);
        }

        // الطالب: يرى حضوره فقط
        if ($user->student) {
            return $dailyAttendance->student_id === $user->student->id;
        }

        // ولي الأمر: يرى حضور أبنائه فقط
        if ($user->guardian) {
            $childrenIds = $user->guardian->students->pluck('id')->toArray();
            return in_array($dailyAttendance->student_id, $childrenIds);
        }

        return false;
    }

    /**
     * تسجيل حضور جديد
     */
    public function take(User $user)
    {
        return $user->hasRole('teacher') && $user->can('take_attendance');
    }

    /**
     * تحديث تسجيل حضور
     */
    public function update(User $user, DailyAttendance $dailyAttendance)
    {
        return $user->hasRole('teacher') && $user->can('update_attendance');
    }
}