<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;

class HomeworkSubmission extends Model
{
    use HasFactory, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'homework_id',
        'submission_date',
        'score',
        'grade',
        'file_url',
        'feedback',
        'status',
        'submitted_at',
        'is_deleted',
        'is_synced',
    ];

    /**
     * Get the student that owns the submission.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the homework that the submission belongs to.
     */
    public function homework()
    {
        return $this->belongsTo(Homework::class);
    }
}
