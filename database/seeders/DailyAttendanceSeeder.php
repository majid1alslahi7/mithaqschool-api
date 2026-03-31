<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DailyAttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = DB::table('students')->pluck('id')->all();
        if (empty($students)) {
            return;
        }

        $now = now();
        $statuses = ['present', 'absent', 'late', 'excused'];

        for ($i = 0; $i < 10; $i++) {
            $studentId = $students[$i % count($students)];
            $date = $now->copy()->subDays($i)->toDateString();

            $exists = DB::table('daily_attendances')
                ->where('student_id', $studentId)
                ->where('date', $date)
                ->exists();

            if ($exists) {
                continue;
            }

            DB::table('daily_attendances')->insert([
                'student_id' => $studentId,
                'date' => $date,
                'status' => $statuses[$i % count($statuses)],
                'notes' => 'Attendance note ' . ($i + 1),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
