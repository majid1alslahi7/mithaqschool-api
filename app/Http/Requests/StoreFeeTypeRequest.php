<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeeTypeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'academic_year_id'=>'academic_year_id',
            'is_recurring'   => 'boolean',
            'default_amount' => 'nullable|numeric|min:0',
            'is_active'      => 'boolean'
        ];
    }
}