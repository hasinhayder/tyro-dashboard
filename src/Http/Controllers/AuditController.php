<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use HasinHayder\Tyro\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AuditController extends BaseController
{
    /**
     * Display a listing of audit logs.
     */
    public function index(Request $request)
    {
        if ($redirect = $this->ensureAuditAvailable()) {
            return $redirect;
        }

        $perPage = (int) $request->get('per_page', 20);
        $perPage = in_array($perPage, [20, 50, 100], true) ? $perPage : 20;

        $query = AuditLog::query()->with('user')->latest('created_at');

        if ($search = trim((string) $request->get('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('event', 'like', "%{$search}%")
                    ->orWhere('auditable_type', 'like', "%{$search}%")
                    ->orWhere('auditable_id', 'like', "%{$search}%")
                    ->orWhere('old_values', 'like', "%{$search}%")
                    ->orWhere('new_values', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($event = trim((string) $request->get('event', ''))) {
            $query->where('event', 'like', "%{$event}%");
        }

        if ($actor = $request->get('actor')) {
            if ($actor === 'system') {
                $query->whereNull('user_id');
            } elseif (is_numeric($actor)) {
                $query->where('user_id', (int) $actor);
            } else {
                $query->whereHas('user', function ($userQuery) use ($actor) {
                    $userQuery->where('name', 'like', "%{$actor}%")
                        ->orWhere('email', 'like', "%{$actor}%");
                });
            }
        }

        if ($from = $request->get('from')) {
            $query->where('created_at', '>=', $from);
        }

        if ($to = $request->get('to')) {
            $query->where('created_at', '<=', $to);
        }

        $logs = $query->paginate($perPage)->withQueryString();

        $eventOptions = AuditLog::query()
            ->select('event')
            ->distinct()
            ->orderBy('event')
            ->limit(200)
            ->pluck('event');

        return view('tyro-dashboard::audits.index', $this->getViewData([
            'logs' => $logs,
            'eventOptions' => $eventOptions,
            'filters' => $request->only(['search', 'event', 'actor', 'from', 'to', 'per_page']),
        ]));
    }

    /**
     * Remove a specific audit log entry.
     */
    public function destroy($id): RedirectResponse
    {
        if ($redirect = $this->ensureAuditAvailable()) {
            return $redirect;
        }

        $log = AuditLog::query()->findOrFail($id);
        $log->delete();

        return redirect()
            ->route('tyro-dashboard.audits.index')
            ->with('success', 'Audit log entry deleted successfully.');
    }

    /**
     * Remove selected audit log entries.
     */
    public function bulkDestroy(Request $request): RedirectResponse
    {
        if ($redirect = $this->ensureAuditAvailable()) {
            return $redirect;
        }

        $validated = $request->validate([
            'selected_ids' => ['required', 'array', 'min:1'],
            'selected_ids.*' => ['integer'],
        ]);

        $deletedCount = AuditLog::query()
            ->whereIn('id', $validated['selected_ids'])
            ->delete();

        return redirect()
            ->route('tyro-dashboard.audits.index', $request->except(['selected_ids']))
            ->with('success', "Deleted {$deletedCount} audit log entries.");
    }

    /**
     * Remove all audit logs.
     */
    public function flush(Request $request): RedirectResponse
    {
        if ($redirect = $this->ensureAuditAvailable()) {
            return $redirect;
        }

        $deletedCount = AuditLog::query()->count();
        AuditLog::query()->delete();

        return redirect()
            ->route('tyro-dashboard.audits.index', $request->except(['_token', '_method']))
            ->with('success', "Flushed {$deletedCount} audit log entries.");
    }

    /**
     * Ensure audit logging is enabled and table exists.
     */
    protected function ensureAuditAvailable(): ?RedirectResponse
    {
        if (!config('tyro-dashboard.features.audit_logs', true) || !config('tyro.audit.enabled', true)) {
            return redirect()->route('tyro-dashboard.index');
        }

        if (!class_exists(AuditLog::class)) {
            return redirect()->route('tyro-dashboard.index');
        }

        $auditTable = config('tyro.tables.audit_logs', 'tyro_audit_logs');

        if (!Schema::hasTable($auditTable)) {
            return redirect()->route('tyro-dashboard.index');
        }

        return null;
    }
}
