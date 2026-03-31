<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table('users')->pluck('id')->all();
        if (empty($users)) {
            return;
        }

        $now = now();
        for ($i = 0; $i < 10; $i++) {
            DB::table('notifications')->insert([
                'title' => 'Notification ' . ($i + 1),
                'message' => 'Notification message ' . ($i + 1),
                'user_id' => $users[$i % count($users)],
                'is_read' => $i % 3 === 0,
                'read_at' => $i % 3 === 0 ? $now->copy()->subHours($i) : null,
                'created_at' => $now,
                'updated_at' => $now,
                'is_deleted' => false,
                'is_synced' => false,
            ]);
        }
    }
}
