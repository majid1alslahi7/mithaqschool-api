<?php

namespace App\Http\Requests\Concerns;

trait NormalizesUserIdentifiers
{
    protected function normalizeUserIdentifiers(): void
    {
        if (empty($this->all())) {
            $content = $this->getContent();
            if (! empty($content)) {
                $decoded = json_decode($content, true);
                if (is_array($decoded)) {
                    $this->merge($decoded);
                }
            }
        }

        $userIds = $this->input('user_ids');

        if ($userIds === null) {
            $userIds = $this->input('users');
        }

        if (is_array($this->input('user_id'))) {
            $userIds = $this->input('user_id');
            $this->offsetUnset('user_id');
        }

        if (is_string($userIds)) {
            $userIds = array_values(array_filter(array_map('trim', explode(',', $userIds)), 'strlen'));
        } elseif (is_int($userIds) || (is_string($userIds) && ctype_digit($userIds))) {
            $userIds = [(int) $userIds];
        }

        if ($userIds !== null) {
            $this->merge(['user_ids' => $userIds]);
        }
    }
}
