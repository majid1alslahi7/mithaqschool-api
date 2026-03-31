<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMonthlyGradeRequest extends FormRequest
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
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester_id' => 'required|exists:semesters,id',
            'month' => 'required|integer|between:1,12',
            'written_exam' => 'required|integer|between:0,40',
            'homework' => 'required|integer|between:0,20',
            'oral_exam' => 'required|integer|between:0,20',
            'attendance' => 'required|integer|between:0,20',
        ];
    }
}
