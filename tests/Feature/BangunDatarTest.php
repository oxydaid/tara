<?php

namespace Tests\Feature;

use App\Models\BangunDatarShape;
use App\Models\User;
use Database\Seeders\BangunDatarShapeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BangunDatarTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_generates_18_clean_bangun_datar_shapes_without_markers(): void
    {
        $this->seed(BangunDatarShapeSeeder::class);

        $this->assertEquals(18, BangunDatarShape::count());
        $this->assertEquals(6, BangunDatarShape::where('type', 'segitiga')->count());
        $this->assertEquals(6, BangunDatarShape::where('type', 'trapesium')->count());

        $segitigaSamaSisi = BangunDatarShape::where('type', 'segitiga')->where('subtype', 'sama_sisi')->first();
        $this->assertNotNull($segitigaSamaSisi);
        $this->assertStringContainsString('<polygon', $segitigaSamaSisi->svg_inline);
        $this->assertStringNotContainsString('<line', $segitigaSamaSisi->svg_inline);
        $this->assertStringNotContainsString('<path', $segitigaSamaSisi->svg_inline);
        $this->assertStringNotContainsString('ditandai', implode(' ', $segitigaSamaSisi->ciri_ciri ?? []));

        $segitigaSiku = BangunDatarShape::where('type', 'segitiga')->where('subtype', 'siku_siku')->first();
        $this->assertNotNull($segitigaSiku);
        $this->assertStringNotContainsString('bertanda', implode(' ', $segitigaSiku->ciri_ciri ?? []));

        $trapesiumSiku = BangunDatarShape::where('type', 'trapesium')->where('subtype', 'siku_siku')->first();
        $this->assertNotNull($trapesiumSiku);
        $this->assertStringContainsString('<polygon', $trapesiumSiku->svg_inline);
        $this->assertStringNotContainsString('<polyline', $trapesiumSiku->svg_inline);
    }

    public function test_profil_page_displays_updated_description_cp_and_tp(): void
    {
        $student = User::factory()->create(['is_admin' => 0]);

        $response = $this->actingAs($student)->get('/profil');

        $response->assertStatus(200);
        $response->assertSee('TARA (Lentera Eksplorasi Bangun Datar)');
        $response->assertSee('Selamat bereksplorasi teman-teman!');
        $response->assertSee('Pada materi ini murid dapat mendeskripsikan ciri-ciri berbagai bentuk bangun datar segi banyak dan bukan segi banyak.');
        $response->assertSee('Murid dapat menerapkan (C3) ciri-ciri bentuk bangun datar (segi banyak dan bukan segi banyak) berdasarkan karakteristiknya pada motif damar kurung dengan baik.');
        $response->assertSee('Murid dapat menguraikan (C4) jenis dan ciri-ciri bangun datar penyusun motif damar kurung dengan tepat.');
        $response->assertSee('Cek Hasil:');
    }

    public function test_eksplorasi_bangun_datar_renders_collapsed_groups_with_modal_variants_and_pagination(): void
    {
        $this->seed(BangunDatarShapeSeeder::class);
        $student = User::factory()->create(['is_admin' => 0]);

        $response = $this->actingAs($student)->get('/ruang-eksplorasi/bangun-datar');

        $response->assertStatus(200);
        $response->assertSee('Persegi');
        $response->assertSee('Segitiga');
        $response->assertSee('Trapesium');
        $response->assertSee('Lihat 6 Jenis');
        $response->assertSee('shapeVariantModal');
        $response->assertSee('SHAPE_VARIANT_GROUPS');
        $response->assertSee('Segitiga Sama Sisi');
        $response->assertSee('Trapesium Tiga Sisi Sama Panjang');
        $response->assertSee('Halaman 1 dari 4');
        $response->assertSee('btnShapePrev');
        $response->assertSee('btnShapeNext');
        $this->assertSame(8, substr_count($response->getContent(), 'data-shape-page="'));
    }

    public function test_cached_shapes_work_with_serializable_classes_disabled(): void
    {
        $this->seed(BangunDatarShapeSeeder::class);
        config(['cache.default' => 'file', 'cache.serializable_classes' => false]);
        BangunDatarShape::clearActiveCache();

        $student = User::factory()->create(['is_admin' => 0]);

        // First request populates the file cache
        $this->actingAs($student)->get('/ruang-eksplorasi/bangun-datar')->assertStatus(200);

        // Second request reads & unserializes from file cache with serializable_classes = false
        $secondResponse = $this->actingAs($student)->get('/ruang-eksplorasi/bangun-datar');
        $secondResponse->assertStatus(200);
        $secondResponse->assertSee('Persegi');
    }

    public function test_api_bangun_datar_shapes_returns_active_ordered_shapes(): void
    {
        $this->seed(BangunDatarShapeSeeder::class);
        $student = User::factory()->create(['is_admin' => 0]);

        $response = $this->actingAs($student)->getJson('/api/bangun-datar-shapes');

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
        $this->assertCount(18, $response->json('shapes'));
    }

    public function test_kolaborasi_loads_master_shapes_in_misi_2_dropdown_without_icons(): void
    {
        $this->seed(BangunDatarShapeSeeder::class);
        $student = User::factory()->create(['is_admin' => 0]);

        $response = $this->actingAs($student)->get('/kolaborasi');

        $response->assertStatus(200);
        $response->assertSee('Trapesium Tiga Sisi Sama Panjang');
        $response->assertSee('Segitiga Siku-Siku');
        $response->assertSee('BANGUN_DATAR_MASTER');
        $response->assertDontSee('${s.icon', false);
        $response->assertDontSee('${selectedShapeObj.icon', false);
    }

    public function test_admin_can_crud_bangun_datar_shapes(): void
    {
        $admin = User::factory()->create(['is_admin' => 1]);
        $student = User::factory()->create(['is_admin' => 0]);

        // Non-admin blocked
        $this->actingAs($student)->get('/admin/bangun-datar')->assertRedirect('/admin/login');

        // Admin index
        $this->actingAs($admin)->get('/admin/bangun-datar')->assertStatus(200);

        // Create
        $createRes = $this->actingAs($admin)->post('/admin/bangun-datar', [
            'name' => 'Segi Lima Beraturan',
            'type' => 'segitiga',
            'subtype' => 'uji',
            'color' => '#123456',
            'bagian_damar_kurung' => 'Ornamen Puncak',
            'ciri_ciri' => ['Memiliki 5 sisi sama panjang', 'Memiliki 5 sudut sama besar'],
            'urutan' => 20,
            'is_active' => 1,
        ]);
        $createRes->assertRedirect('/admin/bangun-datar');

        $shape = BangunDatarShape::where('name', 'Segi Lima Beraturan')->first();
        $this->assertNotNull($shape);
        $this->assertEquals('#123456', $shape->color);
        $this->assertEquals('Ornamen Puncak', $shape->bagian_damar_kurung);

        // Update
        $updateRes = $this->actingAs($admin)->post("/admin/bangun-datar/{$shape->id}", [
            'name' => 'Segi Lima Edit',
            'type' => 'persegi',
            'subtype' => '',
            'color' => '#654321',
            'bagian_damar_kurung' => '',
            'ciri_ciri' => ['Ciri diperbarui'],
            'urutan' => 5,
            'is_active' => 1,
        ]);
        $updateRes->assertRedirect('/admin/bangun-datar');

        $shape->refresh();
        $this->assertEquals('Segi Lima Edit', $shape->name);
        $this->assertEquals('#654321', $shape->color);
        $this->assertNull($shape->bagian_damar_kurung);

        // Delete
        $deleteRes = $this->actingAs($admin)->post("/admin/bangun-datar/{$shape->id}/delete");
        $deleteRes->assertRedirect('/admin/bangun-datar');
        $this->assertDatabaseMissing('bangun_datar_shapes', ['id' => $shape->id]);
    }
}
