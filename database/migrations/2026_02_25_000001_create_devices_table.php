<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('ieee_address')->unique()->nullable();
            $table->string('friendly_name')->unique();
            $table->enum('type', ['sensor', 'relay', 'switch', 'unknown'])->default('unknown');
            $table->string('model')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('description')->nullable();
            $table->boolean('is_online')->default(false);
            $table->timestamp('last_seen_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
