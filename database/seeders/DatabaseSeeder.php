<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Default Admin User
        User::firstOrCreate(
            ['email' => 'admin@tara.com'],
            [
                'name' => 'Admin TARA',
                'no_absen' => null,
                'password' => Hash::make('admin123'),
                'is_admin' => 1,
                'avatar' => 'co',
            ]
        );

        $this->call([
            GameLevelSeeder::class,
        ]);
    }
}
