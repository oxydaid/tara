<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CollaborationRoom;
use App\Models\CollaborationMember;
use App\Models\CollaborationSubmission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CollaborationController extends Controller
{
    /**
     * Display the main collaboration view.
     */
    public function index()
    {
        $this->pruneInactiveMembers();
        $rooms = CollaborationRoom::with('members')->orderBy('room_number')->take(4)->get();
        
        if ($rooms->count() < 4) {
            $themes = [
                1 => ['name' => 'Ruang Kolaborasi 1', 'theme' => 'Motif Damar Kurung'],
                2 => ['name' => 'Ruang Kolaborasi 2', 'theme' => 'Motif Damar Kurung'],
                3 => ['name' => 'Ruang Kolaborasi 3', 'theme' => 'Motif Damar Kurung'],
                4 => ['name' => 'Ruang Kolaborasi 4', 'theme' => 'Motif Damar Kurung'],
            ];
            foreach ($themes as $num => $info) {
                CollaborationRoom::firstOrCreate(
                    ['room_number' => $num],
                    ['name' => $info['name'], 'theme' => $info['theme'], 'max_members' => 10, 'status' => 'active']
                );
            }
            $rooms = CollaborationRoom::with('members')->orderBy('room_number')->take(4)->get();
        }

        $currentUser = Auth::user();
        $activeMember = $currentUser ? CollaborationMember::where('user_id', $currentUser->id)->first() : null;
        $currentRoomId = $activeMember ? $activeMember->room_id : null;
        $activeRoom = $currentRoomId ? $rooms->firstWhere('id', $currentRoomId) : null;
        $activeRoomMembers = $activeRoom ? $activeRoom->members : collect();

        return view('kolaborasi', compact('rooms', 'currentUser', 'currentRoomId', 'activeRoomMembers'));
    }

    /**
     * Clean up inactive members whose last active heartbeat is older than 25 seconds.
     * Throttled with a cache lock to avoid hitting the database on every frequent poll.
     */
    private function pruneInactiveMembers()
    {
        if (Cache::add('tara_prune_inactive_members_lock', true, 10)) {
            CollaborationMember::where('last_active_at', '<', now()->subSeconds(25))->delete();
        }
    }

    /**
     * Get live status of the 4 fixed rooms for realtime polling.
     */
    public function getRooms()
    {
        $this->pruneInactiveMembers();

        $rooms = CollaborationRoom::with(['members' => function ($q) {
            $q->select('id', 'room_id', 'user_id', 'user_name', 'no_absen', 'role', 'created_at')->orderBy('id', 'asc');
        }])->select('id', 'room_number', 'name', 'theme', 'max_members', 'group_name')->orderBy('room_number')->take(4)->get();
        
        if ($rooms->count() < 4) {
            $themes = [
                1 => ['name' => 'Ruang Kolaborasi 1', 'theme' => 'Motif Damar Kurung'],
                2 => ['name' => 'Ruang Kolaborasi 2', 'theme' => 'Motif Damar Kurung'],
                3 => ['name' => 'Ruang Kolaborasi 3', 'theme' => 'Motif Damar Kurung'],
                4 => ['name' => 'Ruang Kolaborasi 4', 'theme' => 'Motif Damar Kurung'],
            ];
            foreach ($themes as $num => $info) {
                CollaborationRoom::firstOrCreate(
                    ['room_number' => $num],
                    ['name' => $info['name'], 'theme' => $info['theme'], 'max_members' => 10, 'status' => 'active']
                );
            }
            $rooms = CollaborationRoom::with(['members' => function ($q) {
                $q->select('id', 'room_id', 'user_id', 'user_name', 'no_absen', 'role', 'created_at')->orderBy('id', 'asc');
            }])->select('id', 'room_number', 'name', 'theme', 'max_members', 'group_name')->orderBy('room_number')->take(4)->get();
        }

        $formattedRooms = $rooms->map(function ($room) {
            $count = $room->members->count();
            return [
                'id' => $room->id,
                'room_number' => $room->room_number,
                'name' => $room->name,
                'theme' => $room->theme,
                'max_members' => $room->max_members,
                'member_count' => $count,
                'is_full' => $count >= $room->max_members,
                'group_name' => $room->group_name,
                'members' => $room->members->map(function ($m) {
                    return [
                        'id' => $m->id,
                        'user_id' => $m->user_id,
                        'user_name' => $m->user_name,
                        'no_absen' => $m->no_absen,
                        'role' => $m->role,
                        'created_at' => $m->created_at ? $m->created_at->toISOString() : null,
                    ];
                }),
            ];
        });

        $currentUser = Auth::user();
        // Lookup active membership from already loaded eager-loaded collection (0 extra query)
        $allMembers = $rooms->pluck('members')->flatten();
        $userActiveMember = $currentUser ? $allMembers->firstWhere('user_id', $currentUser->id) : null;

        return response()->json([
            'status' => 'success',
            'rooms' => $formattedRooms,
            'current_user_id' => $currentUser ? $currentUser->id : null,
            'current_room_id' => $userActiveMember ? $userActiveMember->room_id : null,
        ]);
    }

    /**
     * Create a new collaboration room dynamically.
     */
    public function createRoom(Request $request)
    {
        $request->validate([
            'group_name' => 'required|string|max:80',
            'theme' => 'nullable|string|max:50',
        ]);

        $this->pruneInactiveMembers();

        $user = Auth::user();

        // Determine next room number
        $lastRoomNumber = CollaborationRoom::max('room_number') ?? 0;
        $nextRoomNumber = $lastRoomNumber + 1;

        $room = CollaborationRoom::create([
            'room_number' => $nextRoomNumber,
            'name' => $request->group_name,
            'theme' => $request->theme ?? 'Motif Damar Kurung',
            'max_members' => 10,
            'status' => 'active',
            'group_name' => $request->group_name,
            'leader_name' => $user->name ?? 'Ketua',
        ]);

        // Remove user from any other room
        CollaborationMember::where('user_id', $user->id)->delete();

        // Add user as first member & leader
        CollaborationMember::create([
            'room_id' => $room->id,
            'user_id' => $user->id,
            'user_name' => $user->name ?? 'Siswa ' . $user->id,
            'no_absen' => $user->no_absen ?? '-',
            'role' => 'Kolaborator',
            'last_active_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Ruang kelompok ' . $room->group_name . ' berhasil dibuat!',
            'room' => $room->load('members'),
        ]);
    }

    /**
     * Join a collaboration room (enforcing 10 members max).
     */
    public function joinRoom(Request $request)
    {
        $request->validate([
            'room_number' => 'required|integer',
        ]);

        $this->pruneInactiveMembers();

        $room = CollaborationRoom::where('room_number', $request->room_number)->firstOrFail();
        $user = Auth::user();

        // Check if user is already in this room
        $existingInThisRoom = CollaborationMember::where('room_id', $room->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingInThisRoom) {
            $existingInThisRoom->update([
                'user_name' => $user->name,
                'no_absen' => $user->no_absen,
                'last_active_at' => now(),
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Kembali ke ruang kolaborasi.',
                'room' => $room->load('members'),
            ]);
        }

        // Concurrency-safe atomic check & join using DB transaction and row-level locking
        try {
            DB::transaction(function () use ($room, $user) {
                // Lock current room row to prevent race condition when multiple students join simultaneously
                $lockedRoom = CollaborationRoom::where('id', $room->id)->lockForUpdate()->first();
                $currentCount = CollaborationMember::where('room_id', $lockedRoom->id)->count();

                if ($currentCount >= $lockedRoom->max_members) {
                    throw new \RuntimeException('ROOM_FULL');
                }

                // Remove user from any other room first
                CollaborationMember::where('user_id', $user->id)->delete();

                // Add user to this room
                CollaborationMember::create([
                    'room_id' => $lockedRoom->id,
                    'user_id' => $user->id,
                    'user_name' => $user->name ?? 'Siswa ' . $user->id,
                    'no_absen' => $user->no_absen ?? '-',
                    'role' => 'Kolaborator',
                    'last_active_at' => now(),
                ]);
            });
        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'ROOM_FULL') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Maaf, Ruang ' . $room->room_number . ' sudah penuh (maksimal ' . $room->max_members . ' siswa). Silakan pilih ruang lain!',
                ], 422);
            }
            throw $e;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil masuk ke ' . $room->name,
            'room' => $room->load('members'),
        ]);
    }

    /**
     * Leave room.
     */
    public function leaveRoom(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            CollaborationMember::where('user_id', $user->id)->delete();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil keluar dari ruang kolaborasi.',
        ]);
    }

    /**
     * Get real-time room work data.
     */
    public function getRoomData($roomNumber)
    {
        $this->pruneInactiveMembers();

        $room = CollaborationRoom::where('room_number', $roomNumber)->with('members')->firstOrFail();
        $user = Auth::user();

        // Ensure currently viewing authenticated user is registered and kept active in this room
        if ($user) {
            $member = $room->members->firstWhere('user_id', $user->id);
            if (!$member) {
                CollaborationMember::where('user_id', $user->id)->delete();
                CollaborationMember::create([
                    'room_id' => $room->id,
                    'user_id' => $user->id,
                    'user_name' => $user->name ?? 'Siswa ' . $user->id,
                    'no_absen' => $user->no_absen ?? '-',
                    'role' => 'Kolaborator',
                    'last_active_at' => now(),
                ]);
                $room->load('members');
            } else {
                $member->update(['last_active_at' => now()]);
            }
        }

        return response()->json([
            'status' => 'success',
            'room' => $room,
            'members' => $room->members->map(function ($m) {
                return [
                    'id' => $m->id,
                    'user_id' => $m->user_id,
                    'user_name' => $m->user_name,
                    'no_absen' => $m->no_absen,
                    'role' => $m->role,
                    'created_at' => $m->created_at ? $m->created_at->toISOString() : null,
                ];
            }),
            'coloring_data' => $room->coloring_data ?? [],
            'tasks_data' => $room->tasks_data ?? [],
            'identification_data' => $room->identification_data ?? [],
            'evaluation_data' => $room->evaluation_data ?? [],
        ]);
    }

    /**
     * Save room work data (real-time sync).
     */
    public function saveRoomData(Request $request, $roomNumber)
    {
        $this->pruneInactiveMembers();

        $room = CollaborationRoom::where('room_number', $roomNumber)->firstOrFail();
        $user = Auth::user();

        if ($request->has('group_name')) {
            $room->group_name = $request->group_name;
        }
        if ($request->has('leader_name')) {
            $room->leader_name = $request->leader_name;
        }
        if ($request->has('coloring_data')) {
            $room->coloring_data = $request->coloring_data;
        }
        if ($request->has('tasks_data')) {
            $room->tasks_data = $request->tasks_data;
        }
        if ($request->has('identification_data')) {
            $room->identification_data = $request->identification_data;
        }
        if ($request->has('evaluation_data')) {
            $room->evaluation_data = $request->evaluation_data;
        }

        // Touch active timestamp for current user in this room
        if ($user) {
            CollaborationMember::where('room_id', $room->id)
                ->where('user_id', $user->id)
                ->update(['last_active_at' => now()]);
        }

        // Update current user's role in collaboration_members if provided
        if ($request->has('my_role') && $user) {
            CollaborationMember::where('room_id', $room->id)
                ->where('user_id', $user->id)
                ->update(['role' => $request->my_role]);
        }

        if ($request->has('target_user_id') && $request->has('target_role')) {
            CollaborationMember::where('room_id', $room->id)
                ->where('user_id', $request->target_user_id)
                ->update(['role' => $request->target_role]);
        }

        $room->save();

        if ($request->has('coloring_data')) {
            Cache::put("room_{$roomNumber}_coloring_data", $room->coloring_data, 60);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data kerja kelompok berhasil diperbarui!',
            'room' => $room->load('members'),
            'current_user' => [
                'id' => $user->id,
                'name' => $user->name,
                'no_absen' => $user->no_absen,
            ],
        ]);
    }

    /**
     * Update color of a single SVG shape part in real time (instant live sync).
     */
    public function updateColorPart(Request $request, $roomNumber)
    {
        $request->validate([
            'part_id' => 'required|string|max:60',
            'color' => 'required|string|max:30',
        ]);

        $room = CollaborationRoom::where('room_number', $roomNumber)->firstOrFail();
        $user = Auth::user();

        $coloring = $room->coloring_data ?: [];
        $partId = $request->part_id;
        $color = $request->color;

        if ($color === 'white' || strtolower($color) === '#ffffff') {
            unset($coloring[$partId]);
        } else {
            $coloring[$partId] = $color;
        }

        $lastColorEvent = [
            'type' => 'color',
            'part_id' => $partId,
            'color' => $color,
            'canvas_num' => (int)($request->canvas_num ?? 1),
            'user_id' => $user ? $user->id : null,
            'user_name' => $user ? $user->name : 'Teman',
            'time' => round(microtime(true) * 1000),
        ];

        $coloring['_meta'] = $lastColorEvent;
        $room->coloring_data = $coloring;
        $room->save();

        if ($user) {
            CollaborationMember::where('room_id', $room->id)
                ->where('user_id', $user->id)
                ->update(['last_active_at' => now()]);
        }

        Cache::put("room_{$roomNumber}_last_color", $lastColorEvent, 60);
        Cache::put("room_{$roomNumber}_coloring_data", $coloring, 60);

        return response()->json([
            'status' => 'success',
            'part_id' => $partId,
            'color' => $color,
            'last_colored' => $lastColorEvent,
            'coloring_data' => $room->coloring_data,
        ]);
    }

    /**
     * Reset canvas parts for a specific canvas in real time.
     */
    public function resetCanvasPart(Request $request, $roomNumber)
    {
        $room = CollaborationRoom::where('room_number', $roomNumber)->firstOrFail();
        $canvasNum = $request->canvas_num;
        $user = Auth::user();

        $coloring = $room->coloring_data ?: [];
        if ($canvasNum) {
            $prefix = "c{$canvasNum}-";
            foreach ($coloring as $key => $val) {
                if (str_starts_with($key, $prefix)) {
                    unset($coloring[$key]);
                }
            }
        } else {
            $coloring = [];
        }

        $lastResetEvent = [
            'type' => 'reset',
            'canvas_num' => (int)$canvasNum,
            'user_id' => $user ? $user->id : null,
            'user_name' => $user ? $user->name : 'Teman',
            'time' => round(microtime(true) * 1000),
        ];

        $coloring['_meta'] = $lastResetEvent;
        $room->coloring_data = $coloring;
        $room->save();

        Cache::put("room_{$roomNumber}_last_color", $lastResetEvent, 60);
        Cache::put("room_{$roomNumber}_coloring_data", $coloring, 60);

        return response()->json([
            'status' => 'success',
            'coloring_data' => $room->coloring_data,
            'last_colored' => $lastResetEvent,
        ]);
    }

    /**
     * Ultra-fast endpoint for polling live canvas coloring data and latest color events.
     * Served directly from cache during active sessions to avoid hitting MySQL on rapid sub-second polling.
     */
    public function getLiveColoring($roomNumber)
    {
        $coloring = Cache::remember("room_{$roomNumber}_coloring_data", 60, function () use ($roomNumber) {
            $room = CollaborationRoom::where('room_number', $roomNumber)->select('id', 'coloring_data')->first();
            return $room ? ($room->coloring_data ?: []) : [];
        });

        $lastColor = Cache::get("room_{$roomNumber}_last_color", $coloring['_meta'] ?? null);

        return response()->json([
            'status' => 'success',
            'coloring_data' => $coloring,
            'last_colored' => $lastColor,
        ]);
    }

    /**
     * Submit and complete room work (creates a new CollaborationSubmission for admin review and frees up room).
     */
     public function submitRoom(Request $request, $roomNumber)
     {
         $room = CollaborationRoom::where('room_number', $roomNumber)->with('members')->firstOrFail();
         $user = Auth::user();

         $groupName = $request->group_name ?: ($room->group_name ?: ('Kelompok ' . ($room->theme ?: 'Ruang ' . $room->room_number)));
         $leaderName = $request->leader_name ?: $room->leader_name;
         $coloringData = $request->coloring_data ?: ($room->coloring_data ?: []);
         $identificationData = $request->identification_data ?: ($room->identification_data ?: []);
         $evaluationData = $request->evaluation_data ?: ($room->evaluation_data ?: []);

         // Snapshot anggota dan peran tugas
         $tasksData = $request->tasks_data ?: ($room->tasks_data ?: []);
         if (empty($tasksData) && $room->members->count() > 0) {
             $tasksData = $room->members->map(function ($m) {
                 return [
                     'user_id' => $m->user_id,
                     'user_name' => $m->user_name,
                     'no_absen' => $m->no_absen,
                     'role' => $m->role ?: 'Kolaborator',
                 ];
             })->toArray();
         }

         unset($coloringData['_meta']);

         // Simpan data submission kelompok ke database
         $submission = CollaborationSubmission::create([
             'room_number' => $room->room_number,
             'group_name' => $groupName,
             'theme' => $room->theme,
             'leader_name' => $leaderName,
             'tasks_data' => $tasksData,
             'coloring_data' => $coloringData,
             'identification_data' => $identificationData,
             'evaluation_data' => $evaluationData,
             'submitted_at' => now(),
         ]);

         // Release live members agar siswa keluar dari lobby
         CollaborationMember::where('room_id', $room->id)->delete();

         // Reset room buffer agar siap digunakan untuk kelompok berikutnya
         $room->update([
             'status' => 'active',
             'group_name' => null,
             'leader_name' => null,
             'coloring_data' => null,
             'tasks_data' => null,
             'identification_data' => null,
             'evaluation_data' => null,
         ]);

         Cache::forget("room_{$roomNumber}_last_color");
         Cache::forget("room_{$roomNumber}_coloring_data");

         return response()->json([
             'status' => 'success',
             'message' => 'Tugas ' . $submission->group_name . ' berhasil dikumpulkan dan tersimpan di sistem guru/admin!',
             'submission' => $submission,
         ]);
     }
}
