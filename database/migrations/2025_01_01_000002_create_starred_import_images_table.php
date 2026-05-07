<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (Schema::hasTable('tyro_starred_import_images')) {
            return;
        }

        Schema::create('tyro_starred_import_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('star_key', 64);
            $table->string('provider', 50);
            $table->string('external_id')->nullable();
            $table->text('alt')->nullable();
            $table->string('author')->nullable();
            $table->string('thumb_url', 2048)->nullable();
            $table->string('preview_url', 2048)->nullable();
            $table->string('download_url', 2048)->nullable();
            $table->string('download_location', 2048)->nullable();
            $table->string('source_url', 2048)->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('starred_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'star_key']);
            $table->index(['user_id', 'starred_at']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('tyro_starred_import_images');
    }
};
