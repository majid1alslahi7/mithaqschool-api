<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Course;


class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
$faker = \Faker\Factory::create('ar_SA');

        $students = Student::pluck('id')->toArray();
        $courses = Course::pluck('id')->toArray();

        if (empty($students) || empty($courses)) {
            $this->command->info('Cannot seed attendance. Please seed students and courses first.');
            return;
        }

        foreach ($students as $student_id) {
            foreach ($courses as $course_id) {
                for ($i = 0; $i < 10; $i++) {
                    Attendance::create([
                        'student_id' => $student_id,
                        'course_id' => $course_id,
                        'date' => $faker->dateTimeBetween('-1 month', 'now'),
                        'status' => $faker->randomElement(['present', 'absent', 'late']),
                    ]);
                }
            }
        }
    }
}
