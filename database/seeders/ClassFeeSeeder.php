<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassFee;

class ClassFeeSeeder extends Seeder
{
    public function run()
    {
        ClassFee::insert([
            [
                'grade_id' => 1,
                'fee_type_id' => 1,
                'amount' => 5000,
                'academic_year' => '2024-2025',
            ],
            [
                'grade_id' => 1,
                'fee_type_id' => 2,
                'amount' => 15000,
                'academic_year' => '2024-2025',
            ],
            [
                'grade_id' => 1,
                'fee_type_id' => 3,
                'amount' => 8000,
                'academic_year' => '2024-2025',
            ],
        ]);
    }
}