<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use App\Services\UserNotificationSync;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserNotificationsController extends Controller
{
    /**
     * List notifications for the signed-in user (last 10 days).
     * Optional ?client_version= — if it differs from config, an app-version row is created once per user.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            abort(401);
        }

        UserNotification::query()
            ->where('user_id', $user->id)
            ->where('created_at', '<', now()->subDays(10))
            ->delete();

        $clientVersion = $request->query('client_version');
        if (is_string($clientVersion) && $clientVersion !== '') {
            UserNotificationSync::syncAppVersionNotice($user, $clientVersion);
        }

        $rows = $user->notifications()
            ->where('created_at', '>=', now()->subDays(10))
            ->orderByDesc('created_at')
            ->limit(100)
            ->get();

        return response()->json([
            'data' => $rows->map(fn (UserNotification $n) => [
                'id' => (string) $n->id,
                'type' => $n->type,
                'text' => $n->body,
                'read' => $n->read_at !== null,
                'created_at' => $n->created_at?->toIso8601String(),
            ]),
            'server_client_version' => config('cash_track.client_version'),
        ]);
    }

    /**
     * Mark notifications as read. Body: { "ids": [1,2] } and/or { "mark_all": true }.
     */
    public function markRead(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            abort(401);
        }

        $markAll = $request->boolean('mark_all');

        if ($markAll) {
            $user->notifications()
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            return response()->json(['message' => 'ok']);
        }

        $validated = $request->validate([
            'ids' => ['required', 'array', 'max:200'],
            'ids.*' => ['integer'],
        ]);

        $user->notifications()
            ->whereIn('id', $validated['ids'])
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'ok']);
    }
}
