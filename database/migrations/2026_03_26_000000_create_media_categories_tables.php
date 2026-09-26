<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (! Schema::hasTable('tyro_media_categories')) {
            Schema::create('tyro_media_categories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
                $table->string('name', 150);
                $table->string('slug', 180);
                $table->text('description')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'slug']);
            });
        }

        if (! Schema::hasTable('tyro_media_category_media')) {
            Schema::create('tyro_media_category_media', function (Blueprint $table) {
                $table->id();
                $table->foreignId('media_category_id')->constrained('tyro_media_categories')->cascadeOnDelete();
                $table->foreignId('media_id')->constrained('tyro_media')->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['media_category_id', 'media_id'], 'media_category_media_unique');
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('tyro_media_category_media');
        Schema::dropIfExists('tyro_media_categories');
    }
};
