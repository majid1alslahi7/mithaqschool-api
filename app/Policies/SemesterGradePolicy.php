<?php

namespace App\Policies;

use App\Models\SemesterGrade;
use App\Models\User;

class SemesterGradePolicy
{
    /**
     * عرض قائمة درجات الفصل
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_semester_grade');
    }

    /**
     * عرض درجة فصل محددة
     */
    public function view(User $user, SemesterGrade $semesterGrade)
    {
        // المدير: يرى كل الدرجات
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // المعلم: يرى درجات المواد التي يدرسها فقط
        if ($user->teacher) {
            return $semesterGrade->course->teacher_id === $user->teacher->id;
        }

        // الطالب: يرى درجاته فقط
        if ($user->student) {
            return $semesterGrade->student_number === $user->student->enrollment_number;
        }

        // ولي الأمر: يرى درجات أبنائه فقط
        if ($user->guardian) {
            $childrenNumbers = $user->guardian->students->pluck('enrollment_number')->toArray();
            return in_array($semesterGrade->student_number, $childrenNumbers);
        }

        return false;
    }

    /**
     * إنشاء درجة فصل جديدة
     */
    public function create(User $user)
    {
        return $user->hasRole('teacher') && $user->can('create_semester_grade');
    }

    /**
     * تحديث درجة فصل
     */
    public function update(User $user, SemesterGrade $semesterGrade)
    {
        return $user->teacher && 
               $user->teacher->id === $semesterGrade->course->teacher_id && 
               $user->can('update_semester_grade');
    }

    /**
     * حذف درجة فصل
     */
    public function delete(User $user, SemesterGrade $semesterGrade)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('delete_semester_grade');
    }
}