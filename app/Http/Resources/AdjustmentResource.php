<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdjustmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'student_id' => $this->student_id,
            'invoice_id' => $this->invoice_id,
            'type'       => $this->type,
            'amount'     => $this->amount,
            'reason'     => $this->reason,
            'created_by' => $this->created_by,

            'invoice' => new StudentInvoiceResource($this->whenLoaded('invoice')),

            'created_at' => $this->created_at,
        ];
    }
}