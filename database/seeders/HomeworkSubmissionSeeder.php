<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HomeworkSubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = DB::table('students')->pluck('id')->all();
        $homeworks = DB::table('homeworks')->pluck('id')->all();

        if (empty($students) || empty($homeworks)) {
            return;
        }

        $now = now();
        for ($i = 0; $i < 10; $i++) {
            $studentId = $students[$i % count($students)];
            $homeworkId = $homeworks[$i % count($homeworks)];

            DB::table('homework_submissions')->insert([
                'student_id' => $studentId,
                'homework_id' => $homeworkId,
                'submission_date' => $now->copy()->subDays($i),
                'score' => 50 + ($i % 51),
                'grade' => 'G' . (($i % 5) + 1),
                'file_url' => 'files/submission_' . ($i + 1) . '.pdf',
                'feedback' => 'Feedback ' . ($i + 1),
                'status' => 'submitted',
                'submitted_at' => $now->copy()->subDays($i),
                'created_at' => $now,
                'updated_at' => $now,
                'is_deleted' => false,
                'is_synced' => false,
            ]);
        }
    }
}
