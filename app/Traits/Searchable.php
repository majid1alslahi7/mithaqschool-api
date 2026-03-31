<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Searchable
{
    /**
     * Scope a query to search for a term in the searchable fields.
     *
     * @param Builder $query
     * @param string|null $searchTerm
     * @return Builder
     */
    public function scopeSearch(Builder $query, ?string $searchTerm): Builder
    {
        if (empty($searchTerm)) {
            return $query;
        }

        // الحصول على الحقول القابلة للبحث
        $searchableFields = property_exists($this, 'searchableFields') 
            ? $this->searchableFields 
            : [];

        // إذا لم توجد حقول للبحث، نبحث في الأعمدة الأساسية
        if (empty($searchableFields)) {
            return $query->where('id', 'LIKE', "%{$searchTerm}%");
        }

        // بناء استعلام البحث
        return $query->where(function ($q) use ($searchTerm, $searchableFields) {
            foreach ($searchableFields as $field) {
                // التحقق من العلاقات (مثل user.name)
                if (str_contains($field, '.')) {
                    $parts = explode('.', $field);
                    $relation = $parts[0];
                    $column = $parts[1];
                    
                    // التحقق من وجود العلاقة
                    if (method_exists($this, $relation)) {
                        $q->orWhereHas($relation, function ($subQuery) use ($column, $searchTerm) {
                            $subQuery->where($column, 'LIKE', "%{$searchTerm}%");
                        });
                    }
                } else {
                    // البحث في العمود المباشر
                    $q->orWhere($field, 'LIKE', "%{$searchTerm}%");
                }
            }
        });
    }
}
