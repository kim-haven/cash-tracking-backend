<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\UserNotification;

class UserNotificationSync
{
    public static function syncAppVersionNotice(User $user, ?string $clientVersion): void
    {
        $server = (string) config('cash_track.client_version', '');
        if ($server === '') {
            return;
        }
        if (! is_string($clientVersion) || $clientVersion === '') {
            return;
        }
        if ($clientVersion === $server) {
            return;
        }

        $dedupeKey = 'app_version:'.$server;

        UserNotification::query()->firstOrCreate(
            [
                'user_id' => $user->id,
                'dedupe_key' => $dedupeKey,
            ],
            [
                'type' => 'app_version',
                'body' => "A new app version ({$server}) is available. Refresh the page to load the latest build.",
            ]
        );
    }

    public static function notifyRoleChanged(User $user, UserRole $newRole): void
    {
        UserNotification::query()->create([
            'user_id' => $user->id,
            'type' => 'role_changed',
            'body' => 'Your role was updated to '.$newRole->displayLabel().'.',
            'dedupe_key' => null,
        ]);
    }
}
