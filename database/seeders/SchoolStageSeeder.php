<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        for ($i = 1; $i <= 10; $i++) {
            DB::table('school_stages')->updateOrInsert(
                ['name' => 'Stage ' . $i],
                [
                    'description' => 'Stage description ' . $i,
                    'order_index' => $i,
                    'is_deleted' => false,
                    'is_synced' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
