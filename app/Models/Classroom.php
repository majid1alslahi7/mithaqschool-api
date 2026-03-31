<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory, Searchable;

    protected $searchableFields = [
        'name',
        'description',
        'grade.name',
        'teacher.f_name',
        'teacher.l_name',
    ];

    protected $fillable = [
        'name',
        'grade_id',
        'teacher_id',
        'description',
        'is_active',
        'last_modified',
        'is_deleted',
        'is_synced',
    ];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
