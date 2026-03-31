<?php

namespace App\Policies;

use App\Models\StudentInvoice;
use App\Models\User;

class StudentInvoicePolicy
{
    /**
     * عرض قائمة الفواتير
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_invoice');
    }

    /**
     * عرض فاتورة محددة
     */
    public function view(User $user, StudentInvoice $invoice)
    {
        // المدير: يرى كل الفواتير
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // ولي الأمر: يرى فواتير أبنائه فقط
        if ($user->guardian) {
            $childrenIds = $user->guardian->students->pluck('id')->toArray();
            return in_array($invoice->student_id, $childrenIds);
        }

        // الطالب: يرى فاتورته فقط (إذا كان لديه صلاحية)
        if ($user->student) {
            return $invoice->student_id === $user->student->id && $user->can('view_invoice');
        }

        return false;
    }

    /**
     * إنشاء فاتورة جديدة
     */
    public function create(User $user)
    {
        // فقط المدير أو الإدارة المالية يمكنهم إنشاء فواتير
        return $user->hasRole(['super-admin', 'admin']) && $user->can('create_invoice');
    }

    /**
     * تحديث فاتورة
     */
    public function update(User $user, StudentInvoice $invoice)
    {
        // فقط المدير يمكنه تحديث الفواتير
        return $user->hasRole(['super-admin', 'admin']) && $user->can('update_invoice');
    }

    /**
     * حذف فاتورة
     */
    public function delete(User $user, StudentInvoice $invoice)
    {
        // فقط المدير يمكنه الحذف
        return $user->hasRole(['super-admin', 'admin']) && $user->can('delete_invoice');
    }

    /**
     * دفع فاتورة (الطالب أو ولي الأمر)
     */
    public function pay(User $user, StudentInvoice $invoice)
    {
        // الطالب أو ولي الأمر يمكنه دفع الفاتورة الخاصة به أو بأبنائه
        if ($user->student) {
            return $invoice->student_id === $user->student->id;
        }
        
        if ($user->guardian) {
            $childrenIds = $user->guardian->students->pluck('id')->toArray();
            return in_array($invoice->student_id, $childrenIds);
        }
        
        return false;
    }
}