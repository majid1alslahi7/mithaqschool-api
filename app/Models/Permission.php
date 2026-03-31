<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasFactory, Searchable;

    protected $fillable = ['name', 'label', 'guard_name'];

    /**
     * Scope a query to sort permissions by a given column and direction.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $sortBy
     * @param  string  $sortDirection
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSort(Builder $query, $sortBy, $sortDirection)
    {
        $validSorts = ['id', 'name', 'label'];
        if (in_array($sortBy, $validSorts)) {
            return $query->orderBy($sortBy, $sortDirection);
        }
        return $query;
    }
}
