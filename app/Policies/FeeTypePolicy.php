<?php

namespace App\Policies;

use App\Models\FeeType;
use App\Models\User;

class FeeTypePolicy
{
    /**
     * عرض قائمة أنواع الرسوم
     */
    public function viewAny(User $user)
    {
        // المدير فقط يمكنه رؤية أنواع الرسوم
        return $user->hasRole(['super-admin', 'admin']) && $user->can('manage_fees');
    }

    /**
     * عرض نوع رسوم محدد
     */
    public function view(User $user, FeeType $feeType)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('manage_fees');
    }

    /**
     * إنشاء نوع رسوم جديد
     */
    public function create(User $user)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('manage_fees');
    }

    /**
     * تحديث نوع رسوم
     */
    public function update(User $user, FeeType $feeType)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('manage_fees');
    }

    /**
     * حذف نوع رسوم
     */
    public function delete(User $user, FeeType $feeType)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('manage_fees');
    }
}