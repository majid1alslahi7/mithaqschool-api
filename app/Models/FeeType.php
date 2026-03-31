<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;

class FeeType extends Model
{
    use Searchable;

    protected $fillable = [
        'name',
        'description',
        'is_recurring',
        'default_amount',
        'is_active'
    ];

    protected $casts = [
        'is_recurring' => 'boolean',
        'is_active' => 'boolean',
        'default_amount' => 'decimal:2'
    ];

    public function classFees()
    {
        return $this->hasMany(ClassFee::class);
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function scopeFilter($query, $filters)
    {
        return $query
            ->search($filters['search'] ?? null, ['name', 'description'])
            ->when(isset($filters['active']), fn($q) => $q->where('is_active', $filters['active']));
    }
}