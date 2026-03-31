<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentInvoiceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'student_id'     => 'sometimes|integer',
            'invoice_number' => 'sometimes|string|unique:student_invoices,invoice_number,' . $this->id,
            'total_amount'   => 'sometimes|numeric|min:0',
            'due_date'       => 'nullable|date',
            'status'         => 'sometimes|in:paid,partial,unpaid,overdue,cancelled'
        ];
    }
}