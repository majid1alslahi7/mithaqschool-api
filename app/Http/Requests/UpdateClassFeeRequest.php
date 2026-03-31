<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClassFeeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'grade_id'      => 'sometimes|exists:grades,id',
            'fee_type_id'   => 'sometimes|exists:fee_types,id',
            'amount'        => 'sometimes|numeric|min:0',
            'academic_year_id'=> 'sometimes|exists:academic_years,id',
        ];
    }
}