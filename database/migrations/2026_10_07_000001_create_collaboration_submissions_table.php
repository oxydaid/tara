<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('collaboration_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('room_number')->nullable();
            $table->string('group_name');
            $table->string('theme')->nullable();
            $table->string('leader_name')->nullable();
            $table->json('tasks_data')->nullable();
            $table->json('coloring_data')->nullable();
            $table->json('identification_data')->nullable();
            $table->json('evaluation_data')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collaboration_submissions');
    }
};
