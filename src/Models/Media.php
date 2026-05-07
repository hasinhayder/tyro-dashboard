<?php

namespace HasinHayder\TyroDashboard\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Media extends Model {
    protected $table = 'tyro_media';

    protected $fillable = [
        'user_id',
        'filename',
        'path',
        'webp_path',
        'thumbnail_path',
        'disk',
        'mime_type',
        'size',
        'alt_text',
        'source_url',
    ];

    public function uploader(): BelongsTo {
        return $this->belongsTo(config('tyro-dashboard.user_model', 'App\Models\User'), 'user_id');
    }

    public function getUrlAttribute(): string {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function getWebpUrlAttribute(): ?string {
        if (! $this->webp_path) {
            return null;
        }

        return Storage::disk($this->disk)->url($this->webp_path);
    }

    public function getThumbnailUrlAttribute(): string {
        if ($this->thumbnail_path) {
            return Storage::disk($this->disk)->url($this->thumbnail_path);
        }

        return $this->url;
    }

    public static function thumbnailUrlFrom(?string $urlOrPath): ?string {
        if (! filled($urlOrPath)) {
            return null;
        }

        if (str_starts_with($urlOrPath, 'http://') || str_starts_with($urlOrPath, 'https://')) {
            $pos = strpos($urlOrPath, '/storage/');
            if ($pos === false) {
                return $urlOrPath;
            }
            $path = substr($urlOrPath, $pos + 9);
        } else {
            $path = ltrim($urlOrPath, '/');
        }

        $media = static::where('path', $path)->first(['disk', 'thumbnail_path']);
        if (! $media || ! $media->thumbnail_path) {
            return str_contains($urlOrPath, '://') ? $urlOrPath : Storage::disk('public')->url($path);
        }

        return Storage::disk($media->disk)->url($media->thumbnail_path);
    }

    public static function webpUrlFrom(?string $urlOrPath): ?string {
        if (! filled($urlOrPath)) {
            return null;
        }

        if (str_starts_with($urlOrPath, 'http://') || str_starts_with($urlOrPath, 'https://')) {
            $pos = strpos($urlOrPath, '/storage/');
            if ($pos === false) {
                return null;
            }
            $path = substr($urlOrPath, $pos + 9);
        } else {
            $path = ltrim($urlOrPath, '/');
        }

        $media = static::where('path', $path)->first(['disk', 'webp_path']);
        if (! $media || ! $media->webp_path) {
            return null;
        }

        return Storage::disk($media->disk)->url($media->webp_path);
    }

    public function getFormattedSizeAttribute(): string {
        $bytes = $this->size;
        if ($bytes < 1024) {
            return $bytes.' B';
        } elseif ($bytes < 1048576) {
            return round($bytes / 1024, 1).' KB';
        } else {
            return round($bytes / 1048576, 1).' MB';
        }
    }

    public function getIsImageAttribute(): bool {
        return str_starts_with($this->mime_type, 'image/');
    }
}
