<?php

namespace HasinHayder\TyroDashboard\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class MediaCategory extends Model {
    protected $table = 'tyro_media_categories';

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
    ];

    protected static function booted(): void {
        static::creating(function (MediaCategory $category) {
            if (empty($category->slug)) {
                $category->slug = static::generateUniqueSlug($category->name, $category->user_id);
            }
        });
    }

    public static function generateUniqueSlug(string $name, ?int $userId, ?int $ignoreId = null): string {
        $baseSlug = Str::slug($name);
        if (empty($baseSlug)) {
            $baseSlug = 'category';
        }

        $slug = $baseSlug;
        $counter = 1;

        while (static::where('slug', $slug)
            ->when($userId, fn ($q) => $q->where('user_id', $userId))
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    public function creator(): BelongsTo {
        return $this->belongsTo(config('tyro-dashboard.user_model', 'App\Models\User'), 'user_id');
    }

    public function media(): BelongsToMany {
        return $this->belongsToMany(
            Media::class,
            'tyro_media_category_media',
            'media_category_id',
            'media_id'
        )->withTimestamps();
    }
}
