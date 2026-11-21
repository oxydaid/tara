<?php

namespace App\Http\Controllers\Admin;

use App\Models\StudentSubmission;
use App\Services\StudentSubmissionExportService;
use Illuminate\Http\Request;

class AdminSubmissionController extends AdminController
{
    public function index(Request $request)
    {
        $stepFilter = $request->query('module');
        $search = $request->query('q');

        $query = StudentSubmission::with('user:id,name,avatar,no_absen')->latest();

        if ($stepFilter) {
            $query->where('step_key', $stepFilter);
        }

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $submissions = $query->paginate(15)->withQueryString();

        // 1 single grouped query replaces 7 distinct table scans
        $stepCounts = StudentSubmission::selectRaw('step_key, count(*) as count')
            ->groupBy('step_key')
            ->pluck('count', 'step_key');

        $counts = [
            'all' => $stepCounts->sum(),
            'ide_bisnis' => $stepCounts->get('ide_bisnis', 0),
            'game_belanja' => $stepCounts->get('game_belanja', 0),
            'hitung_modal' => $stepCounts->get('hitung_modal', 0),
            'hitung_total_usaha' => $stepCounts->get('hitung_total_usaha', 0),
            'studi_kasus_tabungan' => $stepCounts->get('studi_kasus_tabungan', 0),
            'studi_kasus_investasi' => $stepCounts->get('studi_kasus_investasi', 0),
        ];

        return view('admin.submissions.index', compact('submissions', 'counts', 'stepFilter', 'search'));
    }

    public function exportExcel(StudentSubmissionExportService $exportService)
    {
        return $exportService->export();
    }

    public function destroy($id)
    {
        $submission = StudentSubmission::findOrFail($id);
        $submission->delete();

        return back()->with('status', 'Jawaban siswa berhasil dihapus.');
    }
}
