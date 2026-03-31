<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherCoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = DB::table('teachers')->pluck('id')->all();
        $courses = DB::table('courses')->pluck('id')->all();

        if (empty($teachers) || empty($courses)) {
            return;
        }

        $now = now();
        for ($i = 0; $i < 10; $i++) {
            $teacherId = $teachers[$i % count($teachers)];
            $courseId = $courses[$i % count($courses)];

            DB::table('teacher_courses')->updateOrInsert(
                [
                    'teacher_id' => $teacherId,
                    'course_id' => $courseId,
                    'academic_year' => '2025/2026',
                    'semester' => $i % 2 === 0 ? 'Semester 1' : 'Semester 2',
                ],
                [
                    'created_at' => $now,
                    'updated_at' => $now,
                    'is_deleted' => false,
                    'is_synced' => false,
                ]
            );
        }
    }
}
