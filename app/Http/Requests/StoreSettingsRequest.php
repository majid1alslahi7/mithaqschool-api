<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage_school_settings');
    }

    public function rules(): array
    {
        return [
            'school_name' => 'required|string|max:255',
            'school_code' => 'nullable|string|max:50',
            'school_type' => 'nullable|string|max:50',
            'principal_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'logo_url' => 'nullable|string',
            'established_year' => 'nullable|string|max:4',
            'description' => 'nullable|string',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'semester_id' => 'nullable|exists:semesters,id',
        ];
    }

    public function messages(): array
    {
        return [
            'school_name.required' => 'اسم المدرسة مطلوب',
            'school_name.string' => 'اسم المدرسة يجب أن يكون نصاً',
            'email.email' => 'البريد الإلكتروني غير صالح',
        ];
    }
}
