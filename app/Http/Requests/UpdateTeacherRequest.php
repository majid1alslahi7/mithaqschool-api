<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeacherRequest extends FormRequest
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
        $teacherId = $this->route('teacher')->id;

        return [
            'user_id' => 'sometimes|nullable|integer|exists:users,id|unique:teachers,user_id,' . $teacherId,
            'f_name' => 'sometimes|string|max:200',
            'l_name' => 'sometimes|string|max:200',
            'gender' => ['sometimes', Rule::in(['male', 'female'])],
            'birth_date' => 'sometimes|nullable|date',
            'hire_date' => 'sometimes|nullable|date',
            'address' => 'sometimes|nullable|string',
            'avatar_path' => 'sometimes|nullable|string',
            'grade_id' => 'sometimes|nullable|exists:grades,id',
            'course_id' => 'sometimes|nullable|exists:courses,id',
            'classroom_id' => 'sometimes|nullable|exists:classrooms,id',
            'last_modified' => 'sometimes|nullable|date',
            'is_synced' => 'sometimes|nullable|boolean',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
