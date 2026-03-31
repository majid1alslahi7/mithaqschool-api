<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('view_reports');
    }

    public function rules(): array
    {
        return [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'format' => 'nullable|in:json,pdf,excel',
            'student_id' => 'nullable|exists:students,id',
            'grade_id' => 'nullable|exists:grades,id',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'course_id' => 'nullable|exists:courses,id',
            'teacher_id' => 'nullable|exists:teachers,id',
        ];
    }
}
