<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = DB::table('students')->pluck('id')->all();
        $courses = DB::table('courses')->pluck('id')->all();
        $gradeScales = DB::table('grades_scales')->pluck('id')->all();

        if (empty($students) || empty($courses)) {
            return;
        }

        $now = now();
        for ($i = 0; $i < 10; $i++) {
            DB::table('student_grades')->insert([
                'student_id' => $students[$i % count($students)],
                'course_id' => $courses[$i % count($courses)],
                'score' => 50 + ($i % 51),
                'term' => $i % 2 === 0 ? 'first' : 'second',
                'assessment_type' => $i % 2 === 0 ? 'quiz' : 'exam',
                'max_score' => 100,
                'grade_id' => empty($gradeScales) ? null : $gradeScales[$i % count($gradeScales)],
                'created_at' => $now,
                'updated_at' => $now,
                'is_deleted' => false,
                'is_synced' => false,
            ]);
        }
    }
}
