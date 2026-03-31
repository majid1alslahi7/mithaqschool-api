<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;

class StudentInvoice extends Model
{
    use Searchable;

    protected $fillable = [
        'student_id',
        'invoice_number',
        'total_amount',     // المبلغ المعدل بعد الخصومات والغرامات
        'total_discounts',
        'total_fines',
        'due_date',
        'status'
    ];

    protected $casts = [
        'total_amount'    => 'decimal:2',
        'total_discounts' => 'decimal:2',
        'total_fines'     => 'decimal:2',
        'due_date'        => 'date'
    ];

    // العلاقات
    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'invoice_id');
    }

    public function adjustments()
    {
        return $this->hasMany(Adjustment::class, 'invoice_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function scopeFilter($query, $filters)
    {
        return $query
            ->search($filters['search'] ?? null)
            ->when($filters['status'] ?? null, fn($q) => $q->where('status', $filters['status']))
            ->when($filters['student_id'] ?? null, fn($q) => $q->where('student_id', $filters['student_id']));
    }

    /**
     * المبلغ الأصلي قبل الخصومات والغرامات
     */
    public function getBaseAmountAttribute()
    {
        return $this->items()->sum('amount') ?? 0;
    }

    /**
     * ✅ إعادة حساب الإجمالي والمتبقي بعد أي تعديل
     */
   public function recalculateTotals()
{
    $itemsTotal     = $this->items()->sum('amount') ?? 0;
    $discountsTotal = $this->adjustments()->where('type', 'discount')->sum('amount') ?? 0;
    $finesTotal     = $this->adjustments()->where('type', 'fine')->sum('amount') ?? 0;
    $paymentsTotal  = $this->payments()->sum('amount_paid') ?? 0;

    if ($discountsTotal > $itemsTotal) {
        $discountsTotal = $itemsTotal;
    }

    $adjustedTotal = $itemsTotal - $discountsTotal + $finesTotal;
    $remaining = $adjustedTotal - $paymentsTotal;

    $this->original_total   = $itemsTotal;      // أصل الفاتورة
    $this->total_discounts = $discountsTotal;
    $this->total_fines     = $finesTotal;
    $this->total_amount    = $adjustedTotal;    // بعد الخصم والغرامة
    $this->remaining       = $remaining < 0 ? 0 : $remaining;

    if ($this->remaining == 0 && $adjustedTotal > 0) {
        $this->status = 'paid';
    } elseif ($paymentsTotal > 0 && $this->remaining > 0) {
        $this->status = 'partial';
    } else {
        $this->status = 'unpaid';
    }

    $this->save();
}


    /**
     * ✅ المتبقي من الأعمدة المخزنة
     */
    public function getRemainingAmountAttribute()
    {
        $paymentsTotal = $this->payments()->sum('amount_paid') ?? 0;
        $remaining = $this->total_amount - $paymentsTotal;

        return $remaining < 0 ? 0 : $remaining;
    }
}