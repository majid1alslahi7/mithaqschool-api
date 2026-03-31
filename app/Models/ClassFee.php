<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;

class ClassFee extends Model
{
    use Searchable;

    protected $fillable = [
        'grade_id',
        'fee_type_id',
        'academic_year_id',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    /**
     * الحقول القابلة للبحث
     */
    protected $searchableFields = [
        'amount',
        'feeType.name',
        'grade.name',
        'academicYear.name',
    ];

    public function feeType()
    {
        return $this->belongsTo(FeeType::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function scopeFilter($query, $filters)
    {
        return $query
            ->search($filters['search'] ?? null) // البحث في الحقول المعرفة أعلاه
            ->when($filters['grade_id'] ?? null, fn($q) => $q->where('grade_id', $filters['grade_id']))
            ->when($filters['fee_type_id'] ?? null, fn($q) => $q->where('fee_type_id', $filters['fee_type_id']))
            ->when($filters['academic_year_id'] ?? null, fn($q) => $q->where('academic_year_id', $filters['academic_year_id']));
    }
}