<?php

namespace App\Models;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'name',
        'description',
        'stage_id',
    ];
    public function schoolStage()
    {
        return $this->belongsTo(SchoolStage::class, 'stage_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
