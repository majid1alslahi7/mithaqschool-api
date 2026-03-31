<?php

namespace App\Policies;

use App\Models\AcademicYear;
use App\Models\User;

class AcademicYearPolicy
{
    /**
     * عرض قائمة السنوات الدراسية
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_academic_year');
    }

    /**
     * عرض سنة دراسية محددة
     */
    public function view(User $user, AcademicYear $academicYear)
    {
        // المدير يمكنه رؤية كل السنوات
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // المعلم يمكنه رؤية السنوات النشطة فقط
        if ($user->hasRole('teacher')) {
            return $academicYear->is_active == true;
        }

        // الطالب يمكنه رؤية السنوات النشطة فقط
        if ($user->hasRole('student')) {
            return $academicYear->is_active == true;
        }

        // ولي الأمر يمكنه رؤية السنوات النشطة فقط
        if ($user->hasRole('guardian')) {
            return $academicYear->is_active == true;
        }

        return false;
    }

    /**
     * إنشاء سنة دراسية جديدة
     */
    public function create(User $user)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('create_academic_year');
    }

    /**
     * تحديث سنة دراسية
     */
    public function update(User $user, AcademicYear $academicYear)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('update_academic_year');
    }

    /**
     * حذف سنة دراسية
     */
    public function delete(User $user, AcademicYear $academicYear)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('delete_academic_year');
    }
}