<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGuardianRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
                Rule::unique('parents', 'user_id')
            ],
            'f_name' => 'required|string|max:200',
            'l_name' => 'required|string|max:200',
            'gender' => 'required|string|in:male,female',
            'address' => 'nullable|string',
            'avatar_path' => 'nullable|string',
        ];
    }
}