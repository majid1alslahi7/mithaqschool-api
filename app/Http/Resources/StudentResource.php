<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'full_name' => $this->f_name . ' ' . $this->l_name,
            'f_name' => $this->f_name,
            'l_name' => $this->l_name,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'address' => $this->address,
            'avatar' => $this->avatar_url,
            'is_active' => $this->is_active,
            'attendance_status' => $this->attendance_status,
            'user' => new UserResource($this->whenLoaded('user')),
            'parent' => new GuardianResource($this->whenLoaded('guardian')),
            'classroom' => new ClassroomResource($this->whenLoaded('classroom')),
            'grade' => new GradeResource($this->whenLoaded('grade')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        // إضافة معلومات إضافية للمعلم والمدير
        if ($user && ($user->hasRole('teacher') || $user->hasRole('admin') || $user->hasRole('super-admin'))) {
            $data['courses'] = CourseResource::collection($this->whenLoaded('courses'));
            $data['attendance_rate'] = $this->attendance_rate ?? null;
            $data['average_grade'] = $this->average_grade ?? null;
        }

        // إضافة معلومات الفواتير للولي والمدير
        if ($user && ($user->hasRole('guardian') || $user->hasRole('admin') || $user->hasRole('super-admin'))) {
            $data['invoices_summary'] = [
                'total_invoices' => $this->invoices_summary['total_invoices'] ?? 0,
                'total_paid' => $this->invoices_summary['total_paid'] ?? 0,
                'remaining' => $this->invoices_summary['remaining'] ?? 0,
            ];
        }

        return $data;
    }
}