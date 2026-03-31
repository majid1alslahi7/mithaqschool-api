<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SemesterGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentNumbers = DB::table('students')->pluck('enrollment_number')->all();
        $courses = DB::table('courses')->pluck('id')->all();
        $academicYears = DB::table('academic_years')->pluck('id')->all();
        $semesters = DB::table('semesters')->pluck('id')->all();

        if (empty($studentNumbers) || empty($courses) || empty($academicYears) || empty($semesters)) {
            return;
        }

        $now = now();
        for ($i = 0; $i < 10; $i++) {
            $semesterWork = 10 + ($i % 11);
            $examSemester = 15 + ($i % 16);

            DB::table('semester_grades')->insert([
                'student_number' => $studentNumbers[$i % count($studentNumbers)],
                'course_id' => $courses[$i % count($courses)],
                'academic_year_id' => $academicYears[$i % count($academicYears)],
                'semester_id' => $semesters[$i % count($semesters)],
                'semester_work' => $semesterWork,
                'exam_semester' => $examSemester,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
