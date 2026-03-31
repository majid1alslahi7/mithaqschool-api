<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'school_name' => $this->name,
            'school_code' => $this->code,
            'school_type' => $this->type,
            'principal_name' => $this->principal_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'city' => $this->city,
            'region' => $this->region,
            'logo_url' => $this->logo_url,
            'established_year' => $this->established_year,
            'description' => $this->description,
            'academic_year' => new AcademicYearResource($this->whenLoaded('academicYear')),
            'semester' => new SemesterResource($this->whenLoaded('semester')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
