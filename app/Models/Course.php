<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory, Searchable;

    protected $searchableFields = [
        'name',
        'description',
        'grade.name',
        'classroom.name',
        'teacher.f_name',
        'teacher.l_name',
    ];

    protected $fillable = [
        'name',
        'description',
        'grade_id',
        'classroom_id',
        'teacher_id',
        'stage_id',
        'last_modified',
        'is_deleted',
        'is_synced',
    ];

    /**
     * Get the grade that owns the course.
     */
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    /**
     * Get the classroom that owns the course.
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * Get the teacher that owns the course.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the school stage that owns the course.
     */
    public function stage()
    {
        return $this->belongsTo(SchoolStage::class, 'stage_id');
    }
}
