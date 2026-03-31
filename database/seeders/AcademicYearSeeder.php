<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AcademicYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $startYear = now()->year - 4;
        $now = now();

        for ($i = 0; $i < 10; $i++) {
            $year = $startYear + $i;
            $start = Carbon::create($year, 9, 1);
            $end = (clone $start)->addYear()->subDay();

            DB::table('academic_years')->updateOrInsert(
                ['name' => $start->year . '/' . ($start->year + 1)],
                [
                    'start_date' => $start->toDateString(),
                    'end_date' => $end->toDateString(),
                    'is_active' => $i === 9,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
