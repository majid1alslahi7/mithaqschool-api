<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\Course;
use App\Models\Teacher;


class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
$faker = \Faker\Factory::create('ar_SA');

        $examTypes = ExamType::pluck('id')->toArray();
        $courses = Course::pluck('id')->toArray();
        $teachers = Teacher::pluck('id')->toArray();

        if (empty($examTypes) || empty($courses) || empty($teachers)) {
            $this->command->info('Cannot seed exams. Please seed exam types, courses and teachers first.');
            return;
        }

        for ($i = 0; $i < 30; $i++) {
            Exam::create([
                'name' => $faker->sentence(2),
                'exam_type_id' => $faker->randomElement($examTypes),
                'course_id' => $faker->randomElement($courses),
                'teacher_id' => $faker->randomElement($teachers),
                'date' => $faker->dateTimeBetween('now', '+1 month'),
                'max_score' => $faker->numberBetween(50, 100),
            ]);
        }
    }
}
