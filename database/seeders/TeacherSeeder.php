<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\User;


class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($users)
    {
$faker = \Faker\Factory::create('ar_SA');
        $start_number = 70707070;

        // Get the last enrollment number to continue the sequence
        $lastTeacher = Teacher::orderBy('enrollment_number', 'desc')->first();
        if ($lastTeacher) {
            $start_number = $lastTeacher->enrollment_number + 1;
        }

        foreach ($users as $key => $user) {
            Teacher::create([
                'enrollment_number' => $start_number + $key,
                'f_name' => $faker->firstName,
                'l_name' => $faker->lastName,
                'gender' => $faker->randomElement(['ذكر', 'أنثى'])
                ,
                'birth_date' => $faker->date('Y-m-d', '1990-01-01'),
                'address' => $faker->address,
                'user_id' => $user->id,
                'is_active' => $faker->boolean(90),
            ]);
        }
    }
}
