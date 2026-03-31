<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentInvoiceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'student_id'     => 'required|integer',
            'invoice_number' => 'required|string|unique:student_invoices,invoice_number',
            'total_amount'   => 'required|numeric|min:0',
            'due_date'       => 'nullable|date',
            'status'         => 'required|in:paid,partial,unpaid,overdue,cancelled'
        ];
    }
}