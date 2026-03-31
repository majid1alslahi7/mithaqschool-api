<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinalyGradeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_number' => $this->student_number, // تعديل: تم تغيير اسم الحقل ليطابق قاعدة البيانات
        
        'first_achievement_score'=>$this->first_achievement_score,
        'midterm_test'=>$this->midterm_test,
        'second_achievement_score'=>$this->second_achievement_score,
        'final_test'=>$this->final_test,
        'total_score'=>$this->total_score,
                    'academic_year' => new AcademicYearResource($this->whenLoaded('academicYear')),
        'student' => new StudentResource($this->whenLoaded('student')),
        'course' => new CourseResource($this->whenLoaded('course')),
        ];
    }
}

