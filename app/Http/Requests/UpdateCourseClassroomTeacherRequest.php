<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseClassroomTeacherRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'course_id' => ['sometimes', 'exists:courses,id'],
            'classroom_id' => ['sometimes', 'exists:classrooms,id'],
            'teacher_id' => [
                'sometimes',
                'exists:teachers,id',
                Rule::unique('course_classroom_teachers')->where(function ($query) {
                    return $query->where('course_id', $this->course_id ?? $this->course_classroom_teacher->course_id)
                        ->where('classroom_id', $this->classroom_id ?? $this->course_classroom_teacher->classroom_id);
                })->ignore($this->course_classroom_teacher),
            ],
        ];
    }

    public function messages()
    {
        return [
            'teacher_id.unique' => 'هذا الربط بين المقرر والفصل والمعلم مسجل بالفعل.',
        ];
    }
}
