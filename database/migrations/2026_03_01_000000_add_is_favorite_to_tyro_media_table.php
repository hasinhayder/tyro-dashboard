<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (! Schema::hasTable('tyro_media')) {
            return;
        }

        if (! Schema::hasColumn('tyro_media', 'is_favorite')) {
            Schema::table('tyro_media', function (Blueprint $table) {
                $table->boolean('is_favorite')->default(false)->after('alt_text')->index();
            });
        }
    }

    public function down(): void {
        if (Schema::hasTable('tyro_media') && Schema::hasColumn('tyro_media', 'is_favorite')) {
            Schema::table('tyro_media', function (Blueprint $table) {
                $table->dropIndex(['is_favorite']);
                $table->dropColumn('is_favorite');
            });
        }
    }
};
