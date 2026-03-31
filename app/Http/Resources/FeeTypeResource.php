<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FeeTypeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'description'    => $this->description,
            'is_recurring'   => $this->is_recurring,
            'default_amount' => $this->default_amount,
            'is_active'      => $this->is_active,
            'created_at'     => $this->created_at,
        ];
    }
}