<?php

namespace Tests\Feature;

use App\Models\CollaborationMember;
use App\Models\CollaborationRoom;
use App\Models\CollaborationSession;
use App\Models\CollaborationSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CollaborationTest extends TestCase
{
    use RefreshDatabase;

    private function createStudent(string $name = 'Siswa Tes', string $absen = '01'): User
    {
        return User::factory()->create([
            'name' => $name,
            'no_absen' => $absen,
            'is_admin' => 0,
        ]);
    }

    public function test_accessing_kolaborasi_index_ensures_eight_rooms_exist(): void
    {
        $user = $this->createStudent();

        $response = $this->actingAs($user)->get('/kolaborasi');

        $response->assertStatus(200);
        $this->assertEquals(8, CollaborationRoom::count());
    }

    public function test_update_color_part_validates_inputs_via_form_request(): void
    {
        $user = $this->createStudent();
        $this->actingAs($user)->get('/kolaborasi'); // ensure rooms exist

        // Valid color update
        $validResponse = $this->actingAs($user)->postJson('/api/kolaborasi/room/1/color', [
            'part_id' => 'c1-ataptengah-1',
            'color' => '#FF5733',
            'canvas_num' => 1,
        ]);
        $validResponse->assertStatus(200);
        $validResponse->assertJson(['status' => 'success', 'part_id' => 'c1-ataptengah-1']);

        // Invalid part_id (malicious characters)
        $invalidPartResponse = $this->actingAs($user)->postJson('/api/kolaborasi/room/1/color', [
            'part_id' => '../../etc/passwd',
            'color' => '#FF5733',
        ]);
        $invalidPartResponse->assertStatus(422);
        $invalidPartResponse->assertJsonValidationErrors(['part_id']);

        // Invalid color value
        $invalidColorResponse = $this->actingAs($user)->postJson('/api/kolaborasi/room/1/color', [
            'part_id' => 'c1-ataptengah-1',
            'color' => 'javascript:alert(1)',
        ]);
        $invalidColorResponse->assertStatus(422);
        $invalidColorResponse->assertJsonValidationErrors(['color']);
    }

    public function test_save_room_data_validates_payload_via_form_request(): void
    {
        $user = $this->createStudent();
        $this->actingAs($user)->get('/kolaborasi'); // ensure rooms exist

        // Valid payload
        $validResponse = $this->actingAs($user)->postJson('/api/kolaborasi/room/1/save', [
            'group_name' => 'Kelompok Garuda',
            'leader_name' => 'Budi',
            'coloring_data' => ['part-1' => '#ff0000', 'part-2' => '#00ff00'],
        ]);
        $validResponse->assertStatus(200);
        $validResponse->assertJson(['status' => 'success']);

        // Payload with coloring_data exceeding 600 items
        $oversizedColoring = array_fill(0, 601, '#123456');
        $invalidResponse = $this->actingAs($user)->postJson('/api/kolaborasi/room/1/save', [
            'coloring_data' => $oversizedColoring,
        ]);
        $invalidResponse->assertStatus(422);
        $invalidResponse->assertJsonValidationErrors(['coloring_data']);
    }

    public function test_join_room_enforces_five_members_capacity(): void
    {
        $user = $this->createStudent();
        $this->actingAs($user)->get('/kolaborasi');
        $room = CollaborationRoom::where('room_number', 1)->first();

        $this->assertEquals(5, $room->max_members);

        // Fill room to 5 members
        for ($i = 1; $i <= 5; $i++) {
            $memberUser = $this->createStudent('Anggota '.$i, (string) $i);
            CollaborationMember::create([
                'room_id' => $room->id,
                'user_id' => $memberUser->id,
                'user_name' => $memberUser->name,
                'no_absen' => $memberUser->no_absen,
                'role' => 'Kolaborator',
                'last_active_at' => now(),
            ]);
        }

        // 6th user attempts to join Room 1
        $newUser = $this->createStudent('Siswa Ke-6', '06');
        $response = $this->actingAs($newUser)->postJson('/api/kolaborasi/join', [
            'room_number' => 1,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'status' => 'error',
        ]);
        $this->assertStringContainsString('sudah penuh', $response->json('message'));
    }

    public function test_get_room_data_does_not_auto_join_when_room_is_full(): void
    {
        $user = $this->createStudent();
        $this->actingAs($user)->get('/kolaborasi');
        $room = CollaborationRoom::where('room_number', 1)->first();

        // Fill room to 5 members
        for ($i = 1; $i <= 5; $i++) {
            $memberUser = $this->createStudent('Anggota '.$i, (string) $i);
            CollaborationMember::create([
                'room_id' => $room->id,
                'user_id' => $memberUser->id,
                'user_name' => $memberUser->name,
                'no_absen' => $memberUser->no_absen,
                'role' => 'Kolaborator',
                'last_active_at' => now(),
            ]);
        }

        // 6th user visits /api/kolaborasi/room/1/data directly
        $outsider = $this->createStudent('Siswa Luar', '99');
        $response = $this->actingAs($outsider)->getJson('/api/kolaborasi/room/1/data');

        $response->assertStatus(422);
        $response->assertJson([
            'status' => 'error',
        ]);
        $this->assertStringContainsString('sudah penuh', $response->json('message'));

        // Confirm outsider is NOT in the room members
        $this->assertDatabaseMissing('collaboration_members', [
            'room_id' => $room->id,
            'user_id' => $outsider->id,
        ]);
    }

    public function test_kolaborasi_view_displays_five_member_quota_and_four_row_peer_appreciation_controls(): void
    {
        $user = $this->createStudent();

        $response = $this->actingAs($user)->get('/kolaborasi');

        $response->assertStatus(200);
        $response->assertSee('hingga 5 anggota', false);
        $response->assertSee('TOTAL_EVAL_ROWS = 4', false);
        $response->assertSee('toggleEvalPeerDropdown', false);
        $response->assertSee('selectEvalPeer', false);

        $profilResponse = $this->actingAs($user)->get('/profil');
        $profilResponse->assertStatus(200);
        $profilResponse->assertSee('hingga 5 siswa', false);
    }

    public function test_student_can_fetch_rooms_and_join_room_successfully(): void
    {
        $user = $this->createStudent('Budi Santoso', '07');
        $this->actingAs($user)->get('/kolaborasi');

        // Fetch rooms
        $roomsResponse = $this->actingAs($user)->getJson('/api/kolaborasi/rooms');
        $roomsResponse->assertStatus(200);
        $roomsResponse->assertJson(['status' => 'success']);
        $this->assertCount(8, $roomsResponse->json('rooms'));

        // Join room 2
        $joinResponse = $this->actingAs($user)->postJson('/api/kolaborasi/join', [
            'room_number' => 2,
        ]);
        $joinResponse->assertStatus(200);
        $joinResponse->assertJson(['status' => 'success']);

        // Check room data
        $dataResponse = $this->actingAs($user)->getJson('/api/kolaborasi/room/2/data');
        $dataResponse->assertStatus(200);
        $dataResponse->assertJson(['status' => 'success']);
        $this->assertEquals('Budi Santoso', $dataResponse->json('members.0.user_name'));

        // Leave room
        $leaveResponse = $this->actingAs($user)->postJson('/api/kolaborasi/leave');
        $leaveResponse->assertStatus(200);
        $leaveResponse->assertJson(['status' => 'success']);
        $this->assertDatabaseMissing('collaboration_members', ['user_id' => $user->id]);
    }

    public function test_menu_page_renders_cek_hasil_button_next_to_kolaborasi(): void
    {
        $user = $this->createStudent();

        $response = $this->actingAs($user)->get('/menu');

        $response->assertStatus(200);
        $response->assertSee('Kolaborasi');
        $response->assertSee('Cek Hasil');
        $response->assertSee(route('hasil'), false);
    }

    public function test_hasil_page_shows_room_statuses_and_submitted_groups_for_active_session(): void
    {
        $student = $this->createStudent('Siti Aminah', '05');
        $activeMemberUser = $this->createStudent('Riko Pratama', '08');

        $this->actingAs($student)->get('/kolaborasi');
        $activeSession = CollaborationSession::getActive();

        $submission = CollaborationSubmission::create([
            'collaboration_session_id' => $activeSession->id,
            'room_number' => 1,
            'group_name' => 'Kelompok Mawar',
            'theme' => 'Motif Damar Kurung',
            'leader_name' => 'Siti Aminah',
            'tasks_data' => [
                ['user_id' => $student->id, 'user_name' => 'Siti Aminah', 'no_absen' => '05', 'role' => 'Kolaborator'],
            ],
            'coloring_data' => ['c1-ataptengah-1' => '#EF4444'],
            'identification_data' => [
                ['bendaName' => 'Atap', 'shapeName' => 'Segitiga', 'ciri' => '3 sisi dan 3 sudut'],
            ],
            'evaluation_data' => [
                ['evaluator_name' => 'Siti Aminah', 'peer_name' => 'Budi', 'peer_no_absen' => '02', 'rating' => 'Sangat Baik', 'note' => 'Kerjasama bagus'],
            ],
            'submitted_at' => now(),
        ]);

        // Submission from an older inactive session should NOT appear in active session /hasil
        $oldSession = CollaborationSession::create([
            'name' => 'Sesi Lama',
            'is_active' => false,
        ]);
        CollaborationSubmission::create([
            'collaboration_session_id' => $oldSession->id,
            'room_number' => 4,
            'group_name' => 'Kelompok Sesi Lama',
            'theme' => 'Motif Damar Kurung',
            'leader_name' => 'Andi Lama',
            'submitted_at' => now()->subDay(),
        ]);

        $room2 = CollaborationRoom::where('collaboration_session_id', $activeSession->id)
            ->where('room_number', 2)
            ->first();
        CollaborationMember::create([
            'room_id' => $room2->id,
            'user_id' => $activeMemberUser->id,
            'user_name' => $activeMemberUser->name,
            'no_absen' => $activeMemberUser->no_absen,
            'role' => 'Kolaborator',
            'last_active_at' => now(),
        ]);

        $response = $this->actingAs($student)->get('/hasil');

        $response->assertStatus(200);
        $response->assertSee('Sudah Selesai');
        $response->assertSee('Sedang Dikerjakan');
        $response->assertSee('Belum Selesai');
        $response->assertSee('Kelompok Mawar');
        $response->assertDontSee('Kelompok Sesi Lama');
        $response->assertSee(route('hasil.show', $submission->id), false);
    }

    public function test_hasil_detail_page_displays_group_work_and_returns_404_for_missing_id(): void
    {
        $student = $this->createStudent('Dewi Lestari', '03');
        $activeSession = CollaborationSession::getActive();

        $submission = CollaborationSubmission::create([
            'collaboration_session_id' => $activeSession->id,
            'room_number' => 3,
            'group_name' => 'Kelompok Melati',
            'theme' => 'Motif Damar Kurung',
            'leader_name' => 'Dewi Lestari',
            'tasks_data' => [
                ['user_id' => $student->id, 'user_name' => 'Dewi Lestari', 'no_absen' => '03', 'role' => 'Kolaborator'],
            ],
            'coloring_data' => ['c1-ataptengah-1' => '#22C55E'],
            'identification_data' => [
                [
                    'bendaKey' => 'c1-lentera-1',
                    'bendaName' => 'Lentera',
                    'shape' => 'persegi',
                    'shapeName' => 'Persegi',
                    'ciri' => '4 sisi sama panjang',
                    'svgSnippet' => '<svg viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" fill="#EF4444" onclick="alert(1)"/></svg>',
                ],
            ],
            'evaluation_data' => [
                ['evaluator_name' => 'Dewi Lestari', 'peer_name' => 'Andi', 'peer_no_absen' => '01', 'rating' => 'Baik', 'note' => 'Aktif berdiskusi'],
            ],
            'submitted_at' => now(),
        ]);

        $response = $this->actingAs($student)->get(route('hasil.show', $submission->id));

        $response->assertStatus(200);
        $response->assertSee('Kelompok Melati');
        $response->assertSee('Dewi Lestari');
        $response->assertSee('<rect x="2" y="2" width="20" height="20" fill="#EF4444"', false);
        $response->assertDontSee('onclick="alert(1)"', false);
        $response->assertSee('Persegi');
        $response->assertSee('Aktif berdiskusi');
        $response->assertSee('#22C55E');

        $missingResponse = $this->actingAs($student)->get('/hasil/999999');
        $missingResponse->assertStatus(404);
    }

    public function test_admin_can_manage_sessions_without_collision_and_isolates_rooms(): void
    {
        $admin = User::factory()->create(['is_admin' => 1]);
        $student = $this->createStudent('Siswa Sesi 1', '10');

        // Student joins room 1 in initial active session
        $this->actingAs($student)->postJson('/api/kolaborasi/join', ['room_number' => 1])->assertStatus(200);
        $this->assertEquals(1, CollaborationMember::count());

        // Admin creates & activates new session for Kelas 4A
        $createRes = $this->actingAs($admin)->post('/admin/kolaborasi/sessions', [
            'name' => 'Pertemuan 1',
            'class_label' => 'Kelas 4A',
            'schedule_label' => 'Selasa 08:00',
        ]);
        $createRes->assertRedirect(route('admin.kolaborasi.index'));

        $activeSession = CollaborationSession::getActive();
        $this->assertEquals('Pertemuan 1', $activeSession->name);
        $this->assertEquals('Kelas 4A', $activeSession->class_label);
        $this->assertEquals(8, CollaborationRoom::where('collaboration_session_id', $activeSession->id)->count());

        // Live members from previous session were released on session switch
        $this->assertEquals(0, CollaborationMember::count());

        // Duplicate name+class+schedule is rejected to avoid collision
        $dupRes = $this->actingAs($admin)->post('/admin/kolaborasi/sessions', [
            'name' => 'Pertemuan 1',
            'class_label' => 'Kelas 4A',
            'schedule_label' => 'Selasa 08:00',
        ]);
        $dupRes->assertSessionHasErrors('name');

        // Cannot delete active session
        $this->actingAs($admin)->delete('/admin/kolaborasi/sessions/'.$activeSession->id)
            ->assertSessionHasErrors('session');

        // Inactive session can be deleted
        $inactiveSession = CollaborationSession::where('id', '!=', $activeSession->id)->first();
        $this->actingAs($admin)->delete('/admin/kolaborasi/sessions/'.$inactiveSession->id)
            ->assertSessionHasNoErrors();
    }

    public function test_submit_room_prevents_duplicate_results_and_merges_peer_evaluations(): void
    {
        $studentA = $this->createStudent('Andi', '01');
        $studentB = $this->createStudent('Budi', '02');

        $this->actingAs($studentA)->postJson('/api/kolaborasi/join', ['room_number' => 1])->assertStatus(200);
        $this->actingAs($studentB)->postJson('/api/kolaborasi/join', ['room_number' => 1])->assertStatus(200);

        // Student A saves evaluation of Budi
        $this->actingAs($studentA)->postJson('/api/kolaborasi/room/1/save', [
            'group_name' => 'Kelompok Garuda',
            'leader_name' => 'Andi',
            'evaluation_data' => [
                ['evaluator_id' => $studentA->id, 'evaluator_name' => 'Andi', 'peer_id' => $studentB->id, 'peer_name' => 'Budi', 'peer_no_absen' => '02', 'rating' => 'Sangat Baik', 'note' => 'Rajin mewarnai'],
            ],
        ])->assertStatus(200);

        // Student B saves evaluation of Andi (should NOT overwrite Student A's evaluation)
        $this->actingAs($studentB)->postJson('/api/kolaborasi/room/1/save', [
            'evaluation_data' => [
                ['evaluator_id' => $studentB->id, 'evaluator_name' => 'Budi', 'peer_id' => $studentA->id, 'peer_name' => 'Andi', 'peer_no_absen' => '01', 'rating' => 'Baik', 'note' => 'Kompak berdiskusi'],
            ],
        ])->assertStatus(200);

        // Student A submits Room 1
        $submitA = $this->actingAs($studentA)->postJson('/api/kolaborasi/room/1/submit', [
            'group_name' => 'Kelompok Garuda',
            'leader_name' => 'Andi',
            'identification_data' => [
                ['bendaKey' => 'atap', 'bendaName' => 'Atap', 'shape' => 'segitiga', 'ciri' => '3 sisi', 'svgSnippet' => '<svg viewBox="0 0 24 24"><polygon points="12,2 2,22 22,22" fill="#E57354"/></svg>'],
            ],
        ]);
        $submitA->assertStatus(200)->assertJson(['status' => 'success']);

        // Second tab (Student B) polls /data -> sees room completed without creating duplicate
        $pollB = $this->actingAs($studentB)->getJson('/api/kolaborasi/room/1/data');
        $pollB->assertStatus(200)->assertJson(['status' => 'completed']);

        // Second tab (Student B) clicks submit too -> returns already_submitted, count remains 1
        $submitB = $this->actingAs($studentB)->postJson('/api/kolaborasi/room/1/submit', [
            'group_name' => 'Kelompok Garuda',
        ]);
        $submitB->assertStatus(200)->assertJson(['status' => 'already_submitted']);

        $this->assertEquals(1, CollaborationSubmission::count());
        $submission = CollaborationSubmission::first();
        $this->assertCount(2, $submission->tasks_data);
        $this->assertCount(2, $submission->evaluation_data);

        // Student C cannot join already completed Room 1 in same session
        $studentC = $this->createStudent('Citra', '03');
        $this->actingAs($studentC)->postJson('/api/kolaborasi/join', ['room_number' => 1])->assertStatus(422);

        // Admin detail page displays visual SVG Misi 2 shape
        $admin = User::factory()->create(['is_admin' => 1]);
        $adminShow = $this->actingAs($admin)->get(route('admin.kolaborasi.show', $submission->id));
        $adminShow->assertStatus(200);
        $adminShow->assertSee('<polygon points="12,2 2,22 22,22" fill="#E57354"', false);
    }
}
