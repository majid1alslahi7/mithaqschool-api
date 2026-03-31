<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFinalyGradeRequest extends FormRequest
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
          'student_number' => 'required|exists:students,enrollment_number',
            'course_id' => 'required|exists:courses,id',
            'academic_year_id' => 'sometimes|required|exists:academic_years,id',
            'first_achievement_score' => 'required|integer|min:0|max:20',
            'midterm_test' => 'required|integer|min:0|max:30',
            'second_achievement_score' => 'required|integer|min:0|max:20',
            'final_test' => 'required|integer|min:0|max:30',
        ];
    }
}
