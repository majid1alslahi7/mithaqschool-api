<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceItemSeeder extends Seeder
{
    public function run()
    {
        $invoices = DB::table('student_invoices')->pluck('id')->all();
        $feeTypes = DB::table('fee_types')->pluck('id')->all();

        if (empty($invoices) || empty($feeTypes)) {
            return;
        }

        $now = now();
        for ($i = 0; $i < 10; $i++) {
            $invoiceId = $invoices[$i % count($invoices)];
            $feeTypeId = $feeTypes[$i % count($feeTypes)];

            $exists = DB::table('invoice_items')
                ->where('invoice_id', $invoiceId)
                ->where('fee_type_id', $feeTypeId)
                ->exists();

            if ($exists) {
                continue;
            }

            DB::table('invoice_items')->insert([
                'invoice_id' => $invoiceId,
                'fee_type_id' => $feeTypeId,
                'amount' => 3000 + ($i * 500),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
