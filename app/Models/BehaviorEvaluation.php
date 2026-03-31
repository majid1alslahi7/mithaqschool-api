<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BehaviorEvaluation extends Model
{
    use HasFactory, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'evaluator_id',
        'score',
        'notes',
        'evaluated_at',
        'is_deleted',
        'is_synced',
    ];

    /**
     * Get the student that is being evaluated.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the teacher who evaluated the student.
     */
    public function evaluator()
    {
        return $this->belongsTo(Teacher::class, 'evaluator_id');
    }
}
