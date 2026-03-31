<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassFeeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'grade_id'      => 'required|exists:grades,id',
            'fee_type_id'   => 'required|exists:fee_types,id',
            'academic_year_id'=> 'required|exists:academic_years,id',
            'amount'        => 'required|numeric|min:0',
        ];
    }
}