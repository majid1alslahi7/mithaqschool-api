<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFinalyGradeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return True;
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
            'academic_year_id' => 'required|exists:academic_years,id',

            'first_achievement_score' => 'required|integer|min:0|max:20',
            'midterm_test' => 'required|integer|min:0|max:30',
            'second_achievement_score' => 'required|integer|min:0|max:20',
            'final_test' => 'required|integer|min:0|max:30',
        ];
    }
}
/*
SQLSTATE[HY000]: General error: 1364 Field 'academic_year_id' doesn't have a default value (Connection: mysql, SQL: insert into `finaly_grades` (`student_number`, `course_id`, `first_achievement_score`, `midterm_test`, `second_achievement_score`, `final_test`, `updated_at`, `created_at`) values (20260000, 4, 20, 30, 20, 30, 2026-01-26 22:03:58, 2026-01-26 22:03:58))
*/