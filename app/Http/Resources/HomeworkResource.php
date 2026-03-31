<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeworkResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'course' => new CourseResource($this->whenLoaded('course')),
            'teacher' => new TeacherResource($this->whenLoaded('teacher')),
            'student' => new StudentResource($this->whenLoaded('student')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        // إضافة حالة التسليم للطالب
        if ($user && $user->student && $this->student_id == $user->student->id) {
            $submission = $this->submissions->where('student_id', $user->student->id)->first();
            $data['submission_status'] = $submission ? 'submitted' : 'pending';
            $data['submission'] = $submission ? new HomeworkSubmissionResource($submission) : null;
            $data['is_late'] = now()->greaterThan($this->due_date) && !$submission;
        }

        // إضافة التسليمات للمعلم
        if ($user && ($user->hasRole('teacher') || $user->hasRole('admin'))) {
            $data['submissions_count'] = $this->submissions->count();
            $data['submissions'] = HomeworkSubmissionResource::collection($this->whenLoaded('submissions'));
        }

        // إضافة صلاحية التعديل
        if ($user) {
            $data['can_edit'] = $user->can('update', $this->resource);
            $data['can_submit'] = $user->can('submit', $this->resource);
        }

        return $data;
    }
}