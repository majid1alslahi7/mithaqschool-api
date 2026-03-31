<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdjustmentSeeder extends Seeder
{
    public function run()
    {
        $students = DB::table('students')->pluck('id')->all();
        $invoices = DB::table('student_invoices')->pluck('id')->all();

        if (empty($students) || empty($invoices)) {
            return;
        }

        $types = ['discount', 'fine', 'scholarship', 'manual'];
        $now = now();

        for ($i = 0; $i < 10; $i++) {
            DB::table('adjustments')->insert([
                'student_id' => $students[$i % count($students)],
                'invoice_id' => $invoices[$i % count($invoices)],
                'type' => $types[$i % count($types)],
                'amount' => 500 + ($i * 250),
                'reason' => 'Adjustment reason ' . ($i + 1),
                'created_by' => '11111111-1111-1111-1111-111111111111',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
