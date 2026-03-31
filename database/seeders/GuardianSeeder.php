<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guardian;


class GuardianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
$faker = \Faker\Factory::create('ar_SA');
        $start_number = 555000;

        // Get the last enrollment number to continue the sequence
        $lastGuardian = Guardian::orderBy('enrollment_number', 'desc')->first();
        if ($lastGuardian) {
            $start_number = $lastGuardian->enrollment_number + 1;
        }

        for ($i = 0; $i < 10; $i++) {
            Guardian::create([
                'enrollment_number' => $start_number + $i,
                'f_name' => $faker->firstName,
                'l_name' => $faker->lastName,
                'gender' => $faker->randomElement(['ذكر', 'أنثى']),
                
                'address' => $faker->address,
            ]);
        }
    }
}
