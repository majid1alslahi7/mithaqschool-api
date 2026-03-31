<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;

class Homework extends Model
{
    use HasFactory, Searchable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'homeworks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'due_date',
        'student_id',
        'teacher_id',
        'course_id',
        'classroom_id',
        'is_deleted',
        'is_synced',
    ];

    /**
     * Get the student that owns the homework.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the teacher that owns the homework.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the course that owns the homework.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the classroom that owns the homework.
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * Get the submissions for the homework.
     */
    public function submissions()
    {
        return $this->hasMany(HomeworkSubmission::class);
    }
}
