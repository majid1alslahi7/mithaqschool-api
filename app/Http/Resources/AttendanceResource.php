<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
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
            'date' => $this->date,
            'period' => $this->period,
            'status' => $this->status,
            'notes' => $this->notes,
            'student' => new StudentResource($this->whenLoaded('student')),
            'course' => new CourseResource($this->whenLoaded('course')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        // إضافة حالة التقييم
        $data['status_text'] = $this->status === 'present' ? 'حاضر' : ($this->status === 'absent' ? 'غائب' : 'متأخر');

        // إضافة صلاحية التعديل للمعلم
        if ($user && $user->hasRole('teacher')) {
            $data['can_edit'] = $user->can('update', $this->resource);
        }

        return $data;
    }
}