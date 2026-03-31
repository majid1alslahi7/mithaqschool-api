<?php

namespace App\Policies;

use App\Models\MonthlyGrade;
use App\Models\User;

class MonthlyGradePolicy
{
    /**
     * عرض قائمة الدرجات الشهرية
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_monthly_grade');
    }

    /**
     * عرض درجة شهرية محددة
     */
    public function view(User $user, MonthlyGrade $monthlyGrade)
    {
        // المدير: يرى كل الدرجات
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // المعلم: يرى درجات المواد التي يدرسها فقط
        if ($user->teacher) {
            return $monthlyGrade->course->teacher_id === $user->teacher->id;
        }

        // الطالب: يرى درجاته فقط
        if ($user->student) {
            return $monthlyGrade->student_number === $user->student->enrollment_number;
        }

        // ولي الأمر: يرى درجات أبنائه فقط
        if ($user->guardian) {
            $childrenNumbers = $user->guardian->students->pluck('enrollment_number')->toArray();
            return in_array($monthlyGrade->student_number, $childrenNumbers);
        }

        return false;
    }

    /**
     * إنشاء درجة شهرية جديدة
     */
    public function create(User $user)
    {
        // المعلم يمكنه إدخال الدرجات
        return $user->hasRole('teacher') && $user->can('create_monthly_grade');
    }

    /**
     * تحديث درجة شهرية
     */
    public function update(User $user, MonthlyGrade $monthlyGrade)
    {
        // المعلم يمكنه تحديث درجات المواد التي يدرسها فقط
        return $user->teacher && 
               $user->teacher->id === $monthlyGrade->course->teacher_id && 
               $user->can('update_monthly_grade');
    }

    /**
     * حذف درجة شهرية
     */
    public function delete(User $user, MonthlyGrade $monthlyGrade)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('delete_monthly_grade');
    }
}