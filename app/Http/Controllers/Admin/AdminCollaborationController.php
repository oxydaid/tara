<?php

namespace App\Http\Controllers\Admin;

use App\Models\CollaborationSubmission;
use Illuminate\Http\Request;

class AdminCollaborationController extends AdminController
{
    /**
     * Display a listing of submitted collaboration assignments per group.
     */
    public function index(Request $request)
    {
        $search = $request->input('q');
        $query = CollaborationSubmission::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('group_name', 'like', "%{$search}%")
                  ->orWhere('theme', 'like', "%{$search}%")
                  ->orWhere('leader_name', 'like', "%{$search}%");
            });
        }

        // Exclude heavy SVG 'coloring_data' on list view for faster query & lower memory usage
        $submissions = $query->select([
            'id', 'room_number', 'group_name', 'theme', 'leader_name',
            'tasks_data', 'identification_data', 'evaluation_data',
            'submitted_at', 'created_at'
        ])->latest('submitted_at')->paginate(12)->withQueryString();

        return view('admin.kolaborasi.index', compact('submissions', 'search'));
    }

    /**
     * Display the specified collaboration assignment detail.
     */
    public function show($id)
    {
        $submission = CollaborationSubmission::findOrFail($id);
        return view('admin.kolaborasi.show', compact('submission'));
    }

    /**
     * Delete a submitted collaboration record.
     */
    public function destroy($id)
    {
        $submission = CollaborationSubmission::findOrFail($id);
        $name = $submission->group_name;
        $submission->delete();

        return redirect()->route('admin.kolaborasi.index')->with('status', 'Data tugas ' . $name . ' berhasil dihapus.');
    }
}
