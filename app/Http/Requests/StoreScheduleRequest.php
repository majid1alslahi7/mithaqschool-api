<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreScheduleRequest extends FormRequest
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
            'classroom_id' => [
                'required',
                'exists:classrooms,id',
                Rule::unique('schedules', 'classroom_id')
                    ->where(function ($query) {
                        return $query
                            ->where('day_of_week', $this->input('day_of_week'))
                            ->where('period', $this->input('period'));
                    }),
            ],
            'course_id' => ['required', 'exists:courses,id'],
            'teacher_id' => [
                'required',
                'exists:teachers,id',
                Rule::unique('schedules', 'teacher_id')
                    ->where(function ($query) {
                        return $query
                            ->where('day_of_week', $this->input('day_of_week'))
                            ->where('period', $this->input('period'));
                    }),
            ],
            'day_of_week' => ['required', Rule::in(['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'])],
            'period' => ['required', Rule::in(['الأولى', 'الثانية', 'الثالثة', 'الرابعة', 'الخامسة', 'السادسة', 'السابعة'])],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ];
    }

    public function messages(): array
    {
        return [
            'teacher_id.unique' => 'لا يمكن تكرار المعلم في نفس الفترة ونفس اليوم.',
            'classroom_id.unique' => 'لا يمكن تكرار الفصل في نفس الفترة ونفس اليوم.',
        ];
    }
}
