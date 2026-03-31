<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentInvoice;
use Illuminate\Support\Facades\DB;

class StudentInvoiceSeeder extends Seeder
{
    public function run()
    {
        $studentIds = DB::table('students')->pluck('id')->all();

        if (empty($studentIds)) {
            return;
        }

        $now = now();
        $count = 10;

        for ($i = 0; $i < $count; $i++) {
            $studentId = $studentIds[$i % count($studentIds)];
            $invoiceNumber = 'INV-' . str_pad((string) (1001 + $i), 4, '0', STR_PAD_LEFT);

            StudentInvoice::updateOrCreate(
                ['invoice_number' => $invoiceNumber],
                [
                    'student_id' => $studentId,
                    'total_amount' => 20000 + ($i * 1500),
                    'due_date' => $now->copy()->addDays(7 + $i)->toDateString(),
                    'status' => $i % 3 === 0 ? 'unpaid' : ($i % 3 === 1 ? 'partial' : 'paid'),
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }
}
