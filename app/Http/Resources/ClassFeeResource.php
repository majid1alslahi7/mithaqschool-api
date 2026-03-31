<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClassFeeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'grade_id'          => $this->grade_id,
            'fee_type_id'       => $this->fee_type_id,
            'amount'            => $this->amount,
            'academic_year_id'  => $this->academic_year_id,

            // العلاقات
            'fee_type'          => new FeeTypeResource($this->whenLoaded('feeType')),
            'grade'             => new GradeResource($this->whenLoaded('grade')),
            'academic_year'     => new AcademicYearResource($this->whenLoaded('academicYear')),

            // أسماء مباشرة
            'grade_name'        => $this->grade?->name,
            'academic_year_name'=> $this->academicYear?->name,

            'created_at'        => $this->created_at,
        ];
    }
}