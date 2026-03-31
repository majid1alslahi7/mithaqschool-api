<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class SystemNotificationService
{
    public function notifyAllUsers(string $title, string $message, array $excludeUserIds = []): void
    {
        $query = User::query()
            ->where('is_active', true)
            ->where('is_deleted', false);

        if (!empty($excludeUserIds)) {
            $query->whereNotIn('id', $excludeUserIds);
        }

        $now = now();

        $query->select('id')->chunkById(500, function ($users) use ($title, $message, $now) {
            $rows = [];
            foreach ($users as $user) {
                $rows[] = [
                    'title' => $title,
                    'message' => $message,
                    'user_id' => $user->id,
                    'is_read' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if (!empty($rows)) {
                Notification::insert($rows);
            }
        });
    }
}
