<?php

namespace App\Policies;

use App\Models\FinalyGrades;
use App\Models\User;

class FinalGradePolicy
{
    /**
     * عرض قائمة الدرجات النهائية
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_final_grade');
    }

    /**
     * عرض درجة نهائية محددة
     */
    public function view(User $user, FinalyGrades $finalGrade)
    {
        // المدير: يرى كل الدرجات
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // المعلم: يرى درجات المواد التي يدرسها فقط
        if ($user->teacher) {
            return $finalGrade->course->teacher_id === $user->teacher->id;
        }

        // الطالب: يرى درجاته فقط
        if ($user->student) {
            return $finalGrade->student_number === $user->student->enrollment_number;
        }

        // ولي الأمر: يرى درجات أبنائه فقط
        if ($user->guardian) {
            $childrenNumbers = $user->guardian->students->pluck('enrollment_number')->toArray();
            return in_array($finalGrade->student_number, $childrenNumbers);
        }

        return false;
    }

    /**
     * إنشاء درجة نهائية جديدة
     */
    public function create(User $user)
    {
        // المعلم يمكنه إدخال الدرجات النهائية
        return $user->hasRole('teacher') && $user->can('create_final_grade');
    }

    /**
     * تحديث درجة نهائية
     */
    public function update(User $user, FinalyGrades $finalGrade)
    {
        // المعلم يمكنه تحديث درجات المواد التي يدرسها فقط
        return $user->teacher && 
               $user->teacher->id === $finalGrade->course->teacher_id && 
               $user->can('update_final_grade');
    }

    /**
     * حذف درجة نهائية
     */
    public function delete(User $user, FinalyGrades $finalGrade)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('delete_final_grade');
    }
}