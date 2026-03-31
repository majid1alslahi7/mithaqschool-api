<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table('users')->pluck('id')->all();
        if (count($users) < 2) {
            return;
        }

        $now = now();
        for ($i = 0; $i < 10; $i++) {
            $senderId = $users[$i % count($users)];
            $receiverId = $users[($i + 1) % count($users)];

            DB::table('messages')->insert([
                'sender_id' => $senderId,
                'receiver_id' => $receiverId,
                'subject' => 'Message ' . ($i + 1),
                'body' => 'Message body ' . ($i + 1),
                'sent_at' => $now->copy()->subMinutes($i * 5),
                'is_read' => $i % 2 === 0,
                'read_at' => $i % 2 === 0 ? $now->copy()->subMinutes($i * 3) : null,
                'is_deleted' => false,
                'is_synced' => false,
            ]);
        }
    }
}
