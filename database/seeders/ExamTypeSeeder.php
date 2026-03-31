<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExamType;

class ExamTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $examTypes = [
            ['name' => 'Monthly Exam'],
            ['name' => 'Midterm Exam'],
            ['name' => 'Final Exam'],
            ['name' => 'Quiz'],
        ];

        foreach ($examTypes as $type) {
            ExamType::create($type);
        }
    }
}
