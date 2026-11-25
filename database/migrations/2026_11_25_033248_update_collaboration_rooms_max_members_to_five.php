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
        Schema::table('collaboration_rooms', function (Blueprint $table) {
            $table->unsignedInteger('max_members')->default(5)->change();
        });

        DB::table('collaboration_rooms')->update(['max_members' => 5]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collaboration_rooms', function (Blueprint $table) {
            $table->unsignedInteger('max_members')->default(10)->change();
        });

        DB::table('collaboration_rooms')->update(['max_members' => 10]);
    }
};
