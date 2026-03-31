<?php

namespace App\Policies;

use App\Models\StudentGrade;
use App\Models\User;

class StudentGradePolicy
{
    /**
     * عرض قائمة درجات الطلاب
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_student_grade');
    }

    /**
     * عرض درجة طالب محددة
     */
    public function view(User $user, StudentGrade $studentGrade)
    {
        // المدير: يرى كل الدرجات
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // المعلم: يرى درجات المواد التي يدرسها فقط
        if ($user->teacher) {
            return $studentGrade->course->teacher_id === $user->teacher->id;
        }

        // الطالب: يرى درجاته فقط
        if ($user->student) {
            return $studentGrade->student_id === $user->student->id;
        }

        // ولي الأمر: يرى درجات أبنائه فقط
        if ($user->guardian) {
            $childrenIds = $user->guardian->students->pluck('id')->toArray();
            return in_array($studentGrade->student_id, $childrenIds);
        }

        return false;
    }

    /**
     * إنشاء درجة جديدة
     */
    public function create(User $user)
    {
        return $user->hasRole('teacher') && $user->can('create_student_grade');
    }

    /**
     * تحديث درجة
     */
    public function update(User $user, StudentGrade $studentGrade)
    {
        return $user->teacher && 
               $user->teacher->id === $studentGrade->course->teacher_id && 
               $user->can('update_student_grade');
    }

    /**
     * حذف درجة
     */
    public function delete(User $user, StudentGrade $studentGrade)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('delete_student_grade');
    }
}