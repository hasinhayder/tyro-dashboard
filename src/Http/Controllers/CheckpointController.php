<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use HasinHayder\TyroDashboard\Support\Checkpoint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CheckpointController extends BaseController {
    /**
     * Show the Checkpoints management page.
     */
    public function index() {
        $checkpoint = app(Checkpoint::class);
        $checkpoints = $checkpoint->isAvailable() ? $checkpoint->all() : [];
        $totalSize = $checkpoint->isAvailable() ? $checkpoint->totalSize() : 0;

        return view('tyro-dashboard::checkpoints.index', $this->getViewData([
            'available' => $checkpoint->isAvailable(),
            'checkpoints' => $checkpoints,
            'totalSize' => $totalSize,
            'totalSizeForHumans' => $this->formatBytes($totalSize),
            'encryptionKeySet' => $this->encryptionKeySet(),
        ]));
    }

    /**
     * Create a new checkpoint.
     */
    public function create(Request $request, Checkpoint $checkpoint): JsonResponse {
        $this->requireAjax($request);
        $this->guardUnavailable($checkpoint);

        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:100'],
            'note' => ['nullable', 'string', 'max:500'],
            'encrypt' => ['nullable', 'boolean'],
        ]);

        $exit = $checkpoint->create(
            $data['name'] ?? null,
            $data['note'] ?? null,
            (bool) ($data['encrypt'] ?? false),
        );

        if ($exit !== 0) {
            return $this->error('Could not create the checkpoint. Check that the database is reachable.');
        }

        return $this->listResponse('Checkpoint created successfully.');
    }

    /**
     * Restore a checkpoint.
     */
    public function restore(Request $request, Checkpoint $checkpoint): JsonResponse {
        $this->requireAjax($request);
        $this->guardUnavailable($checkpoint);

        $data = $request->validate([
            'identifier' => ['required', 'string', 'max:200'],
        ]);

        if (! $checkpoint->find($data['identifier'])) {
            return $this->error('Checkpoint not found.', 404);
        }

        $exit = $checkpoint->restore($data['identifier']);

        if ($exit !== 0) {
            return $this->error('Could not restore the checkpoint. See the application log for details.');
        }

        return $this->listResponse('Checkpoint restored successfully.');
    }

    /**
     * Delete a checkpoint.
     */
    public function delete(Request $request, Checkpoint $checkpoint): JsonResponse {
        $this->requireAjax($request);
        $this->guardUnavailable($checkpoint);

        $data = $request->validate([
            'identifier' => ['required', 'string', 'max:200'],
        ]);

        $target = $checkpoint->find($data['identifier']);
        if (! $target) {
            return $this->error('Checkpoint not found.', 404);
        }

        if (! empty($target['locked'])) {
            return $this->error('Locked checkpoints cannot be deleted. Unlock it first.', 422);
        }

        $exit = $checkpoint->delete($data['identifier']);

        if ($exit !== 0) {
            return $this->error('Could not delete the checkpoint.');
        }

        return $this->listResponse('Checkpoint deleted.');
    }

    /**
     * Delete all unlocked checkpoints.
     */
    public function flush(Request $request, Checkpoint $checkpoint): JsonResponse {
        $this->requireAjax($request);
        $this->guardUnavailable($checkpoint);

        $checkpoint->flush();

        return $this->listResponse('All unlocked checkpoints deleted.');
    }

    /**
     * Add or update a checkpoint note.
     */
    public function note(Request $request, Checkpoint $checkpoint): JsonResponse {
        $this->requireAjax($request);
        $this->guardUnavailable($checkpoint);

        $data = $request->validate([
            'identifier' => ['required', 'string', 'max:200'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        if (! $checkpoint->find($data['identifier'])) {
            return $this->error('Checkpoint not found.', 404);
        }

        $checkpoint->setNote($data['identifier'], $data['note'] ?? null);

        return $this->listResponse('Note saved.');
    }

    /**
     * Lock or unlock a checkpoint.
     */
    public function toggleLock(Request $request, Checkpoint $checkpoint): JsonResponse {
        $this->requireAjax($request);
        $this->guardUnavailable($checkpoint);

        $data = $request->validate([
            'identifier' => ['required', 'string', 'max:200'],
        ]);

        $target = $checkpoint->find($data['identifier']);
        if (! $target) {
            return $this->error('Checkpoint not found.', 404);
        }

        $locked = $checkpoint->toggleLock($data['identifier']);

        if ($locked === null) {
            return $this->error('Checkpoint not found.', 404);
        }

        return $this->listResponse($locked ? 'Checkpoint locked.' : 'Checkpoint unlocked.');
    }

    /**
     * Flag or unflag a checkpoint.
     */
    public function toggleFlag(Request $request, Checkpoint $checkpoint): JsonResponse {
        $this->requireAjax($request);
        $this->guardUnavailable($checkpoint);

        $data = $request->validate([
            'identifier' => ['required', 'string', 'max:200'],
        ]);

        $target = $checkpoint->find($data['identifier']);
        if (! $target) {
            return $this->error('Checkpoint not found.', 404);
        }

        $flagged = $checkpoint->toggleFlag($data['identifier']);

        if ($flagged === null) {
            return $this->error('Checkpoint not found.', 404);
        }

        return $this->listResponse($flagged ? 'Checkpoint flagged.' : 'Flag removed.');
    }

    /**
     * Encrypt an existing checkpoint in place.
     */
    public function encrypt(Request $request, Checkpoint $checkpoint): JsonResponse {
        $this->requireAjax($request);
        $this->guardUnavailable($checkpoint);

        $data = $request->validate([
            'identifier' => ['required', 'string', 'max:200'],
        ]);

        if (! $this->encryptionKeySet()) {
            return $this->error('No TYRO_CHECKPOINT_ENCRYPTION_KEY is set. Generate one first.', 422);
        }

        if (! $checkpoint->find($data['identifier'])) {
            return $this->error('Checkpoint not found.', 404);
        }

        $exit = $checkpoint->encrypt($data['identifier']);

        if ($exit !== 0) {
            return $this->error('Could not encrypt the checkpoint. Generate an encryption key first.');
        }

        return $this->listResponse('Checkpoint encrypted.');
    }

    /**
     * Build a JSON response containing the freshly rendered checkpoint list.
     */
    protected function listResponse(string $message): JsonResponse {
        $checkpoint = app(Checkpoint::class);
        $checkpoints = $checkpoint->all();
        $totalSize = $checkpoint->totalSize();

        $html = view('tyro-dashboard::checkpoints._list', [
            'checkpoints' => $checkpoints,
            'totalSize' => $totalSize,
        ])->render();

        return response()->json([
            'success' => true,
            'message' => $message,
            'html' => $html,
            'count' => count($checkpoints),
            'totalSize' => $totalSize,
        ]);
    }

    protected function requireAjax(Request $request): void {
        abort_unless($request->ajax() && $request->wantsJson(), 403);
    }

    protected function guardUnavailable(Checkpoint $checkpoint): void {
        abort_unless($checkpoint->isAvailable(), 404, 'Tyro Checkpoint is not installed.');
    }

    protected function error(string $message, int $status = 422): JsonResponse {
        return response()->json(['success' => false, 'message' => $message], $status);
    }

    protected function formatBytes(int $bytes): string {
        return app(Checkpoint::class)->formatBytes($bytes);
    }

    protected function encryptionKeySet(): bool {
        return filled(config('tyro-checkpoint.encryption_key'));
    }

    /**
     * Format an ISO timestamp for display.
     */
    public static function formatDate(string $iso): string {
        try {
            return Carbon::parse($iso)->format('Y-m-d H:i:s');
        } catch (\Throwable $e) {
            return $iso ?: '—';
        }
    }
}
