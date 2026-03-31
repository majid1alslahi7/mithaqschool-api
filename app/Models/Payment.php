<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;

class Payment extends Model
{
    use Searchable;

    protected $fillable = [
        'student_id',
        'invoice_id',
        'amount_paid',
        'payment_method',
        'payment_date',
        'reference_number'
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'payment_date' => 'datetime'
    ];

    public function invoice()
    {
        return $this->belongsTo(StudentInvoice::class, 'invoice_id');
    }

    public function scopeFilter($query, $filters)
    {
        return $query
            ->search($filters['search'] ?? null, ['reference_number'])
            ->when($filters['student_id'] ?? null, fn($q) => $q->where('student_id', $filters['student_id']));
    }
}