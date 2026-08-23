<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use HasinHayder\TyroDashboard\Support\OnlineUsers;
use Illuminate\Http\JsonResponse;

class HeartbeatController extends BaseController {
    /**
     * Record a heartbeat beat for the authenticated user.
     *
     * Called by the dashboard JS every five minutes to power cache-based
     * online detection. No audit entry is written on purpose: the high
     * frequency would flood the audit trail.
     */
    public function store(): JsonResponse {
        OnlineUsers::touch(auth()->id());

        return response()->json(['ok' => true]);
    }
}
