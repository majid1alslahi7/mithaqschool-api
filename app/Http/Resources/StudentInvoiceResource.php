<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentInvoiceResource extends JsonResource
{
    public function toArray($request)
    {
        $user = $request->user();
        
        $data = [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'invoice_number' => $this->invoice_number,

            // المبلغ الأصلي قبل الخصومات والغرامات
            'base_amount' => $this->base_amount,

            // المبلغ المعدل بعد الخصومات والغرامات
            'total_amount' => $this->total_amount,

            // الخصومات والغرامات من الأعمدة المخزنة
            'total_discounts' => $this->total_discounts,
            'total_fines' => $this->total_fines,

            // المتبقي من accessor
            'remaining_amount' => $this->remaining_amount,

            'status' => $this->status,
            'due_date' => $this->due_date,

            // بيانات الطالب المرتبطة
            'student' => new StudentResource($this->whenLoaded('student')),
            'student_name' => $this->student ? ($this->student->f_name . ' ' . $this->student->l_name) : null,
            'enrollment_number' => $this->student?->enrollment_number,
            'grade_name' => $this->student?->grade?->name,

            // العلاقات الأخرى
            'items' => InvoiceItemResource::collection($this->whenLoaded('items')),
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
            'adjustments' => AdjustmentResource::collection($this->whenLoaded('adjustments')),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        // إضافة حالة الدفع
        if ($this->remaining_amount == 0) {
            $data['payment_status'] = 'مدفوعة بالكامل';
        } elseif ($this->remaining_amount < $this->total_amount) {
            $data['payment_status'] = 'مدفوعة جزئياً';
        } else {
            $data['payment_status'] = 'غير مدفوعة';
        }

        // إضافة صلاحية الدفع للمستخدم
        if ($user) {
            $data['can_pay'] = $user->can('pay', $this->resource);
        }

        return $data;
    }
}