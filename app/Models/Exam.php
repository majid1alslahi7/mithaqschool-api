<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'date',
        'course_id',
        'teacher_id',
        'exam_type_id',
        'duration_minutes',
        'max_score',
        'academic_year_id',
        'semester_id',
        'is_deleted',
        'is_synced',
    ];

    /**
     * Get the course for the exam.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the teacher for the exam.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the exam type for the exam.
     */
    public function examType()
    {
        return $this->belongsTo(ExamType::class);
    }
    /**
     * Get the academic year for the exam.
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
    /**
     * Get the semester for the exam.
     */
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
