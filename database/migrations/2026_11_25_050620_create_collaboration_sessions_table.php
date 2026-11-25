<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('collaboration_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('class_label')->nullable();
            $table->string('schedule_label')->nullable();
            $table->boolean('is_active')->default(false)->index();
            $table->timestamps();
        });

        $defaultSessionId = DB::table('collaboration_sessions')->insertGetId([
            'name' => 'Sesi Kolaborasi 1',
            'class_label' => null,
            'schedule_label' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Schema::table('collaboration_rooms', function (Blueprint $table) {
            $table->foreignId('collaboration_session_id')
                ->nullable()
                ->after('id')
                ->constrained('collaboration_sessions')
                ->cascadeOnDelete();
            $table->dropUnique(['room_number']);
            $table->unique(['collaboration_session_id', 'room_number'], 'collab_rooms_session_room_unique');
        });

        DB::table('collaboration_rooms')
            ->whereNull('collaboration_session_id')
            ->update(['collaboration_session_id' => $defaultSessionId]);

        Schema::table('collaboration_submissions', function (Blueprint $table) {
            $table->foreignId('collaboration_session_id')
                ->nullable()
                ->after('id')
                ->constrained('collaboration_sessions')
                ->cascadeOnDelete();
            $table->index(['collaboration_session_id', 'room_number'], 'collab_subs_session_room_idx');
        });

        DB::table('collaboration_submissions')
            ->whereNull('collaboration_session_id')
            ->update(['collaboration_session_id' => $defaultSessionId]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collaboration_submissions', function (Blueprint $table) {
            $table->dropIndex('collab_subs_session_room_idx');
            $table->dropConstrainedForeignId('collaboration_session_id');
        });

        Schema::table('collaboration_rooms', function (Blueprint $table) {
            $table->dropUnique('collab_rooms_session_room_unique');
            $table->dropConstrainedForeignId('collaboration_session_id');
            $table->unique('room_number');
        });

        Schema::dropIfExists('collaboration_sessions');
    }
};
