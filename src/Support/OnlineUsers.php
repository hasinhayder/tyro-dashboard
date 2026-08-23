<?php

namespace HasinHayder\TyroDashboard\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Cache-based online user detection backed by the heartbeat API.
 *
 * Public API:
 * - `OnlineUsers::heartbeatKey($userId)` — cache key for a user's heartbeat
 * - `OnlineUsers::touch($userId)` — record a heartbeat beat for a user
 * - `OnlineUsers::isOnline($userId)` — whether the user heartbeated within the TTL window
 * - `OnlineUsers::onlineUserIds()` — string IDs of all users inside the TTL window
 *
 * Every beat (re)writes a per-user cache key whose TTL defines the online
 * window, so presence data expires on its own with no cleanup. User IDs are
 * plucked in a single id-only query and resolved through chunked Cache::many()
 * calls (500 keys per batch), keeping detection O(1) cache reads per user for
 * large user bases. Keys are scoped per user only — no global keys — so
 * multi-tenant/host-name deployments never collide.
 */
class OnlineUsers {
    public static function heartbeatKey($userId): string {
        return 'tyro_dashboard_heartbeat_'.$userId;
    }

    public static function touch($userId): void {
        Cache::put(static::heartbeatKey($userId), now()->getTimestamp(), static::ttl());
    }

    public static function isOnline($userId): bool {
        return Cache::has(static::heartbeatKey($userId));
    }

    public static function onlineUserIds(): Collection {
        // No beats can exist while the feature is off — skip the full-table scan
        if (! config('tyro-dashboard.features.heartbeat', true)) {
            return collect();
        }

        $userModel = config('tyro-dashboard.user_model', 'App\\Models\\User');

        if (! class_exists($userModel)) {
            return collect();
        }

        try {
            $keyName = (new $userModel)->getKeyName();
            $keys = [];

            foreach ($userModel::query()->pluck($keyName) as $id) {
                $keys[static::heartbeatKey($id)] = (string) $id;
            }
        } catch (\Throwable $e) {
            return collect();
        }

        if ($keys === []) {
            return collect();
        }

        $online = [];

        foreach (array_chunk(array_keys($keys), 500) as $chunk) {
            $present = array_filter(Cache::many($chunk), fn ($value) => $value !== null);

            foreach (array_keys($present) as $key) {
                $online[] = $keys[$key];
            }
        }

        return collect($online)->values();
    }

    protected static function ttl(): int {
        return max(1, (int) config('tyro-dashboard.heartbeat_ttl', 600));
    }
}
