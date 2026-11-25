<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveRoomDataRequest;
use App\Http\Requests\UpdateColorPartRequest;
use App\Models\BangunDatarShape;
use App\Models\CollaborationMember;
use App\Models\CollaborationRoom;
use App\Models\CollaborationSession;
use App\Models\CollaborationSubmission;
use App\Models\User;
use App\Support\CollaborationSubmissionFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CollaborationController extends Controller
{
    /**
     * Build a session-scoped cache key for real-time canvas data.
     */
    private function cacheColorKey(int $sessionId, int|string $roomNumber, string $suffix): string
    {
        return "session_{$sessionId}_room_{$roomNumber}_{$suffix}";
    }

    /**
     * Merge per-evaluator appreciation entries so teammates do not overwrite each other.
     *
     * @param  array<mixed>  $existing
     * @param  array<mixed>  $incoming
     * @return array<int, array<string, mixed>>
     */
    private function mergeEvaluationEntries(array $existing, array $incoming, ?int $evaluatorId): array
    {
        $kept = [];

        foreach ($existing as $entry) {
            if (! is_array($entry)) {
                continue;
            }
            $entryEvaluatorId = isset($entry['evaluator_id']) ? (int) $entry['evaluator_id'] : null;
            if ($evaluatorId !== null && $entryEvaluatorId === $evaluatorId) {
                continue;
            }
            $kept[] = $entry;
        }

        foreach ($incoming as $entry) {
            if (! is_array($entry)) {
                continue;
            }
            if ($evaluatorId !== null && empty($entry['evaluator_id'])) {
                $entry['evaluator_id'] = $evaluatorId;
            }
            $kept[] = $entry;
        }

        return array_values($kept);
    }

    /**
     * Merge cumulative session members across existing tasks snapshot, active members, and request payload.
     *
     * @param  array<mixed>  $existingTasks
     * @param  iterable<CollaborationMember>  $members
     * @param  array<mixed>  $incomingTasks
     * @return array<int, array<string, mixed>>
     */
    private function mergeTeamMembers(array $existingTasks, iterable $members, array $incomingTasks = []): array
    {
        $roster = [];

        $upsert = static function (array $entry) use (&$roster): void {
            $uid = isset($entry['user_id']) && $entry['user_id'] !== '' ? 'u_'.$entry['user_id'] : null;
            $key = $uid ?? ('n_'.mb_strtolower(trim((string) ($entry['user_name'] ?? ''))));
            if ($key === 'n_') {
                return;
            }
            $roster[$key] = [
                'user_id' => $entry['user_id'] ?? ($roster[$key]['user_id'] ?? null),
                'user_name' => $entry['user_name'] ?? ($roster[$key]['user_name'] ?? 'Siswa'),
                'no_absen' => $entry['no_absen'] ?? ($roster[$key]['no_absen'] ?? '-'),
                'role' => $entry['role'] ?? ($roster[$key]['role'] ?? 'Kolaborator'),
            ];
        };

        foreach ($existingTasks as $task) {
            if (is_array($task)) {
                $upsert($task);
            }
        }

        foreach ($incomingTasks as $task) {
            if (is_array($task)) {
                $upsert($task);
            }
        }

        foreach ($members as $member) {
            $upsert([
                'user_id' => $member->user_id,
                'user_name' => $member->user_name,
                'no_absen' => $member->no_absen ?: '-',
                'role' => $member->role ?: 'Kolaborator',
            ]);
        }

        return array_values($roster);
    }

    /**
     * Record a user into the room's cumulative tasks_data roster if not already present.
     */
    private function recordMemberInRoomRoster(CollaborationRoom $room, User $user, string $role = 'Kolaborator'): void
    {
        $existing = is_array($room->tasks_data) ? $room->tasks_data : [];
        $updated = $this->mergeTeamMembers($existing, [], [[
            'user_id' => $user->id,
            'user_name' => $user->name ?? 'Siswa '.$user->id,
            'no_absen' => $user->no_absen ?? '-',
            'role' => $role,
        ]]);

        $room->update(['tasks_data' => $updated]);
    }

    /**
     * Clean up inactive members whose last active heartbeat is older than 25 seconds.
     */
    private function pruneInactiveMembers(): void
    {
        if (Cache::add('tara_prune_inactive_members_lock', true, 10)) {
            CollaborationMember::where('last_active_at', '<', now()->subSeconds(25))->delete();
        }
    }

    /**
     * Display the main collaboration view scoped to the admin's active session.
     */
    public function index()
    {
        $this->pruneInactiveMembers();
        $activeSession = CollaborationSession::getActive();

        $rooms = CollaborationRoom::with('members')
            ->where('collaboration_session_id', $activeSession->id)
            ->orderBy('room_number')
            ->take(8)
            ->get();

        $currentUser = Auth::user();
        $activeRoomIds = $rooms->pluck('id');
        $activeMember = $currentUser
            ? CollaborationMember::where('user_id', $currentUser->id)->whereIn('room_id', $activeRoomIds)->first()
            : null;
        $currentRoomId = $activeMember ? $activeMember->room_id : null;
        $activeRoom = $currentRoomId ? $rooms->firstWhere('id', $currentRoomId) : null;
        $activeRoomMembers = $activeRoom ? $activeRoom->members : collect();

        $bangunDatarShapes = BangunDatarShape::getCachedActive();

        return view('kolaborasi', compact(
            'activeSession',
            'rooms',
            'currentUser',
            'currentRoomId',
            'activeRoomMembers',
            'bangunDatarShapes'
        ));
    }

    /**
     * Get live status of the 8 rooms in the active session for real-time polling.
     */
    public function getRooms()
    {
        $this->pruneInactiveMembers();
        $activeSession = CollaborationSession::getActive();

        $rooms = CollaborationRoom::with([
            'members' => function ($q) {
                $q->select('id', 'room_id', 'user_id', 'user_name', 'no_absen', 'role', 'created_at')->orderBy('id', 'asc');
            },
        ])
            ->select('id', 'collaboration_session_id', 'room_number', 'name', 'theme', 'max_members', 'status', 'group_name')
            ->where('collaboration_session_id', $activeSession->id)
            ->orderBy('room_number')
            ->take(8)
            ->get();

        $formattedRooms = $rooms->map(function ($room) {
            $count = $room->members->count();
            $isCompleted = $room->status === 'completed';

            return [
                'id' => $room->id,
                'room_number' => $room->room_number,
                'name' => $room->name,
                'theme' => $room->theme,
                'max_members' => $room->max_members,
                'member_count' => $count,
                'status' => $room->status,
                'is_completed' => $isCompleted,
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
        $allMembers = $rooms->pluck('members')->flatten();
        $userActiveMember = $currentUser ? $allMembers->firstWhere('user_id', $currentUser->id) : null;

        return response()->json([
            'status' => 'success',
            'session_id' => $activeSession->id,
            'active_session' => [
                'id' => $activeSession->id,
                'name' => $activeSession->name,
                'label' => $activeSession->displayLabel(),
            ],
            'rooms' => $formattedRooms,
            'current_user_id' => $currentUser ? $currentUser->id : null,
            'current_room_id' => $userActiveMember ? $userActiveMember->room_id : null,
        ]);
    }

    /**
     * Create a new collaboration room dynamically in the active session.
     */
    public function createRoom(Request $request)
    {
        $request->validate([
            'group_name' => 'required|string|max:80',
            'theme' => 'nullable|string|max:50',
        ]);

        $this->pruneInactiveMembers();
        $activeSession = CollaborationSession::getActive();
        $user = Auth::user();

        $lastRoomNumber = CollaborationRoom::where('collaboration_session_id', $activeSession->id)->max('room_number') ?? 0;
        $nextRoomNumber = $lastRoomNumber + 1;

        $room = CollaborationRoom::create([
            'collaboration_session_id' => $activeSession->id,
            'room_number' => $nextRoomNumber,
            'name' => $request->group_name,
            'theme' => $request->theme ?? 'Motif Damar Kurung',
            'max_members' => 5,
            'status' => 'active',
            'group_name' => $request->group_name,
            'leader_name' => $user->name ?? 'Ketua',
        ]);

        CollaborationMember::where('user_id', $user->id)->delete();

        CollaborationMember::create([
            'room_id' => $room->id,
            'user_id' => $user->id,
            'user_name' => $user->name ?? 'Siswa '.$user->id,
            'no_absen' => $user->no_absen ?? '-',
            'role' => 'Kolaborator',
            'last_active_at' => now(),
        ]);

        $this->recordMemberInRoomRoster($room, $user);

        return response()->json([
            'status' => 'success',
            'session_id' => $activeSession->id,
            'message' => 'Ruang kelompok '.$room->group_name.' berhasil dibuat!',
            'room' => $room->load('members'),
        ]);
    }

    /**
     * Join a collaboration room in the active session (enforcing 5 members max and completed-room lock).
     */
    public function joinRoom(Request $request)
    {
        $request->validate([
            'room_number' => 'required|integer',
        ]);

        $this->pruneInactiveMembers();
        $activeSession = CollaborationSession::getActive();

        $room = CollaborationRoom::where('collaboration_session_id', $activeSession->id)
            ->where('room_number', $request->room_number)
            ->firstOrFail();
        $user = Auth::user();

        if ($room->status === 'completed') {
            return response()->json([
                'status' => 'error',
                'message' => 'Ruang '.$room->room_number.' sudah menyelesaikan dan mengumpulkan tugas pada sesi ini.',
            ], 422);
        }

        $existingInThisRoom = CollaborationMember::where('room_id', $room->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingInThisRoom) {
            $existingInThisRoom->update([
                'user_name' => $user->name,
                'no_absen' => $user->no_absen,
                'last_active_at' => now(),
            ]);
            $this->recordMemberInRoomRoster($room, $user, $existingInThisRoom->role ?: 'Kolaborator');

            return response()->json([
                'status' => 'success',
                'session_id' => $activeSession->id,
                'message' => 'Kembali ke ruang kolaborasi.',
                'room' => $room->load('members'),
            ]);
        }

        try {
            DB::transaction(function () use ($room, $user) {
                $lockedRoom = CollaborationRoom::where('id', $room->id)->lockForUpdate()->first();

                if ($lockedRoom->status === 'completed') {
                    throw new \RuntimeException('ROOM_COMPLETED');
                }

                $currentCount = CollaborationMember::where('room_id', $lockedRoom->id)->count();
                if ($currentCount >= $lockedRoom->max_members) {
                    throw new \RuntimeException('ROOM_FULL');
                }

                CollaborationMember::where('user_id', $user->id)->delete();

                CollaborationMember::create([
                    'room_id' => $lockedRoom->id,
                    'user_id' => $user->id,
                    'user_name' => $user->name ?? 'Siswa '.$user->id,
                    'no_absen' => $user->no_absen ?? '-',
                    'role' => 'Kolaborator',
                    'last_active_at' => now(),
                ]);

                $this->recordMemberInRoomRoster($lockedRoom, $user);
            });
        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'ROOM_COMPLETED') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ruang '.$room->room_number.' sudah menyelesaikan dan mengumpulkan tugas pada sesi ini.',
                ], 422);
            }
            if ($e->getMessage() === 'ROOM_FULL') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Maaf, Ruang '.$room->room_number.' sudah penuh (maksimal '.$room->max_members.' siswa). Silakan pilih ruang lain!',
                ], 422);
            }
            throw $e;
        }

        return response()->json([
            'status' => 'success',
            'session_id' => $activeSession->id,
            'message' => 'Berhasil masuk ke '.$room->name,
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
     * Get real-time room work data for the active session.
     */
    public function getRoomData($roomNumber)
    {
        $this->pruneInactiveMembers();
        $activeSession = CollaborationSession::getActive();

        $room = CollaborationRoom::where('collaboration_session_id', $activeSession->id)
            ->where('room_number', $roomNumber)
            ->with('members')
            ->firstOrFail();

        if ($room->status === 'completed') {
            return response()->json([
                'status' => 'completed',
                'session_id' => $activeSession->id,
                'message' => 'Tugas kelompok di Ruang '.$room->room_number.' sudah dikumpulkan bersama tim!',
            ]);
        }

        $user = Auth::user();

        if ($user) {
            $member = $room->members->firstWhere('user_id', $user->id);
            if (! $member) {
                $currentCount = CollaborationMember::where('room_id', $room->id)->count();
                if ($currentCount >= $room->max_members) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Maaf, Ruang '.$room->room_number.' sudah penuh (maksimal '.$room->max_members.' siswa). Silakan pilih ruang lain!',
                    ], 422);
                }

                CollaborationMember::where('user_id', $user->id)->delete();
                CollaborationMember::create([
                    'room_id' => $room->id,
                    'user_id' => $user->id,
                    'user_name' => $user->name ?? 'Siswa '.$user->id,
                    'no_absen' => $user->no_absen ?? '-',
                    'role' => 'Kolaborator',
                    'last_active_at' => now(),
                ]);
                $this->recordMemberInRoomRoster($room, $user);
                $room->load('members');
            } else {
                $member->update(['last_active_at' => now()]);
            }
        }

        return response()->json([
            'status' => 'success',
            'session_id' => $activeSession->id,
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
     * Save room work data (real-time sync, merging evaluations per evaluator).
     */
    public function saveRoomData(SaveRoomDataRequest $request, $roomNumber)
    {
        $this->pruneInactiveMembers();
        $activeSession = CollaborationSession::getActive();

        $room = CollaborationRoom::where('collaboration_session_id', $activeSession->id)
            ->where('room_number', $roomNumber)
            ->with('members')
            ->firstOrFail();

        if ($room->status === 'completed') {
            return response()->json([
                'status' => 'completed',
                'session_id' => $activeSession->id,
                'message' => 'Tugas kelompok di ruang ini sudah dikumpulkan!',
            ]);
        }

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
        if ($request->has('identification_data')) {
            $room->identification_data = $request->identification_data;
        }
        if ($request->has('evaluation_data')) {
            $room->evaluation_data = $this->mergeEvaluationEntries(
                is_array($room->evaluation_data) ? $room->evaluation_data : [],
                is_array($request->evaluation_data) ? $request->evaluation_data : [],
                $user?->id
            );
        }

        if ($user) {
            CollaborationMember::where('room_id', $room->id)
                ->where('user_id', $user->id)
                ->update(['last_active_at' => now()]);
        }

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

        $room->load('members');
        $room->tasks_data = $this->mergeTeamMembers(
            is_array($room->tasks_data) ? $room->tasks_data : [],
            $room->members,
            is_array($request->tasks_data) ? $request->tasks_data : []
        );

        $room->save();

        if ($request->has('coloring_data')) {
            Cache::put($this->cacheColorKey($activeSession->id, $roomNumber, 'coloring_data'), $room->coloring_data, 60);
        }

        return response()->json([
            'status' => 'success',
            'session_id' => $activeSession->id,
            'message' => 'Data kerja kelompok berhasil diperbarui!',
            'room' => $room,
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
    public function updateColorPart(UpdateColorPartRequest $request, $roomNumber)
    {
        $activeSession = CollaborationSession::getActive();
        $room = CollaborationRoom::where('collaboration_session_id', $activeSession->id)
            ->where('room_number', $roomNumber)
            ->firstOrFail();

        if ($room->status === 'completed') {
            return response()->json([
                'status' => 'completed',
                'session_id' => $activeSession->id,
                'message' => 'Tugas kelompok di ruang ini sudah dikumpulkan!',
            ]);
        }

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
            'canvas_num' => (int) ($request->canvas_num ?? 1),
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

        Cache::put($this->cacheColorKey($activeSession->id, $roomNumber, 'last_color'), $lastColorEvent, 60);
        Cache::put($this->cacheColorKey($activeSession->id, $roomNumber, 'coloring_data'), $coloring, 60);

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
        $activeSession = CollaborationSession::getActive();
        $room = CollaborationRoom::where('collaboration_session_id', $activeSession->id)
            ->where('room_number', $roomNumber)
            ->firstOrFail();

        if ($room->status === 'completed') {
            return response()->json([
                'status' => 'completed',
                'session_id' => $activeSession->id,
                'message' => 'Tugas kelompok di ruang ini sudah dikumpulkan!',
            ]);
        }

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
            'canvas_num' => (int) $canvasNum,
            'user_id' => $user ? $user->id : null,
            'user_name' => $user ? $user->name : 'Teman',
            'time' => round(microtime(true) * 1000),
        ];

        $coloring['_meta'] = $lastResetEvent;
        $room->coloring_data = $coloring;
        $room->save();

        Cache::put($this->cacheColorKey($activeSession->id, $roomNumber, 'last_color'), $lastResetEvent, 60);
        Cache::put($this->cacheColorKey($activeSession->id, $roomNumber, 'coloring_data'), $coloring, 60);

        return response()->json([
            'status' => 'success',
            'coloring_data' => $room->coloring_data,
            'last_colored' => $lastResetEvent,
        ]);
    }

    /**
     * Ultra-fast endpoint for polling live canvas coloring data scoped to the active session.
     */
    public function getLiveColoring($roomNumber)
    {
        $activeSession = CollaborationSession::getActive();
        $dataKey = $this->cacheColorKey($activeSession->id, $roomNumber, 'coloring_data');
        $lastKey = $this->cacheColorKey($activeSession->id, $roomNumber, 'last_color');

        $coloring = Cache::remember($dataKey, 60, function () use ($activeSession, $roomNumber) {
            $room = CollaborationRoom::where('collaboration_session_id', $activeSession->id)
                ->where('room_number', $roomNumber)
                ->select('id', 'coloring_data')
                ->first();

            return $room ? ($room->coloring_data ?: []) : [];
        });

        $lastColor = Cache::get($lastKey, $coloring['_meta'] ?? null);

        return response()->json([
            'status' => 'success',
            'coloring_data' => $coloring,
            'last_colored' => $lastColor,
        ]);
    }

    /**
     * Submit and lock room work for the active session (enforcing 1 room = 1 submission per session).
     */
    public function submitRoom(Request $request, $roomNumber)
    {
        $activeSession = CollaborationSession::getActive();
        $user = Auth::user();

        $result = DB::transaction(function () use ($activeSession, $roomNumber, $request, $user) {
            $lockedRoom = CollaborationRoom::where('collaboration_session_id', $activeSession->id)
                ->where('room_number', $roomNumber)
                ->with('members')
                ->lockForUpdate()
                ->firstOrFail();

            $existingSubmission = CollaborationSubmission::where('collaboration_session_id', $activeSession->id)
                ->where('room_number', $lockedRoom->room_number)
                ->first();

            if ($lockedRoom->status === 'completed' || $existingSubmission) {
                CollaborationMember::where('room_id', $lockedRoom->id)->delete();

                return [
                    'already_submitted' => true,
                    'submission' => $existingSubmission,
                    'room' => $lockedRoom,
                ];
            }

            $groupName = $request->group_name ?: ($lockedRoom->group_name ?: ('Kelompok '.($lockedRoom->theme ?: 'Ruang '.$lockedRoom->room_number)));
            $leaderName = $request->leader_name ?: $lockedRoom->leader_name;
            $coloringData = $request->coloring_data ?: ($lockedRoom->coloring_data ?: []);
            $identificationData = $request->identification_data ?: ($lockedRoom->identification_data ?: []);

            $existingEvaluations = is_array($lockedRoom->evaluation_data) ? $lockedRoom->evaluation_data : [];
            $evaluationData = $request->filled('evaluation_data')
                ? $this->mergeEvaluationEntries(
                    $existingEvaluations,
                    is_array($request->evaluation_data) ? $request->evaluation_data : [],
                    $user?->id
                )
                : $existingEvaluations;

            $tasksData = $this->mergeTeamMembers(
                is_array($lockedRoom->tasks_data) ? $lockedRoom->tasks_data : [],
                $lockedRoom->members,
                is_array($request->tasks_data) ? $request->tasks_data : []
            );

            if (empty($tasksData) && $user) {
                $tasksData = [[
                    'user_id' => $user->id,
                    'user_name' => $user->name ?? 'Siswa',
                    'no_absen' => $user->no_absen ?? '-',
                    'role' => 'Kolaborator',
                ]];
            }

            unset($coloringData['_meta']);

            $submission = CollaborationSubmission::create([
                'collaboration_session_id' => $activeSession->id,
                'room_number' => $lockedRoom->room_number,
                'group_name' => $groupName,
                'theme' => $lockedRoom->theme,
                'leader_name' => $leaderName,
                'tasks_data' => $tasksData,
                'coloring_data' => $coloringData,
                'identification_data' => $identificationData,
                'evaluation_data' => $evaluationData,
                'submitted_at' => now(),
            ]);

            CollaborationMember::where('room_id', $lockedRoom->id)->delete();

            $lockedRoom->update([
                'status' => 'completed',
                'group_name' => null,
                'leader_name' => null,
                'coloring_data' => null,
                'tasks_data' => null,
                'identification_data' => null,
                'evaluation_data' => null,
            ]);

            return [
                'already_submitted' => false,
                'submission' => $submission,
                'room' => $lockedRoom,
            ];
        });

        Cache::forget($this->cacheColorKey($activeSession->id, $roomNumber, 'last_color'));
        Cache::forget($this->cacheColorKey($activeSession->id, $roomNumber, 'coloring_data'));

        if ($result['already_submitted']) {
            return response()->json([
                'status' => 'already_submitted',
                'message' => 'Tugas kelompok di Ruang '.$result['room']->room_number.' sudah dikumpulkan oleh teman satu tim!',
                'submission' => $result['submission'],
            ]);
        }

        $submission = $result['submission'];

        return response()->json([
            'status' => 'success',
            'message' => 'Tugas '.$submission->group_name.' berhasil dikumpulkan dan tersimpan di sistem guru/admin!',
            'submission' => $submission,
        ]);
    }

    /**
     * Display collaboration results and live room progress scoped to the active session.
     */
    public function hasil()
    {
        $this->pruneInactiveMembers();
        $activeSession = CollaborationSession::getActive();

        $rooms = CollaborationRoom::with([
            'members' => function ($query) {
                $query->orderBy('id', 'asc');
            },
        ])
            ->where('collaboration_session_id', $activeSession->id)
            ->orderBy('room_number')
            ->take(8)
            ->get();

        $submissions = CollaborationSubmission::where('collaboration_session_id', $activeSession->id)
            ->select([
                'id', 'collaboration_session_id', 'room_number', 'group_name', 'theme', 'leader_name',
                'tasks_data', 'identification_data', 'evaluation_data',
                'submitted_at', 'created_at',
            ])
            ->latest('submitted_at')
            ->latest('id')
            ->paginate(6)
            ->withQueryString();

        $submissionsByRoom = CollaborationSubmission::where('collaboration_session_id', $activeSession->id)
            ->select([
                'id', 'room_number', 'group_name', 'leader_name',
                'tasks_data', 'submitted_at', 'created_at',
            ])
            ->latest('submitted_at')
            ->latest('id')
            ->get()
            ->keyBy('room_number');

        $roomStatuses = $rooms->map(function (CollaborationRoom $room) use ($submissionsByRoom) {
            $memberCount = $room->members->count();
            $latestSubmission = $submissionsByRoom->get($room->room_number);

            if ($room->status === 'completed' || $latestSubmission) {
                $statusKey = 'completed';
                $statusLabel = 'Sudah Selesai';
                $statusDescription = 'Kelompok di ruang ini sudah menyelesaikan seluruh misi dan mengumpulkan hasilnya.';
            } elseif ($memberCount > 0) {
                $statusKey = 'in_progress';
                $statusLabel = 'Sedang Dikerjakan';
                $statusDescription = 'Kelompok sedang mengerjakan misi. Hasil dapat dilihat setelah tugas dikumpulkan.';
            } else {
                $statusKey = 'not_started';
                $statusLabel = 'Belum Selesai';
                $statusDescription = 'Belum ada kelompok yang menyelesaikan misi di ruang ini.';
            }

            return [
                'room_number' => $room->room_number,
                'name' => $room->name,
                'theme' => $room->theme,
                'member_count' => $memberCount,
                'max_members' => $room->max_members ?? 5,
                'active_group_name' => $room->group_name,
                'status_key' => $statusKey,
                'status_label' => $statusLabel,
                'status_description' => $statusDescription,
                'latest_submission' => $latestSubmission,
            ];
        });

        return view('hasil.index', [
            'activeSession' => $activeSession,
            'roomStatuses' => $roomStatuses,
            'submissions' => $submissions,
            'currentUser' => Auth::user(),
        ]);
    }

    /**
     * Display detailed collaboration result for a submitted group.
     */
    public function showHasil(int $id)
    {
        $submission = CollaborationSubmission::with('session')->findOrFail($id);

        $canvases = CollaborationSubmissionFormatter::buildCanvases('hasil-', 'hasil-canvas-part');
        $sanitizedColors = CollaborationSubmissionFormatter::sanitizeColorMap(
            is_array($submission->coloring_data) ? $submission->coloring_data : []
        );
        $identificationItems = CollaborationSubmissionFormatter::formatIdentifications(
            is_array($submission->identification_data) ? $submission->identification_data : []
        );

        return view('hasil.show', [
            'submission' => $submission,
            'canvases' => $canvases,
            'sanitizedColors' => $sanitizedColors,
            'identificationItems' => $identificationItems,
        ]);
    }
}
