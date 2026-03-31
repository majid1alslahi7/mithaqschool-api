<?php

namespace app\Services;

use app\Models\Student;
use app\Models\StudentInvoice;
use app\Models\InvoiceItem;
use app\Models\ClassFee;
use Illuminate\Support\Str;

class InvoiceGeneratorService
{
    public function generateForStudent(Student $student)
    {
        // 1) جلب رسوم الصف
        $classFees = ClassFee::where('grade_id', $student->grade_id)
            ->with('feeType')
            ->get();

        if ($classFees->isEmpty()) {
            return null; // لا توجد رسوم لهذا الصف
        }

        // 2) إنشاء رقم فاتورة
        $invoiceNumber = 'INV-' . Str::random(6);

        // 3) إنشاء الفاتورة
        $invoice = StudentInvoice::create([
            'student_id'    => $student->id,
            'invoice_number'=> $invoiceNumber,
            'total_amount'  => 0, // سيتم حسابه لاحقًا
            'due_date'      => now()->addDays(7),
            'status'        => 'unpaid',
        ]);

        $total = 0;

        // 4) إضافة البنود
        foreach ($classFees as $fee) {
            InvoiceItem::create([
                'invoice_id'   => $invoice->id,
                'fee_type_id'  => $fee->fee_type_id,
                'amount'       => $fee->amount,
            ]);

            $total += $fee->amount;
        }

        // 5) تحديث إجمالي الفاتورة
        $invoice->update([
            'total_amount' => $total
        ]);

        return $invoice;
    }
}