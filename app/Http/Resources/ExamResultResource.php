<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $request->user();
        
        $data = [
            'id' => $this->id,
            'score' => $this->score,
            'grade' => $this->grade,
            'remarks' => $this->remarks,
            'exam' => new ExamResource($this->whenLoaded('exam')),
            'student' => new StudentResource($this->whenLoaded('student')),
            'grade_scale' => new GradesScaleResource($this->whenLoaded('gradeScale')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        // إضافة نسبة النجاح
        if ($this->exam && $this->exam->max_score) {
            $data['percentage'] = round(($this->score / $this->exam->max_score) * 100, 2);
            $data['passed'] = $data['percentage'] >= 50;
        }

        // إضافة صلاحية التعديل للمعلم
        if ($user && $user->hasRole('teacher')) {
            $data['can_edit'] = $user->can('update', $this->resource);
        }

        return $data;
    }
}