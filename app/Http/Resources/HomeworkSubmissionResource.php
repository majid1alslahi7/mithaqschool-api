<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeworkSubmissionResource extends JsonResource
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
            'submission_date' => $this->submission_date,
            'file_url' => $this->file_url,
            'score' => $this->score,
            'grade' => $this->grade,
            'feedback' => $this->feedback,
            'status' => $this->status,
            'homework' => new HomeworkResource($this->whenLoaded('homework')),
            'student' => new StudentResource($this->whenLoaded('student')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        // إضافة حالة التأخير
        if ($this->homework && $this->submission_date > $this->homework->due_date) {
            $data['is_late'] = true;
            $data['late_days'] = $this->submission_date->diffInDays($this->homework->due_date);
        }

        // إضافة صلاحية التقييم للمعلم
        if ($user && $user->hasRole('teacher')) {
            $data['can_grade'] = $user->can('grade', $this->resource);
        }

        return $data;
    }
}