<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GuardianResource extends JsonResource
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
            'address' => $this->address,
            'avatar' => $this->avatar_url,
            'is_active' => $this->is_active,
            'user' => new UserResource($this->whenLoaded('user')),
            'students' => StudentResource::collection($this->whenLoaded('students')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        // إضافة معلومات إضافية للأبناء مع التفاصيل الدراسية
        if ($this->whenLoaded('students')) {
            $data['children_summary'] = $this->students->map(function($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->f_name . ' ' . $student->l_name,
                    'enrollment_number' => $student->enrollment_number,
                    'grade' => $student->grade->name ?? null,
                    'classroom' => $student->classroom->name ?? null,
                    'attendance_rate' => $student->attendance_rate ?? null,
                    'average_grade' => $student->average_grade ?? null,
                ];
            });
        }

        return $data;
    }
}