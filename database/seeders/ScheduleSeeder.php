<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classrooms = DB::table('classrooms')->pluck('id')->all();
        $courses = DB::table('courses')->pluck('id')->all();
        $teachers = DB::table('teachers')->pluck('id')->all();

        if (empty($classrooms) || empty($courses) || empty($teachers)) {
            return;
        }

        $days = ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        $periods = ['الأولى', 'الثانية', 'الثالثة', 'الرابعة', 'الخامسة'];
        $now = now();

        for ($i = 0; $i < 10; $i++) {
            $start = now()->setTime(8 + ($i % 5), 0);
            $end = (clone $start)->addMinutes(45);

            DB::table('schedules')->insert([
                'classroom_id' => $classrooms[$i % count($classrooms)],
                'course_id' => $courses[$i % count($courses)],
                'teacher_id' => $teachers[$i % count($teachers)],
                'day_of_week' => $days[$i % count($days)],
                'period' => $periods[$i % count($periods)],
                'start_time' => $start->format('H:i:s'),
                'end_time' => $end->format('H:i:s'),
                'created_at' => $now,
                'updated_at' => $now,
                'is_deleted' => false,
                'is_synced' => false,
            ]);
        }
    }
}
