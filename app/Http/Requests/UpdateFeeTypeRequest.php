<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFeeTypeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'           => 'sometimes|string|max:255',
            'description'    => 'nullable|string',
            'is_recurring'   => 'sometimes|boolean',
            'default_amount' => 'nullable|numeric|min:0',
            'is_active'      => 'sometimes|boolean'
        ];
    }
}