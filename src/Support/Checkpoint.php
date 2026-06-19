<?php

namespace HasinHayder\TyroDashboard\Support;

use HasinHayder\TyroCheckpoint\Exceptions\CheckpointException;
use HasinHayder\TyroCheckpoint\Services\CheckpointService;
use HasinHayder\TyroCheckpoint\TyroCheckpointServiceProvider;
use Illuminate\Support\Collection;

/**
 * Bridge to the optional "hasinhayder/tyro-checkpoint" development package.
 *
 * Tyro Checkpoint is local-dev only and stores its metadata in
 * storage/tyro-checkpoints/checkpoints.json. This class reads that file
 * directly for listing/checkpoint metadata and delegates all database-touching
 * operations (create, restore, delete, encrypt, flush) to the package's
 * CheckpointService so they work during HTTP requests (the package only
 * registers its Artisan commands in console context).
 */
class Checkpoint {
    /**
     * Whether the tyro-checkpoint package is installed.
     */
    public function isAvailable(): bool {
        return class_exists(TyroCheckpointServiceProvider::class);
    }

    /**
     * Resolve the package's CheckpointService (bound unconditionally by the
     * package provider, so it works in HTTP requests unlike its Artisan commands).
     */
    protected function service(): ?CheckpointService {
        if (! class_exists(CheckpointService::class)) {
            return null;
        }

        try {
            return app(CheckpointService::class);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Absolute path to the checkpoints metadata file.
     */
    public function metadataPath(): string {
        return rtrim((string) config('tyro-checkpoint.storage_path', storage_path('tyro-checkpoints')), '/').DIRECTORY_SEPARATOR.'checkpoints.json';
    }

    /**
     * Load all checkpoints, newest first.
     *
     * @return array<int, array>
     */
    public function all(): array {
        return $this->readMetadata()
            ->sortByDesc('created_at')
            ->values()
            ->map(fn (array $c) => $this->normalize($c))
            ->all();
    }

    /**
     * Find a single checkpoint by id or name.
     */
    public function find(string $idOrName): ?array {
        $match = $this->readMetadata()->first(fn (array $c) => $this->matches($c, $idOrName));

        return $match ? $this->normalize($match) : null;
    }

    /**
     * Total size in bytes of all stored snapshot files.
     */
    public function totalSize(): int {
        return (int) $this->readMetadata()->sum('size');
    }

    /**
     * Create a new checkpoint via the package service.
     * Returns 0 on success, non-zero on failure.
     */
    public function create(?string $name, ?string $note = null, bool $encrypt = false): int {
        $service = $this->service();
        if (! $service) {
            return 1;
        }

        try {
            $cleanName = filled($name) ? $this->sanitizeName($name) : null;
            $service->create($cleanName, filled($note) ? $note : null, $encrypt);

            return 0;
        } catch (\Throwable $e) {
            return 1;
        }
    }

    /**
     * Restore a checkpoint via the package service.
     */
    public function restore(string $idOrName): int {
        $service = $this->service();
        if (! $service) {
            return 1;
        }

        try {
            $service->restore($idOrName, true);

            return 0;
        } catch (\Throwable $e) {
            return 1;
        }
    }

    /**
     * Delete a checkpoint via the package service.
     */
    public function delete(string $idOrName): int {
        $service = $this->service();
        if (! $service) {
            return 1;
        }

        try {
            $service->delete($idOrName);

            return 0;
        } catch (CheckpointException $e) {
            return 1;
        } catch (\Throwable $e) {
            return 1;
        }
    }

    /**
     * Delete all unlocked checkpoints via the package service.
     * Locked checkpoints are preserved.
     */
    public function flush(): int {
        $service = $this->service();
        if (! $service) {
            return 1;
        }

        $failed = 0;
        foreach ($this->all() as $cp) {
            if (! empty($cp['locked'])) {
                continue;
            }

            $identifier = (string) $cp['id'] !== '0' ? (string) $cp['id'] : $cp['name'];
            try {
                $service->delete($identifier);
            } catch (\Throwable $e) {
                $failed++;
            }
        }

        return $failed === 0 ? 0 : 1;
    }

    /**
     * Rename a checkpoint (metadata-only).
     * Returns the new name, or null if not found / invalid.
     */
    public function rename(string $idOrName, string $newName): ?string {
        $newName = $this->sanitizeName($newName);
        if ($newName === '') {
            return null;
        }

        $result = null;

        $this->mutate(function (Collection $items) use ($idOrName, $newName, &$result) {
            foreach ($items as $key => $c) {
                if ($this->matches($c, $idOrName)) {
                    $items[$key] = array_merge($c, ['name' => $newName]);
                    $result = $newName;

                    return true;
                }
            }

            return false;
        });

        return $result;
    }

    /**
     * Set/update the note for a checkpoint (metadata-only, no DB).
     */
    public function setNote(string $idOrName, ?string $note): bool {
        return $this->mutate(function (Collection $items) use ($idOrName, $note) {
            foreach ($items as $key => $c) {
                if ($this->matches($c, $idOrName)) {
                    $items[$key] = array_merge($c, ['note' => filled($note) ? trim((string) $note) : null]);

                    return true;
                }
            }

            return false;
        });
    }

    /**
     * Toggle the locked state of a checkpoint (metadata-only).
     * Returns the new locked state, or null if not found.
     */
    public function toggleLock(string $idOrName): ?bool {
        $result = null;

        $this->mutate(function (Collection $items) use ($idOrName, &$result) {
            foreach ($items as $key => $c) {
                if ($this->matches($c, $idOrName)) {
                    $newLocked = ! ($c['locked'] ?? false);
                    $items[$key] = array_merge($c, ['locked' => $newLocked]);
                    $result = $newLocked;

                    return true;
                }
            }

            return false;
        });

        return $result;
    }

    /**
     * Toggle the flagged state of a checkpoint (metadata-only).
     * Returns the new flagged state, or null if not found.
     */
    public function toggleFlag(string $idOrName): ?bool {
        $result = null;

        $this->mutate(function (Collection $items) use ($idOrName, &$result) {
            foreach ($items as $key => $c) {
                if ($this->matches($c, $idOrName)) {
                    $newFlagged = ! ($c['flagged'] ?? false);
                    $items[$key] = array_merge($c, ['flagged' => $newFlagged]);
                    $result = $newFlagged;

                    return true;
                }
            }

            return false;
        });

        return $result;
    }

    /**
     * Encrypt an existing checkpoint in place via the package service.
     */
    public function encrypt(string $idOrName): int {
        $service = $this->service();
        if (! $service) {
            return 1;
        }

        try {
            $service->encrypt($idOrName);

            return 0;
        } catch (\Throwable $e) {
            return 1;
        }
    }

    /**
     * Run a mutate callback against the metadata collection and persist it.
     *
     * The callback receives a Collection of checkpoint arrays (mutated by
     * reference) and must return true when a change was made.
     */
    protected function mutate(callable $callback): bool {
        $path = $this->metadataPath();

        if (! is_file($path)) {
            return false;
        }

        $items = $this->readMetadata();
        $changed = (bool) $callback($items);

        if (! $changed) {
            return false;
        }

        $this->writeMetadata($items);

        return true;
    }

    protected function readMetadata(): Collection {
        $path = $this->metadataPath();

        if (! is_file($path)) {
            return collect();
        }

        try {
            $decoded = json_decode((string) file_get_contents($path), true);
        } catch (\Throwable $e) {
            $decoded = null;
        }

        return is_array($decoded) ? collect($decoded) : collect();
    }

    protected function writeMetadata(Collection $items): void {
        $path = $this->metadataPath();

        $dir = dirname($path);
        if (! is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }

        $tmp = $path.'.tmp';
        file_put_contents(
            $tmp,
            json_encode($items->values()->all(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n"
        );

        rename($tmp, $path);
    }

    /**
     * Normalize a raw checkpoint record for display.
     */
    protected function normalize(array $c): array {
        $size = (int) ($c['size'] ?? 0);

        return [
            'id' => (int) ($c['id'] ?? 0),
            'name' => (string) ($c['name'] ?? ''),
            'path' => (string) ($c['path'] ?? ''),
            'size' => $size,
            'size_for_humans' => $this->formatBytes($size),
            'created_at' => (string) ($c['created_at'] ?? ''),
            'locked' => (bool) ($c['locked'] ?? false),
            'flagged' => (bool) ($c['flagged'] ?? false),
            'encrypted' => (bool) ($c['encrypted'] ?? false),
            'note' => isset($c['note']) ? (string) $c['note'] : null,
            'driver' => isset($c['driver']) ? (string) $c['driver'] : null,
            'database' => isset($c['database']) ? (string) $c['database'] : null,
            'exists_on_disk' => ($c['path'] ?? '') !== '' && is_file((string) $c['path']),
        ];
    }

    protected function matches(array $c, string $idOrName): bool {
        return (string) ($c['id'] ?? '') === $idOrName || ($c['name'] ?? '') === $idOrName;
    }

    public function formatBytes(int $bytes): string {
        if ($bytes < 1024) {
            return $bytes.' B';
        }
        if ($bytes < 1048576) {
            return round($bytes / 1024, 1).' KB';
        }
        if ($bytes < 1073741824) {
            return round($bytes / 1048576, 1).' MB';
        }

        return round($bytes / 1073741824, 2).' GB';
    }

    /**
     * Keep checkpoint names filesystem-safe (mirrors the package convention).
     */
    protected function sanitizeName(string $name): string {
        $name = trim($name);

        return preg_replace('/[^A-Za-z0-9_\-]/', '_', $name) ?? $name;
    }
}
