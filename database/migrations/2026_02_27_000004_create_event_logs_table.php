<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->dateTime('occurred_at');
            $table->json('context')->nullable(); // reading values, schedule info, etc.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_logs');
    }
};
