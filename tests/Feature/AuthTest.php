<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_login_with_name_and_no_absen(): void
    {
        $response = $this->post('/login', [
            'name' => 'Budi Santoso',
            'no_absen' => '12',
        ]);

        $response->assertRedirect(route('menu'));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'name' => 'Budi Santoso',
            'no_absen' => '12',
            'is_admin' => 0,
        ]);
    }

    public function test_existing_student_with_same_name_and_absen_logs_in(): void
    {
        $user = User::factory()->create([
            'name' => 'Siti Aminah',
            'no_absen' => '05',
            'is_admin' => 0,
        ]);

        $response = $this->post('/login', [
            'name' => 'Siti Aminah',
            'no_absen' => '05',
        ]);

        $response->assertRedirect(route('menu'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_different_name_with_already_registered_absen_is_rejected(): void
    {
        User::factory()->create([
            'name' => 'Siti Aminah',
            'no_absen' => '05',
            'is_admin' => 0,
        ]);

        $response = $this->from('/login')->post('/login', [
            'name' => 'Budi Santoso',
            'no_absen' => '05',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['no_absen']);
        $this->assertGuest();
    }

    public function test_student_login_is_rate_limited_after_10_attempts(): void
    {
        User::factory()->create([
            'name' => 'Siswa Terdaftar',
            'no_absen' => '99',
            'is_admin' => 0,
        ]);

        // 10 attempts allowed per minute
        for ($i = 1; $i <= 10; $i++) {
            $response = $this->from('/login')->post('/login', [
                'name' => 'Nama Salah '.$i,
                'no_absen' => '99',
            ]);
            $response->assertStatus(302);
            $this->assertGuest();
        }

        // 11th attempt should trigger 429 Too Many Requests
        $response = $this->from('/login')->post('/login', [
            'name' => 'Nama Salah 11',
            'no_absen' => '99',
        ]);

        $response->assertStatus(429);
    }
}
