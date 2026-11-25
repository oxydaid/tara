<?php

namespace App\Http\Controllers\Admin;

use App\Models\CollaborationMember;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends AdminController
{
    public function index(Request $request)
    {
        $search = $request->query('q');

        $query = User::where('is_admin', 0);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('no_absen', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        $activeMemberships = CollaborationMember::with([
            'room:id,collaboration_session_id,room_number,name',
            'room.session:id,name',
        ])
            ->whereIn('user_id', $users->pluck('id'))
            ->get()
            ->keyBy('user_id');

        return view('admin.users.index', [
            'users' => $users,
            'activeMemberships' => $activeMemberships,
            'search' => $search,
        ]);
    }

    public function resetProgress($id)
    {
        $user = User::where('is_admin', 0)->findOrFail($id);
        CollaborationMember::where('user_id', $user->id)->delete();

        return back()->with('status', "Status ruang kolaborasi aktif untuk siswa '{$user->name}' berhasil direset.");
    }

    public function destroy($id)
    {
        $user = User::where('is_admin', 0)->findOrFail($id);
        CollaborationMember::where('user_id', $user->id)->delete();
        $user->delete();

        return back()->with('status', "Data siswa '{$user->name}' berhasil dihapus.");
    }
}
