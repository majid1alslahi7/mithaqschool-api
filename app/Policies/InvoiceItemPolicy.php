<?php

namespace App\Policies;

use App\Models\InvoiceItem;
use App\Models\User;

class InvoiceItemPolicy
{
    /**
     * عرض قائمة بنود الفواتير
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_invoice');
    }

    /**
     * عرض بند فاتورة محدد
     */
    public function view(User $user, InvoiceItem $invoiceItem)
    {
        // المدير: يرى كل البنود
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // الطالب: يرى بنود فاتورته فقط
        if ($user->student && $invoiceItem->invoice) {
            return $invoiceItem->invoice->student_id === $user->student->id;
        }

        // ولي الأمر: يرى بنود فواتير أبنائه فقط
        if ($user->guardian && $invoiceItem->invoice) {
            $childrenIds = $user->guardian->students->pluck('id')->toArray();
            return in_array($invoiceItem->invoice->student_id, $childrenIds);
        }

        return false;
    }

    /**
     * إنشاء بند فاتورة جديد
     */
    public function create(User $user)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('create_invoice');
    }

    /**
     * تحديث بند فاتورة
     */
    public function update(User $user, InvoiceItem $invoiceItem)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('update_invoice');
    }

    /**
     * حذف بند فاتورة
     */
    public function delete(User $user, InvoiceItem $invoiceItem)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('delete_invoice');
    }
}