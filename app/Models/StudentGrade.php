<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentGrade extends Model
{
    use HasFactory, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'course_id',
        'score',
        'term',
        'assessment_type',
        'max_score',
        'grade_id',
        'is_deleted',
        'is_synced',
    ];

    /**
     * Get the student for the grade.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the course for the grade.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the grade scale for the grade.
     */
    public function gradeScale()
    {
        return $this->belongsTo(GradesScale::class, 'grade_id');
    }
}
