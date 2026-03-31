<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FeeType;

class FeeTypeSeeder extends Seeder
{
    public function run()
    {
        FeeType::insert([
            [
                'name' => 'رسوم تسجيل',
                'description' => 'رسوم تسجيل الطالب للعام الدراسي',
                'is_recurring' => false,
                'default_amount' => 5000,
                'is_active' => true,
            ],
            [
                'name' => 'رسوم شهرية',
                'description' => 'الرسوم الدراسية الشهرية',
                'is_recurring' => true,
                'default_amount' => 15000,
                'is_active' => true,
            ],
            [
                'name' => 'رسوم نقل',
                'description' => 'رسوم باص المدرسة',
                'is_recurring' => true,
                'default_amount' => 8000,
                'is_active' => true,
            ],
        ]);
    }
}