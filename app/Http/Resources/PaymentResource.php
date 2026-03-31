<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'student_id'      => $this->student_id,
            'invoice_id'      => $this->invoice_id,
            'amount_paid'     => $this->amount_paid,
            'payment_method'  => $this->payment_method,
            'payment_date'    => $this->payment_date,
            'reference_number'=> $this->reference_number,

            'invoice' => new StudentInvoiceResource($this->whenLoaded('invoice')),

            'created_at' => $this->created_at,
        ];
    }
}