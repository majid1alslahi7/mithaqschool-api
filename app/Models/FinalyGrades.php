<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Searchable;

use Illuminate\Database\Eloquent\Model;

class FinalyGrades extends Model
{
        use HasFactory, Searchable;

    // تصحيح: تم تعديل الخطأ الإملائي في كلمة "fillable"
    protected $fillable = [
        'student_number',
        'course_id',
        'academic_year_id',
        'first_achievement_score',
        'midterm_test',
        'second_achievement_score',
        'final_test',
        'total_score',
    ];

    /**
     * تعريف علاقة "ينتمي إلى" مع موديل الطالب.
     * A final grade belongs to a student.
     */
    public function student()
    {
        // تصحيح: تم تعديل اسم الدالة إلى "belongsTo" واستخدام الصيغة الصحيحة لتعريف المفاتيح
        return $this->belongsTo(Student::class, 'student_number', 'enrollment_number');
    }
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }


    /**
     * تعريف علاقة "ينتمي إلى" مع موديل المادة الدراسية.
     * A final grade belongs to a course.
     */
    public function course()
    {
        // تصحيح: تم تعديل اسم الدالة إلى "belongsTo" واستخدام الصيغة الصحيحة لتعريف المفاتيح
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }
}
