<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('collaboration_rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('room_number')->unique();
            $table->string('name');
            $table->string('theme');
            $table->unsignedInteger('max_members')->default(10);
            $table->string('group_name')->nullable();
            $table->string('leader_name')->nullable();
            $table->longText('coloring_data')->nullable();
            $table->longText('tasks_data')->nullable();
            $table->longText('identification_data')->nullable();
            $table->longText('evaluation_data')->nullable();
            $table->timestamps();
        });

        Schema::create('collaboration_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('collaboration_rooms')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('user_name');
            $table->string('no_absen')->nullable();
            $table->string('role')->nullable();
            $table->timestamp('last_active_at')->nullable();
            $table->timestamps();

            $table->unique(['room_id', 'user_id']);
        });

        // Seed 4 initial default rooms
        DB::table('collaboration_rooms')->insert([
            [
                'room_number' => 1,
                'name' => 'Ruang 1',
                'theme' => 'Kanvas 1',
                'max_members' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_number' => 2,
                'name' => 'Ruang 2',
                'theme' => 'Kanvas 2',
                'max_members' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_number' => 3,
                'name' => 'Ruang 3',
                'theme' => 'Kanvas 3',
                'max_members' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_number' => 4,
                'name' => 'Ruang 4',
                'theme' => 'Kanvas 4',
                'max_members' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collaboration_members');
        Schema::dropIfExists('collaboration_rooms');
    }
};
