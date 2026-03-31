<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Grade;


class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
$faker = \Faker\Factory::create('ar_SA');

        $teachers = Teacher::pluck('id')->toArray();
        $grades = Grade::pluck('id')->toArray();

        if (empty($teachers) || empty($grades)) {
            $this->command->info('Cannot seed courses. Please seed teachers and grades first.');
            return;
        }

        for ($i = 0; $i < 20; $i++) {
            Course::create([
                'name' => $faker->sentence(3),
                'description' => $faker->paragraph,
                'teacher_id' => $faker->randomElement($teachers),
                'grade_id' => $faker->randomElement($grades),
            ]);
        }
    }
}
