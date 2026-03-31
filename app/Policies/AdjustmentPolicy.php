<?php

namespace App\Policies;

use App\Models\Adjustment;
use App\Models\User;

class AdjustmentPolicy
{
    /**
     * عرض قائمة التعديلات
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_invoice');
    }

    /**
     * عرض تعديل محدد
     */
    public function view(User $user, Adjustment $adjustment)
    {
        // المدير: يرى كل التعديلات
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // الطالب: يرى تعديلات فاتورته فقط
        if ($user->student && $adjustment->invoice) {
            return $adjustment->invoice->student_id === $user->student->id;
        }

        // ولي الأمر: يرى تعديلات فواتير أبنائه فقط
        if ($user->guardian && $adjustment->invoice) {
            $childrenIds = $user->guardian->students->pluck('id')->toArray();
            return in_array($adjustment->invoice->student_id, $childrenIds);
        }

        return false;
    }

    /**
     * إنشاء تعديل جديد (خصم أو غرامة)
     */
    public function create(User $user)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('manage_fees');
    }

    /**
     * تحديث تعديل
     */
    public function update(User $user, Adjustment $adjustment)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('manage_fees');
    }

    /**
     * حذف تعديل
     */
    public function delete(User $user, Adjustment $adjustment)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('manage_fees');
    }
}