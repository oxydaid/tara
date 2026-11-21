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
        Schema::table('student_submissions', function (Blueprint $table) {
            $table->index('step_key');
            $table->index(['user_id', 'step_key']);
        });

        Schema::table('collaboration_members', function (Blueprint $table) {
            $table->index('last_active_at');
        });

        Schema::table('collaboration_submissions', function (Blueprint $table) {
            $table->index('submitted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collaboration_submissions', function (Blueprint $table) {
            $table->dropIndex(['submitted_at']);
        });

        Schema::table('collaboration_members', function (Blueprint $table) {
            $table->dropIndex(['last_active_at']);
        });

        Schema::table('student_submissions', function (Blueprint $table) {
            $table->dropIndex(['step_key']);
            $table->dropIndex(['user_id', 'step_key']);
        });
    }
};

