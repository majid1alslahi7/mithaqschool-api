<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BehaviorEvaluationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = DB::table('students')->pluck('id')->all();
        $teachers = DB::table('teachers')->pluck('id')->all();

        if (empty($students) || empty($teachers)) {
            return;
        }

        $now = now();
        for ($i = 0; $i < 10; $i++) {
            $studentId = $students[$i % count($students)];
            $teacherId = $teachers[$i % count($teachers)];

            DB::table('behavior_evaluations')->insert([
                'student_id' => $studentId,
                'evaluator_id' => $teacherId,
                'score' => 60 + ($i % 41),
                'notes' => 'Behavior evaluation ' . ($i + 1),
                'evaluated_at' => $now->copy()->subDays($i),
                'created_at' => $now,
                'updated_at' => $now,
                'is_deleted' => false,
                'is_synced' => false,
            ]);
        }
    }
}
