<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTeacherRequest extends FormRequest
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
            'user_id' => 'nullable|integer|exists:users,id|unique:teachers,user_id',
            'f_name' => 'required|string|max:200',
            'l_name' => 'required|string|max:200',
            'gender' => ['required', Rule::in(['male', 'female'])],
            'birth_date' => 'nullable|date',
            'hire_date' => 'nullable|date',
            'address' => 'nullable|string',
            'avatar_path' => 'nullable|string',
            'grade_id' => 'nullable|exists:grades,id',
            'course_id' => 'nullable|exists:courses,id',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'last_modified' => 'nullable|date',
            'is_synced' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ];
    }
}
