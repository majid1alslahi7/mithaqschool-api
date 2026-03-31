<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classroom;
use App\Models\Grade;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $grades = Grade::all();

        foreach ($grades as $grade) {
            Classroom::create([
                'name' => 'فصل أ',
                'grade_id' => $grade->id,
            ]);

            Classroom::create([
                'name' => 'فصل ب',
                'grade_id' => $grade->id,
            ]);
        }
    }
}
