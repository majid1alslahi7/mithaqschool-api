<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdjustmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'student_id' => 'sometimes|integer',
            'invoice_id' => 'sometimes|exists:student_invoices,id',
            'type'       => 'sometimes|in:discount,fine,scholarship,manual',
            'amount'     => 'sometimes|numeric|min:0',
            'reason'     => 'nullable|string',
            'created_by' => 'nullable|uuid'
        ];
    }
}