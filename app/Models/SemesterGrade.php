<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemesterGrade extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'student_number',
        'course_id',
        'academic_year_id',
        'semester_id',
        'semester_work',
        'exam_semester',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_number', 'enrollment_number');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
