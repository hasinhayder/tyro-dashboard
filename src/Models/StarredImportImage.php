<?php

namespace HasinHayder\TyroDashboard\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StarredImportImage extends Model {
    protected $table = 'tyro_starred_import_images';

    protected $fillable = [
        'user_id',
        'star_key',
        'provider',
        'external_id',
        'alt',
        'author',
        'thumb_url',
        'preview_url',
        'download_url',
        'download_location',
        'source_url',
        'payload',
        'starred_at',
    ];

    protected function casts(): array {
        return [
            'payload' => 'array',
            'starred_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo {
        return $this->belongsTo(config('tyro-dashboard.user_model', 'App\Models\User'));
    }

    public function toImporterArray(): array {
        return [
            'star_key' => $this->star_key,
            'provider' => $this->provider,
            'id' => $this->external_id,
            'alt' => $this->alt,
            'author' => $this->author,
            'thumb' => $this->thumb_url,
            'preview' => $this->preview_url,
            'download_url' => $this->download_url,
            'download_location' => $this->download_location,
            'source_url' => $this->source_url,
            'raw' => $this->payload,
            'starred_at' => $this->starred_at?->toIso8601String(),
        ];
    }
}
