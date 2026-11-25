<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        for ($num = 5; $num <= 8; $num++) {
            $exists = DB::table('collaboration_rooms')->where('room_number', $num)->exists();
            if (! $exists) {
                DB::table('collaboration_rooms')->insert([
                    'room_number' => $num,
                    'name' => "Ruang Kolaborasi {$num}",
                    'theme' => 'Motif Damar Kurung',
                    'max_members' => 5,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        DB::table('collaboration_rooms')->where('room_number', '<=', 8)->update(['max_members' => 5]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('collaboration_rooms')->whereBetween('room_number', [5, 8])->delete();
    }
};
