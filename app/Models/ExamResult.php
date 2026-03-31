<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'exam_id',
        'student_id',
        'score',
        'grade',
        'grade_id',
        'remarks',
        'is_deleted',
        'is_synced',
    ];

    /**
     * Get the exam for the result.
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the student for the result.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the grade scale for the result.
     */
    public function gradeScale()
    {
        return $this->belongsTo(GradesScale::class, 'grade_id');
    }
}
