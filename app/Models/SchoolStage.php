<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolStage extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'name',
        'description',
        'order_index',
    ];
    /**
     * Get all of the grades for the SchoolStage
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class, 'stage_id');
    }

    /**
     * Get all of the students for the SchoolStage
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function students(): HasManyThrough
    {
        return $this->hasManyThrough(Student::class, Grade::class, 'stage_id', 'grade_id');
    }
}
