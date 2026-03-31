<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseClassroomTeacher extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'course_id',
        'classroom_id',
        'teacher_id',
    ];

    protected $searchableFields = [
        'course.name',
        'classroom.name',
        'teacher.enrollment_number',
        'teacher.f_name',
        'teacher.l_name',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
