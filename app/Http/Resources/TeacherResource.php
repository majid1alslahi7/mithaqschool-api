<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
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
            'enrollment_number' => $this->enrollment_number,
            'user_id' => $this->user_id,
            'full_name' => $this->f_name . ' ' . $this->l_name,
            'f_name' => $this->f_name,
            'l_name' => $this->l_name,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'hire_date' => $this->hire_date,
            'address' => $this->address,
            'avatar_path' => $this->avatar_path,
            'avatar' => $this->avatar_url,
            'avatar_url' => $this->avatar_url,
            'grade_id' => $this->grade_id,
            'course_id' => $this->course_id,
            'classroom_id' => $this->classroom_id,
            'last_modified' => $this->last_modified,
            'is_deleted' => $this->is_deleted,
            'is_synced' => $this->is_synced,
            'is_active' => $this->is_active,
            'user' => new UserResource($this->whenLoaded('user')),
            'grade' => new GradeResource($this->whenLoaded('grade')),
            'course' => new CourseResource($this->whenLoaded('course')),
            'classroom' => new ClassroomResource($this->whenLoaded('classroom')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        // إضافة معلومات إضافية للمدير
        if ($user && ($user->hasRole('admin') || $user->hasRole('super-admin'))) {
            $data['courses_taught'] = CourseResource::collection($this->whenLoaded('courses'));
            $data['students_count'] = $this->students_count ?? 0;
            $data['schedule'] = ScheduleResource::collection($this->whenLoaded('schedule'));
        }

        return $data;
    }
}