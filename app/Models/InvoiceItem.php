<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'fee_type_id',
        'amount'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public function invoice()
    {
        return $this->belongsTo(StudentInvoice::class, 'invoice_id');
    }

    public function feeType()
    {
        return $this->belongsTo(FeeType::class);
    }
}