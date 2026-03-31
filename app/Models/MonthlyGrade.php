<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;

class MonthlyGrade extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'student_number',
        'course_id',
        'academic_year_id',
        'semester_id',
        'month',
        'written_exam',
        'homework',
        'oral_exam',
        'attendance',
        // 'total_score' غير موجود لأنها محسوبة تلقائياً في قاعدة البيانات
    ];

    protected $casts = [
        'written_exam' => 'integer',
        'homework' => 'integer',
        'oral_exam' => 'integer',
        'attendance' => 'integer',
        'total_score' => 'decimal:2',
        'month' => 'integer',
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
