<?php

namespace HasinHayder\TyroDashboard\Traits;

use HasinHayder\TyroDashboard\Models\Media;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * Provides media library access for the User model.
 *
 * Available since: Tyro Dashboard 1.45.0.
 *
 * Requires: tyro_media table (provided by the Tyro Dashboard migration).
 *           The User model's primary key is used as tyro_media.user_id.
 * No columns on the users table are required by this trait.
 */
trait HasMedia {
    /**
     * All media uploaded by this user.
     */
    public function media(): HasMany {
        return $this->hasMany(Media::class, 'user_id');
    }

    /**
     * Get the user's media library, newest first.
     *
     * @return Collection<int, \HasinHayder\TyroDashboard\Models\Media>
     */
    public function mediaLibrary(): Collection {
        return $this->media()->latest()->get();
    }

    /**
     * Get a paginated chunk of the user's media library.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<int, \HasinHayder\TyroDashboard\Models\Media>
     */
    public function mediaLibraryPaginated(int $perPage = 24) {
        return $this->media()->latest()->paginate($perPage);
    }

    /**
     * Resolve the URL for a given media variant.
     *
     * @param  Media  $media
     * @param  string  $variant  One of: original, webp, thumbnail
     * @return string
     */
    public function mediaUrl(Media $media, string $variant = 'original'): string {
        $path = $this->resolveMediaPath($media, $variant);

        return Storage::disk($media->disk)->url($path);
    }

    /**
     * Get the relative disk paths for all variants of a media record.
     *
     * @param  \HasinHayder\TyroDashboard\Models\Media  $media
     * @return array{original: string, webp: ?string, thumbnail: ?string}
     */
    public function mediaPaths(Media $media): array {
        return [
            'original' => $media->path,
            'webp' => $media->webp_path,
            'thumbnail' => $media->thumbnail_path,
        ];
    }

    /**
     * Delete one of the user's own media records and its generated files.
     *
     * Returns false (no-op) when the media is not owned by this user, so
     * consumers can never accidentally delete another user's files via the trait.
     *
     * @param  \HasinHayder\TyroDashboard\Models\Media  $media
     * @param  bool  $flushCache  Flush the media cache after deletion (set false when deleting in a loop).
     * @return bool  True when deleted, false when not owned by this user.
     */
    public function deleteMedia(Media $media, bool $flushCache = true): bool {
        if (! $this->ownsMedia($media)) {
            return false;
        }

        $this->purgeMediaFiles($media);
        $media->delete();

        if ($flushCache) {
            $this->flushMediaCache();
        }

        return true;
    }

    /**
     * Delete one or many of the user's own media records by id.
     *
     * Ownership is enforced via the scoped query, so ids that do not belong
     * to this user are silently ignored. Returns the count of deleted records.
     *
     * @param  int|array<int>  $ids
     * @return int
     */
    public function deleteMediaById(int|array $ids): int {
        $ids = array_values((array) $ids);

        $deleted = 0;
        $this->media()
            ->whereIn((new Media())->getKeyName(), $ids)
            ->get()
            ->each(function (Media $media) use (&$deleted) {
                if ($this->deleteMedia($media, flushCache: false)) {
                    $deleted++;
                }
            });

        if ($deleted > 0) {
            $this->flushMediaCache();
        }

        return $deleted;
    }

    /**
     * Determine whether a media record belongs to this user.
     *
     * @param  \HasinHayder\TyroDashboard\Models\Media  $media
     * @return bool
     */
    public function ownsMedia(Media $media): bool {
        return $media->user_id === $this->getKey();
    }

    /**
     * Resolve the relative path for a variant, applying the same fallback
     * behavior as the Media model's accessors.
     *
     * @param  \HasinHayder\TyroDashboard\Models\Media  $media
     * @param  string  $variant
     * @return string
     */
    protected function resolveMediaPath(Media $media, string $variant): string {
        return match ($variant) {
            'webp' => $media->webp_path ?? $media->path,
            'thumbnail', 'thumb' => $media->thumbnail_path ?? $media->path,
            default => $media->path,
        };
    }

    /**
     * Remove the original, WebP, and thumbnail files for a media record.
     *
     * @param  \HasinHayder\TyroDashboard\Models\Media  $media
     * @return void
     */
    protected function purgeMediaFiles(Media $media): void {
        Storage::disk($media->disk)->delete($media->path);

        if ($media->webp_path) {
            Storage::disk($media->disk)->delete($media->webp_path);
        }

        if ($media->thumbnail_path) {
            Storage::disk($media->disk)->delete($media->thumbnail_path);
        }
    }

    /**
     * Flush the optional consumer media cache after a mutation.
     *
     * @return void
     */
    protected function flushMediaCache(): void {
        if (class_exists(\App\Support\BlogCache::class) && method_exists(\App\Support\BlogCache::class, 'flushMedia')) {
            \App\Support\BlogCache::flushMedia();
        }
    }
}
