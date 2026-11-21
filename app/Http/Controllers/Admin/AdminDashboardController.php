<?php

namespace App\Http\Controllers\Admin;

use App\Models\CollaborationSubmission;
use App\Models\SpendingItem;
use App\Models\StudentSubmission;
use App\Models\User;

class AdminDashboardController extends AdminController
{
    public function index()
    {
        $totalStudents = User::where('is_admin', 0)->count();
        $totalSpendingItems = SpendingItem::count();
        $totalCollaboration = CollaborationSubmission::count();

        // 1 consolidated query for all step counts instead of 4 separate table scans
        $submissionCounts = StudentSubmission::selectRaw('step_key, count(*) as count')
            ->groupBy('step_key')
            ->pluck('count', 'step_key');

        $totalSubmissions = $submissionCounts->sum();
        $totalIdeBisnis = $submissionCounts->get('ide_bisnis', 0);
        $totalRencanaKeuangan = $submissionCounts->get('hitung_modal', 0);
        $totalPengembanganBisnis = $submissionCounts->get('studi_kasus_investasi', 0);

        $recentSubmissions = StudentSubmission::with('user:id,name,avatar,no_absen')
            ->latest()
            ->take(8)
            ->get();

        $recentStudents = User::where('is_admin', 0)
            ->select('id', 'name', 'avatar', 'no_absen', 'created_at')
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', [
            'totalStudents' => $totalStudents,
            'totalSubmissions' => $totalSubmissions,
            'totalCollaboration' => $totalCollaboration,
            'totalIdeBisnis' => $totalIdeBisnis,
            'totalRencanaKeuangan' => $totalRencanaKeuangan,
            'totalPengembanganBisnis' => $totalPengembanganBisnis,
            'totalSpendingItems' => $totalSpendingItems,
            'recentSubmissions' => $recentSubmissions,
            'recentStudents' => $recentStudents,
        ]);
    }
}
