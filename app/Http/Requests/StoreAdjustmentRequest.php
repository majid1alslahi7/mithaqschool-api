<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdjustmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'student_id' => 'required|integer',
            'invoice_id' => 'nullable|exists:student_invoices,id',
            'type'       => 'required|in:discount,fine,scholarship,manual',
            'amount'     => 'required|numeric|min:0',
            'reason'     => 'nullable|string',
            'created_by' => 'nullable|uuid'
        ];
    }
}