<?php

namespace App\Http\Requests;

use App\Models\Schedule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateScheduleRequest extends FormRequest
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
            'classroom_id' => ['sometimes', 'exists:classrooms,id'],
            'course_id' => ['sometimes', 'exists:courses,id'],
            'teacher_id' => ['sometimes', 'exists:teachers,id'],
            'day_of_week' => ['sometimes', Rule::in(['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'])],
            'period' => ['sometimes', Rule::in(['الأولى', 'الثانية', 'الثالثة', 'الرابعة', 'الخامسة', 'السادسة', 'السابعة'])],
            'start_time' => ['sometimes', 'date_format:H:i'],
            'end_time' => ['sometimes', 'date_format:H:i', 'after:start_time'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $scheduleId = $this->route('schedule') ?? $this->route('id');
            $schedule = $scheduleId ? Schedule::find($scheduleId) : null;

            $teacherId = $this->input('teacher_id', $schedule?->teacher_id);
            $classroomId = $this->input('classroom_id', $schedule?->classroom_id);
            $dayOfWeek = $this->input('day_of_week', $schedule?->day_of_week);
            $period = $this->input('period', $schedule?->period);

            if ($teacherId && $dayOfWeek && $period) {
                $exists = Schedule::where('teacher_id', $teacherId)
                    ->where('day_of_week', $dayOfWeek)
                    ->where('period', $period)
                    ->when($scheduleId, function ($query) use ($scheduleId) {
                        $query->where('id', '!=', $scheduleId);
                    })
                    ->exists();

                if ($exists) {
                    $validator->errors()->add('teacher_id', 'لا يمكن تكرار المعلم في نفس الفترة ونفس اليوم.');
                }
            }

            if ($classroomId && $dayOfWeek && $period) {
                $exists = Schedule::where('classroom_id', $classroomId)
                    ->where('day_of_week', $dayOfWeek)
                    ->where('period', $period)
                    ->when($scheduleId, function ($query) use ($scheduleId) {
                        $query->where('id', '!=', $scheduleId);
                    })
                    ->exists();

                if ($exists) {
                    $validator->errors()->add('classroom_id', 'لا يمكن تكرار الفصل في نفس الفترة ونفس اليوم.');
                }
            }
        });
    }
}
