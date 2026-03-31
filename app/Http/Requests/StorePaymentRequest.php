<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'student_id'      => 'required|integer',
            'invoice_id'      => 'required|exists:student_invoices,id',
            'amount_paid'     => 'required|numeric|min:0',
            'payment_method'  => 'nullable|string|max:50',
            'payment_date'    => 'nullable|date',
            'reference_number'=> 'nullable|string|max:255'
        ];
    }
}