<?php

namespace App\Http\Controllers\Admin;

use App\Models\CollaborationRoom;
use App\Models\CollaborationSession;
use App\Models\CollaborationSubmission;
use App\Support\CollaborationSubmissionFormatter;
use Illuminate\Http\Request;

class AdminCollaborationController extends AdminController
{
    /**
     * Display collaboration session manager and paginated group submissions.
     */
    public function index(Request $request)
    {
        $activeSession = CollaborationSession::getActive();
        $search = $request->input('q');
        $sessionFilter = $request->input('session_id');

        $query = CollaborationSubmission::with('session:id,name,class_label,schedule_label');

        if (is_numeric($sessionFilter)) {
            $query->where('collaboration_session_id', (int) $sessionFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('group_name', 'like', "%{$search}%")
                    ->orWhere('theme', 'like', "%{$search}%")
                    ->orWhere('leader_name', 'like', "%{$search}%");
            });
        }

        $submissions = $query->select([
            'id', 'collaboration_session_id', 'room_number', 'group_name', 'theme', 'leader_name',
            'tasks_data', 'identification_data', 'evaluation_data',
            'submitted_at', 'created_at',
        ])->latest('submitted_at')->latest('id')->paginate(9)->withQueryString();

        $sessions = CollaborationSession::withCount('submissions')
            ->orderByDesc('is_active')
            ->latest('id')
            ->paginate(5, ['*'], 'sessions_page')
            ->withQueryString();

        $allSessions = CollaborationSession::orderByDesc('is_active')->latest('id')->get();

        return view('admin.kolaborasi.index', [
            'submissions' => $submissions,
            'sessions' => $sessions,
            'allSessions' => $allSessions,
            'activeSession' => $activeSession,
            'sessionFilter' => $sessionFilter,
            'search' => $search,
        ]);
    }

    /**
     * Store and immediately activate a new collaboration session.
     */
    public function storeSession(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'class_label' => ['nullable', 'string', 'max:60'],
            'schedule_label' => ['nullable', 'string', 'max:80'],
        ]);

        $name = trim($validated['name']);
        $classLabel = isset($validated['class_label']) && trim($validated['class_label']) !== ''
            ? trim($validated['class_label'])
            : null;
        $scheduleLabel = isset($validated['schedule_label']) && trim($validated['schedule_label']) !== ''
            ? trim($validated['schedule_label'])
            : null;

        if ($this->sessionCombinationExists($name, $classLabel, $scheduleLabel)) {
            return back()
                ->withInput()
                ->withErrors(['name' => 'Kombinasi Nama Sesi, Kelas, dan Jadwal sudah ada. Gunakan nama atau jadwal berbeda agar tidak bentrok.']);
        }

        $session = CollaborationSession::create([
            'name' => $name,
            'class_label' => $classLabel,
            'schedule_label' => $scheduleLabel,
            'is_active' => false,
        ]);

        CollaborationSession::activateSession($session);

        return redirect()
            ->route('admin.kolaborasi.index')
            ->with('status', 'Sesi "'.$session->displayLabel().'" berhasil dibuat dan diaktifkan untuk siswa.');
    }

    /**
     * Update metadata of an existing collaboration session.
     */
    public function updateSession(Request $request, int $id)
    {
        $session = CollaborationSession::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'class_label' => ['nullable', 'string', 'max:60'],
            'schedule_label' => ['nullable', 'string', 'max:80'],
        ]);

        $name = trim($validated['name']);
        $classLabel = isset($validated['class_label']) && trim($validated['class_label']) !== ''
            ? trim($validated['class_label'])
            : null;
        $scheduleLabel = isset($validated['schedule_label']) && trim($validated['schedule_label']) !== ''
            ? trim($validated['schedule_label'])
            : null;

        if ($this->sessionCombinationExists($name, $classLabel, $scheduleLabel, $session->id)) {
            return back()
                ->withErrors(['name' => 'Kombinasi Nama Sesi, Kelas, dan Jadwal bentrok dengan sesi lain.']);
        }

        $session->update([
            'name' => $name,
            'class_label' => $classLabel,
            'schedule_label' => $scheduleLabel,
        ]);

        return back()->with('status', 'Informasi sesi berhasil diperbarui.');
    }

    /**
     * Activate a specific collaboration session for all students.
     */
    public function activateSession(int $id)
    {
        $session = CollaborationSession::findOrFail($id);
        CollaborationSession::activateSession($session);

        return back()->with('status', 'Sesi aktif diganti ke "'.$session->displayLabel().'".');
    }

    /**
     * Delete an inactive collaboration session and its rooms/submissions.
     */
    public function destroySession(int $id)
    {
        $session = CollaborationSession::findOrFail($id);

        if ($session->is_active) {
            return back()->withErrors(['session' => 'Sesi yang sedang aktif tidak dapat dihapus. Aktifkan sesi lain terlebih dahulu.']);
        }

        $label = $session->displayLabel();
        $session->delete();

        return back()->with('status', 'Sesi "'.$label.'" berhasil dihapus.');
    }

    /**
     * Display the specified collaboration assignment detail with visual Misi 2 objects.
     */
    public function show($id)
    {
        $submission = CollaborationSubmission::with('session')->findOrFail($id);

        $canvases = CollaborationSubmissionFormatter::buildCanvases('admin-', 'admin-canvas-part');
        $sanitizedColors = CollaborationSubmissionFormatter::sanitizeColorMap(
            is_array($submission->coloring_data) ? $submission->coloring_data : []
        );
        $identificationItems = CollaborationSubmissionFormatter::formatIdentifications(
            is_array($submission->identification_data) ? $submission->identification_data : []
        );

        return view('admin.kolaborasi.show', [
            'submission' => $submission,
            'canvases' => $canvases,
            'sanitizedColors' => $sanitizedColors,
            'identificationItems' => $identificationItems,
        ]);
    }

    /**
     * Delete a submitted collaboration record and reopen the room if needed.
     */
    public function destroy($id)
    {
        $submission = CollaborationSubmission::findOrFail($id);
        $name = $submission->group_name;
        $sessionId = $submission->collaboration_session_id;
        $roomNumber = $submission->room_number;

        $submission->delete();

        if ($sessionId && $roomNumber) {
            $stillExists = CollaborationSubmission::where('collaboration_session_id', $sessionId)
                ->where('room_number', $roomNumber)
                ->exists();

            if (! $stillExists) {
                CollaborationRoom::where('collaboration_session_id', $sessionId)
                    ->where('room_number', $roomNumber)
                    ->update(['status' => 'active']);
            }
        }

        return redirect()->route('admin.kolaborasi.index')->with('status', 'Data tugas '.$name.' berhasil dihapus.');
    }

    /**
     * Check whether a session with the same name, class, and schedule already exists.
     */
    private function sessionCombinationExists(string $name, ?string $classLabel, ?string $scheduleLabel, ?int $ignoreId = null): bool
    {
        $query = CollaborationSession::whereRaw('LOWER(name) = ?', [mb_strtolower($name)]);

        if ($classLabel === null) {
            $query->whereNull('class_label');
        } else {
            $query->whereRaw('LOWER(class_label) = ?', [mb_strtolower($classLabel)]);
        }

        if ($scheduleLabel === null) {
            $query->whereNull('schedule_label');
        } else {
            $query->whereRaw('LOWER(schedule_label) = ?', [mb_strtolower($scheduleLabel)]);
        }

        if ($ignoreId !== null) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }
}
