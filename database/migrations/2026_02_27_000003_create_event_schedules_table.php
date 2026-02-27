<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->dateTime('scheduled_at')->nullable();   // for `once` type
            $table->string('time', 5)->nullable();          // HH:MM for `repeating`
            $table->tinyInteger('day_of_week')->nullable(); // ISO 1=Mon … 7=Sun
            $table->json('week_of_month')->nullable();      // e.g. [1, 3] — 1st and 3rd occurrence
            $table->dateTime('next_run_at')->nullable();    // cached next trigger time
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_schedules');
    }
};
