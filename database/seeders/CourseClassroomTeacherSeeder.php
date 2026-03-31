<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseClassroomTeacherSeeder extends Seeder
{
    public function run(): void
    {
        $courses = DB::table('courses')->pluck('id')->all();
        $classrooms = DB::table('classrooms')->pluck('id')->all();
        $teachers = DB::table('teachers')->pluck('id')->all();

        if (empty($courses) || empty($classrooms) || empty($teachers)) {
            return;
        }

        $now = now();
        for ($i = 0; $i < 10; $i++) {
            $courseId = $courses[$i % count($courses)];
            $classroomId = $classrooms[$i % count($classrooms)];
            $teacherId = $teachers[$i % count($teachers)];

            DB::table('course_classroom_teachers')->updateOrInsert(
                [
                    'course_id' => $courseId,
                    'classroom_id' => $classroomId,
                    'teacher_id' => $teacherId,
                ],
                [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
