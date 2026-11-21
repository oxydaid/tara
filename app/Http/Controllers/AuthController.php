<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('menu');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'no_absen' => ['required', 'string', 'max:20'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'no_absen.required' => 'Nomor absen wajib diisi.',
        ]);

        $name = trim($data['name']);
        $noAbsen = trim($data['no_absen']);

        // Check if student with this no_absen exists
        $userByAbsen = User::where('is_admin', 0)->where('no_absen', $noAbsen)->first();

        if ($userByAbsen) {
            if (strcasecmp($userByAbsen->name, $name) === 0) {
                $user = $userByAbsen;
            } else {
                return back()
                    ->withInput()
                    ->withErrors(['no_absen' => 'Nomor absen ' . $noAbsen . ' sudah terdaftar untuk siswa ' . $userByAbsen->name . '. Silakan periksa kembali nama & nomor absenmu!']);
            }
        } else {
            // Check if user with this name exists and has no absen yet
            $userByName = User::where('is_admin', 0)->where('name', $name)->whereNull('no_absen')->first();

            if ($userByName) {
                $userByName->update(['no_absen' => $noAbsen]);
                $user = $userByName;
            } else {
                $cleanName = strtolower(preg_replace('/[^a-z0-9]/', '', $name)) ?: 'siswa';
                $email = "absen_{$noAbsen}_{$cleanName}@tara.local";

                if (User::where('email', $email)->exists()) {
                    $email = "absen_{$noAbsen}_{$cleanName}_" . time() . "@tara.local";
                }

                $user = User::create([
                    'name' => $name,
                    'no_absen' => $noAbsen,
                    'email' => $email,
                    'password' => bcrypt('tara123'),
                    'avatar' => 'co',
                    'is_admin' => 0,
                ]);
            }
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(route('menu'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'no_absen' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $data['avatar'] = 'co';
        $data['is_admin'] = 0;

        $user = User::create($data);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('menu');
    }

    public function logout(Request $request)
    {
        // When student logs out, also remove them from active collaboration room
        if (Auth::check()) {
            \App\Models\CollaborationMember::where('user_id', Auth::id())->delete();
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->to('/');
    }
}
