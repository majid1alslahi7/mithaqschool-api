<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $academicYears = DB::table('academic_years')->orderBy('start_date')->limit(5)->get();
        if ($academicYears->isEmpty()) {
            return;
        }

        $now = now();
        $count = 0;

        foreach ($academicYears as $year) {
            if ($count >= 10) {
                break;
            }

            $start = Carbon::parse($year->start_date);
            $mid = (clone $start)->addMonths(5)->endOfMonth();
            $end = Carbon::parse($year->end_date);

            $semesters = [
                ['Semester 1', $start, $mid],
                ['Semester 2', $mid->copy()->addDay(), $end],
            ];

            foreach ($semesters as $semester) {
                if ($count >= 10) {
                    break;
                }

                DB::table('semesters')->updateOrInsert(
                    ['academic_year_id' => $year->id, 'name' => $semester[0]],
                    [
                        'start_date' => $semester[1]->toDateString(),
                        'end_date' => $semester[2]->toDateString(),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
                $count++;
            }
        }
    }
}
