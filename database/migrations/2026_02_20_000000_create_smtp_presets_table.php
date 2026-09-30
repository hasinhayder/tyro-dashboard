<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (Schema::hasTable('tyro_smtp_presets')) {
            return;
        }

        Schema::create('tyro_smtp_presets', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('mailer')->default('smtp');
            $table->string('host')->default('');
            $table->unsignedSmallInteger('port')->default(587);
            $table->string('encryption')->nullable();
            $table->string('username')->nullable();
            $table->text('password')->nullable();
            $table->string('from_address')->nullable();
            $table->string('from_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tyro_smtp_presets');
    }
};
