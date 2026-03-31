<?php

namespace App\Events;

use App\Models\Student;
use Illuminate\Foundation\Events\Dispatchable;

class StudentRegistered
{
    use Dispatchable;

    public $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }
}