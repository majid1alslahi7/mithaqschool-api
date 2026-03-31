<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'invoice_id'  => $this->invoice_id,
            'fee_type_id' => $this->fee_type_id,
            'amount'      => $this->amount,

            'fee_type' => new FeeTypeResource($this->whenLoaded('feeType')),

            'created_at' => $this->created_at,
        ];
    }
}