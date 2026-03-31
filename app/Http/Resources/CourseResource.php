<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'grade' => new GradeResource($this->whenLoaded('grade')),
            'classroom' => new ClassroomResource($this->whenLoaded('classroom')),
            'teacher' => new TeacherResource($this->whenLoaded('teacher')),
            'stage' => new SchoolStageResource($this->whenLoaded('stage')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        // إضافة معلومات إضافية للمعلم والمدير
        if ($user && ($user->hasRole('teacher') || $user->hasRole('admin') || $user->hasRole('super-admin'))) {
            $data['students_count'] = $this->students_count ?? $this->students->count();
            $data['students'] = StudentResource::collection($this->whenLoaded('students'));
            $data['schedule'] = ScheduleResource::collection($this->whenLoaded('schedule'));
        }

        return $data;
    }
}