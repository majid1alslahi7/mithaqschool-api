<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountsResource extends JsonResource
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
            'total' => $this->resource,
            'updated_at' => now(),
        ];

        // إضافة معلومات الجلسات النشطة للمدير
        if ($user && ($user->hasRole('super-admin') || $user->hasRole('admin'))) {
            $data['active_sessions'] = $this->active_sessions ?? null;
        }

        return $data;
    }
}