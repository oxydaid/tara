<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'no_absen' => ['nullable', 'string', 'max:20'],
            'avatar' => ['nullable', 'in:co,ce'],
        ]);

        $user = Auth::user();
        $name = trim($data['name']);
        $noAbsen = isset($data['no_absen']) && trim($data['no_absen']) !== '' ? trim($data['no_absen']) : null;

        // Validasi: Nomor absen tidak boleh sama dengan siswa lain
        if (!empty($noAbsen)) {
            $duplicate = \App\Models\User::where('is_admin', 0)
                ->where('id', '!=', $user->id)
                ->where('no_absen', $noAbsen)
                ->exists();

            if ($duplicate) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Nomor absen ' . $noAbsen . ' sudah digunakan oleh siswa lain. Silakan gunakan nomor absenmu!',
                    ], 422);
                }

                return back()->withErrors(['no_absen' => 'Nomor absen ' . $noAbsen . ' sudah digunakan oleh siswa lain.']);
            }
        }

        $payload = [
            'name' => $name,
            'no_absen' => $noAbsen,
        ];

        if (!empty($data['avatar'])) {
            $payload['avatar'] = $data['avatar'];
        }

        $user->update($payload);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Identitas profil berhasil diperbarui!',
                'user' => [
                    'name' => $user->name,
                    'no_absen' => $user->no_absen,
                    'avatar' => $user->avatar,
                ],
            ]);
        }

        return redirect()->route('menu')->with('status', 'Profil berhasil disimpan!');
    }
}
