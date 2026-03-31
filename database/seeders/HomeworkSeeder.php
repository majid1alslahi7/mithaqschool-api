<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HomeworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = DB::table('students')->pluck('id')->all();
        $teachers = DB::table('teachers')->pluck('id')->all();
        $courses = DB::table('courses')->pluck('id')->all();
        $classrooms = DB::table('classrooms')->pluck('id')->all();

        if (empty($teachers) || empty($courses)) {
            return;
        }

        $now = now();
        for ($i = 0; $i < 10; $i++) {
            DB::table('homeworks')->insert([
                'title' => 'Homework ' . ($i + 1),
                'description' => 'Homework description ' . ($i + 1),
                'due_date' => $now->copy()->addDays(3 + $i),
                'student_id' => empty($students) ? null : $students[$i % count($students)],
                'teacher_id' => $teachers[$i % count($teachers)],
                'course_id' => $courses[$i % count($courses)],
                'classroom_id' => empty($classrooms) ? null : $classrooms[$i % count($classrooms)],
                'created_at' => $now,
                'updated_at' => $now,
                'is_deleted' => false,
                'is_synced' => false,
            ]);
        }
    }
}
