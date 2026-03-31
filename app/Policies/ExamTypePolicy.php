<?php

namespace App\Policies;

use App\Models\ExamType;
use App\Models\User;

class ExamTypePolicy
{
    /**
     * عرض قائمة أنواع الاختبارات
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_exam_type');
    }

    /**
     * عرض نوع اختبار محدد
     */
    public function view(User $user, ExamType $examType)
    {
        // المدير: يرى كل الأنواع
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // المعلم: يمكنه رؤية الأنواع
        if ($user->hasRole('teacher')) {
            return true;
        }

        return false;
    }

    /**
     * إنشاء نوع اختبار جديد
     */
    public function create(User $user)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('create_exam_type');
    }

    /**
     * تحديث نوع اختبار
     */
    public function update(User $user, ExamType $examType)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('update_exam_type');
    }

    /**
     * حذف نوع اختبار
     */
    public function delete(User $user, ExamType $examType)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('delete_exam_type');
    }
}