<?php

namespace App\Http\Controllers\Admin;

use App\Models\BangunDatarShape;
use App\Models\CollaborationRoom;
use App\Models\CollaborationSession;
use App\Models\CollaborationSubmission;
use App\Models\User;

class AdminDashboardController extends AdminController
{
    public function index()
    {
        $activeSession = CollaborationSession::getActive();

        $totalStudents = User::where('is_admin', 0)->count();
        $totalSessions = CollaborationSession::count();
        $totalActiveSessionSubmissions = CollaborationSubmission::where('collaboration_session_id', $activeSession->id)->count();
        $totalCollaboration = CollaborationSubmission::count();
        $totalActiveShapes = BangunDatarShape::where('is_active', true)->count();

        $activeRooms = CollaborationRoom::with([
            'members' => function ($query) {
                $query->orderBy('id', 'asc');
            },
        ])
            ->where('collaboration_session_id', $activeSession->id)
            ->orderBy('room_number')
            ->take(8)
            ->get();

        $recentSubmissions = CollaborationSubmission::with('session:id,name,class_label,schedule_label')
            ->select([
                'id', 'collaboration_session_id', 'room_number', 'group_name',
                'leader_name', 'tasks_data', 'submitted_at', 'created_at',
            ])
            ->latest('submitted_at')
            ->latest('id')
            ->take(6)
            ->get();

        $recentStudents = User::where('is_admin', 0)
            ->select('id', 'name', 'no_absen', 'created_at')
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', [
            'activeSession' => $activeSession,
            'totalStudents' => $totalStudents,
            'totalSessions' => $totalSessions,
            'totalActiveSessionSubmissions' => $totalActiveSessionSubmissions,
            'totalCollaboration' => $totalCollaboration,
            'totalActiveShapes' => $totalActiveShapes,
            'activeRooms' => $activeRooms,
            'recentSubmissions' => $recentSubmissions,
            'recentStudents' => $recentStudents,
        ]);
    }
}
