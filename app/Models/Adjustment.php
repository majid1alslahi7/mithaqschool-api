<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;

class Adjustment extends Model
{
    use Searchable;

    protected $fillable = [
        'student_id',
        'invoice_id',
        'type',
        'amount',
        'reason',
        'created_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public function invoice()
    {
        return $this->belongsTo(StudentInvoice::class, 'invoice_id');
    }

    public function scopeFilter($query, $filters)
    {
        return $query
            ->search($filters['search'] ?? null, ['reason'])
            ->when($filters['type'] ?? null, fn($q) => $q->where('type', $filters['type']))
            ->when($filters['student_id'] ?? null, fn($q) => $q->where('student_id', $filters['student_id']));
    }

    /**
     * ✅ إعادة حساب الفاتورة تلقائيًا عند الإنشاء أو التحديث أو الحذف
     */
    protected static function booted()
    {
        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(fn($adjustment) => $adjustment->refreshInvoiceTotals());
        }
    }

    /**
     * دالة مساعدة لتحديث الفاتورة المرتبطة
     */
    protected function refreshInvoiceTotals()
    {
        try {
            $invoice = $this->invoice ?? StudentInvoice::find($this->invoice_id);
            if ($invoice) {
                $invoice->recalculateTotals();
            }
        } catch (\Throwable $e) {
            \Log::error("خطأ في refreshInvoiceTotals للتعديل {$this->id}: " . $e->getMessage());
        }
    }
}