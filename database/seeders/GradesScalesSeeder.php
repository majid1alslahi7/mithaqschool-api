<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradesScalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        $scales = [
            ['A+', 97, 100, '#2ecc71'],
            ['A', 93, 96.99, '#27ae60'],
            ['B+', 88, 92.99, '#3498db'],
            ['B', 83, 87.99, '#2980b9'],
            ['C+', 78, 82.99, '#f1c40f'],
            ['C', 73, 77.99, '#f39c12'],
            ['D+', 68, 72.99, '#e67e22'],
            ['D', 60, 67.99, '#d35400'],
            ['F', 0, 59.99, '#e74c3c'],
            ['FX', 0, 49.99, '#c0392b'],
        ];

        foreach ($scales as $index => $scale) {
            DB::table('grades_scales')->updateOrInsert(
                ['grade_name' => $scale[0]],
                [
                    'min_percentage' => $scale[1],
                    'max_percentage' => $scale[2],
                    'description' => 'Scale ' . ($index + 1),
                    'color_code' => $scale[3],
                    'is_deleted' => false,
                    'is_synced' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
