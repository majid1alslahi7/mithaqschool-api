<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    /**
     * عرض قائمة المدفوعات
     */
    public function viewAny(User $user)
    {
        return $user->can('view_transactions');
    }

    /**
     * عرض دفعة محددة
     */
    public function view(User $user, Payment $payment)
    {
        // المدير: يرى كل المدفوعات
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // ولي الأمر: يرى مدفوعات أبنائه فقط
        if ($user->guardian) {
            $childrenIds = $user->guardian->students->pluck('id')->toArray();
            return in_array($payment->student_id, $childrenIds);
        }

        // الطالب: يرى مدفوعاته فقط
        if ($user->student) {
            return $payment->student_id === $user->student->id;
        }

        return false;
    }

    /**
     * إنشاء دفعة جديدة
     */
    public function create(User $user)
    {
        // الطالب أو ولي الأمر يمكنه إنشاء دفعة (دفع)
        if ($user->hasRole(['student', 'guardian'])) {
            return $user->can('process_payment');
        }
        
        return $user->hasRole(['super-admin', 'admin']) && $user->can('process_payment');
    }

    /**
     * تحديث دفعة
     */
    public function update(User $user, Payment $payment)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('manage_fees');
    }

    /**
     * حذف دفعة
     */
    public function delete(User $user, Payment $payment)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('manage_fees');
    }

    /**
     * استرداد دفعة
     */
    public function refund(User $user, Payment $payment)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('refund_payment');
    }
}