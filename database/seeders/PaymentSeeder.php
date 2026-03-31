<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        $students = DB::table('students')->pluck('id')->all();
        $invoices = DB::table('student_invoices')->pluck('id')->all();

        if (empty($students) || empty($invoices)) {
            return;
        }

        $methods = ['cash', 'bank', 'online'];
        $now = now();

        for ($i = 0; $i < 10; $i++) {
            DB::table('payments')->insert([
                'student_id' => $students[$i % count($students)],
                'invoice_id' => $invoices[$i % count($invoices)],
                'amount_paid' => 5000 + ($i * 750),
                'payment_method' => $methods[$i % count($methods)],
                'payment_date' => $now->copy()->subDays($i),
                'reference_number' => 'PAY-' . str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
