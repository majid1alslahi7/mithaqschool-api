<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'invoice_id'  => 'sometimes|exists:student_invoices,id',
            'fee_type_id' => 'sometimes|exists:fee_types,id',
            'amount'      => 'sometimes|numeric|min:0'
        ];
    }
}