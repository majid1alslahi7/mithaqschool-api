<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\User;
use App\Models\Guardian;
use App\Models\Classroom;
use App\Models\Grade;


class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($users)
    {
$faker = \Faker\Factory::create('ar_SA');
        $start_number = 20260000;

        // Get the last enrollment number to continue the sequence
        $lastStudent = Student::orderBy('enrollment_number', 'desc')->first();
        if ($lastStudent) {
            $start_number = $lastStudent->enrollment_number + 1;
        }

        $parents = Guardian::pluck('id')->toArray();
        $classrooms = Classroom::pluck('id')->toArray();
        $grades = Grade::pluck('id')->toArray();

        if (empty($parents) || empty($classrooms) || empty($grades)) {
            $this->command->info('Cannot seed students. Please seed parents, classrooms, and grades first.');
            return;
        }

        foreach ($users as $key => $user) {
            Student::create([
                'enrollment_number' => $start_number + $key,
                'f_name' => $faker->firstName,
                'l_name' => $faker->lastName,
                'gender' => $faker->randomElement(['ذكر', 'أنثى']),
                
                'birth_date' => $faker->date('Y-m-d', '2010-01-01'),
                'address' => $faker->address,
                'parent_id' => $faker->randomElement($parents),
                'classroom_id' => $faker->randomElement($classrooms),
                'grade_id' => $faker->randomElement($grades),
                'user_id' => $user->id,
                'is_active' => $faker->boolean(90),
            ]);
        }
    }
}
