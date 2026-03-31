<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSemesterGradeRequest extends FormRequest
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
            'student_number' => 'sometimes|exists:students,enrollment_number',
            'course_id' => 'sometimes|exists:courses,id',
            'academic_year_id' => 'sometimes|exists:academic_years,id',
            'semester_id' => 'sometimes|exists:semesters,id',
            'semester_work' => 'sometimes|integer|min:0|max:20',
            'exam_semester' => 'sometimes|integer|min:0|max:30',
        ];
    }
}
