<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/admin/login');
    }

    public function test_non_admin_cannot_access_admin_dashboard(): void
    {
        $student = User::factory()->create([
            'is_admin' => 0,
        ]);

        $response = $this->actingAs($student)->get('/admin');
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_access_dashboard_overview_and_legacy_artifacts_are_removed(): void
    {
        $admin = User::factory()->create([
            'is_admin' => 1,
        ]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Panel Pengelola Media TARA');
        $response->assertSee('Total Siswa');
        $response->assertSee('Sesi Kolaborasi');
        $response->assertSee('Kelompok Selesai');
        $response->assertSee('Bangun Datar');
        $response->assertSee('Status 8 Ruang');
        $response->assertSee('Hasil Kolaborasi Terbaru');
        $response->assertSee('Siswa Baru');
        $response->assertSee('target="_blank"', false);
        $response->assertSee('Buka Media Siswa');

        // Ensure legacy Ide Bisnis / SABI artifacts are gone
        $response->assertDontSee('Ide Bisnis Siswa');
        $response->assertDontSee('Item Game Belanja');
        $response->assertDontSee('Jawaban & Evaluasi Siswa');

        $this->actingAs($admin)->get('/admin/submissions')->assertStatus(404);
        $this->actingAs($admin)->get('/admin/spending-items')->assertStatus(404);
    }
}
