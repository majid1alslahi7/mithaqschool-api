<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SemesterGradeResource extends JsonResource
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
            'student_number' => $this->student_number,
            'student' => new StudentResource($this->whenLoaded('student')),
            'course_id' => $this->course_id,
            'course' => $this->whenLoaded('course'),
            'academic_year_id' => $this->academic_year_id,
            'academic_year' => $this->whenLoaded('academicYear'),
            'semester_id' => $this->semester_id,
            'semester' => $this->whenLoaded('semester'),
            'semester_work' => $this->semester_work,
            'exam_semester' => $this->exam_semester,
            'total_score' => $this->total_score,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
