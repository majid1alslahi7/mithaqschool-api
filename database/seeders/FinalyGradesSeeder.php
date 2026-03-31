<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinalyGradesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentNumbers = DB::table('students')->pluck('enrollment_number')->all();
        $courses = DB::table('courses')->pluck('id')->all();
        $academicYears = DB::table('academic_years')->pluck('id')->all();

        if (empty($studentNumbers) || empty($courses) || empty($academicYears)) {
            return;
        }

        $now = now();
        for ($i = 0; $i < 10; $i++) {
            $first = 10 + ($i % 11);
            $mid = 15 + ($i % 16);
            $second = 10 + ($i % 11);
            $final = 20 + ($i % 11);

            DB::table('finaly_grades')->insert([
                'student_number' => $studentNumbers[$i % count($studentNumbers)],
                'course_id' => $courses[$i % count($courses)],
                'academic_year_id' => $academicYears[$i % count($academicYears)],
                'first_achievement_score' => $first,
                'midterm_test' => $mid,
                'second_achievement_score' => $second,
                'final_test' => $final,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
