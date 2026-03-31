<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory, Searchable;

    /**
     * The fields that are searchable.
     *
     * @var array<int, string>
     */
    protected $searchableFields = [
        'day_of_week',
        'period',
        'start_time',
        'end_time',
        'teacher.f_name',
        'teacher.l_name',
        'course.name',
        'classroom.name',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'classroom_id',
        'course_id',
        'teacher_id',
        'day_of_week',
        'period',
        'start_time',
        'end_time',
        'is_deleted',
        'is_synced',
    ];

    /**
     * Get the classroom for the schedule.
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * Get the course for the schedule.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the teacher for the schedule.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
