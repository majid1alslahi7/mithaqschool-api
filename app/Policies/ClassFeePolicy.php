<?php

namespace App\Policies;

use App\Models\ClassFee;
use App\Models\User;

class ClassFeePolicy
{
    /**
     * عرض قائمة رسوم الصفوف
     */
    public function viewAny(User $user)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('manage_fees');
    }

    /**
     * عرض رسم صف محدد
     */
    public function view(User $user, ClassFee $classFee)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('manage_fees');
    }

    /**
     * إنشاء رسم صف جديد
     */
    public function create(User $user)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('manage_fees');
    }

    /**
     * تحديث رسم صف
     */
    public function update(User $user, ClassFee $classFee)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('manage_fees');
    }

    /**
     * حذف رسم صف
     */
    public function delete(User $user, ClassFee $classFee)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('manage_fees');
    }
}