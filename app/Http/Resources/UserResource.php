<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone,
            'is_active' => $this->is_active,
            'is_deleted' => $this->is_deleted,
            'last_login_at' => $this->last_login_at,
            'last_logout_at' => $this->last_logout_at,
            'roles' => $this->getRoleNames(), // أسماء الأدوار فقط
            'roles_with_labels' => RoleResource::collection($this->whenLoaded('roles')), // الأدوار مع الـ Label
            'permissions' => $this->getAllPermissions()->pluck('name'), // أسماء الصلاحيات فقط
            'permissions_with_labels' => PermissionResource::collection($this->getAllPermissions()), // الصلاحيات مع الـ Label
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        // إضافة التفاصيل حسب نوع المستخدم
        if ($this->whenLoaded('student')) {
            $data['details'] = new StudentResource($this->student);
            $data['type'] = 'student';
        } elseif ($this->whenLoaded('guardian')) {
            $data['details'] = new GuardianResource($this->guardian);
            $data['type'] = 'guardian';
        } elseif ($this->whenLoaded('teacher')) {
            $data['details'] = new TeacherResource($this->teacher);
            $data['type'] = 'teacher';
        } else {
            $data['type'] = 'admin';
        }

        return $data;
    }
}
