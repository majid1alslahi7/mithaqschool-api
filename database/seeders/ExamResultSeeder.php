<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExamResult;
use App\Models\Exam;
use App\Models\Student;


class ExamResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
$faker = \Faker\Factory::create('ar_SA');

        $exams = Exam::all();
        $students = Student::pluck('id')->toArray();

        if ($exams->isEmpty() || empty($students)) {
            $this->command->info('Cannot seed exam results. Please seed exams and students first.');
            return;
        }

        foreach ($exams as $exam) {
            foreach ($students as $student_id) {
                ExamResult::create([
                    'exam_id' => $exam->id,
                    'student_id' => $student_id,
                    'score' => $faker->numberBetween(0, 100),
                ]);
            }
        }
    }
}
