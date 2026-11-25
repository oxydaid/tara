<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminBangunDatarController;
use App\Http\Controllers\Admin\AdminCollaborationController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CollaborationController;
use App\Http\Controllers\ProfileController;
use App\Models\BangunDatarShape;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/menu', function () {
    return view('menu');
})->middleware('auth')->name('menu');

Route::middleware('auth')->group(function () {
    // 1. Profil Media & Pembelajaran TARA
    Route::get('/profil', function () {
        return view('profil');
    })->name('profil');

    // 2. Ruang Eksplorasi
    Route::get('/ruang-eksplorasi', function () {
        return view('eksplorasi.index');
    })->name('eksplorasi');

    Route::get('/ruang-eksplorasi/damar-kurung', function () {
        return view('eksplorasi.damar-kurung');
    })->name('eksplorasi.damar-kurung');
    Route::get('/damar-kurung', function () {
        return redirect()->route('eksplorasi.damar-kurung');
    })->name('damar-kurung');

    Route::get('/ruang-eksplorasi/bangun-datar', function () {
        $shapes = BangunDatarShape::getCachedActive();

        return view('eksplorasi.bangun-datar', compact('shapes'));
    })->name('eksplorasi.bangun-datar');
    Route::get('/bangun-datar', function () {
        return redirect()->route('eksplorasi.bangun-datar');
    })->name('bangun-datar');

    Route::get('/api/bangun-datar-shapes', function () {
        $shapes = BangunDatarShape::getCachedActive();

        return response()->json([
            'status' => 'success',
            'shapes' => $shapes,
        ]);
    })->name('api.bangun-datar-shapes');

    // 3. Kolaborasi Center
    Route::get('/kolaborasi', [CollaborationController::class, 'index'])->name('kolaborasi');
    Route::get('/api/kolaborasi/rooms', [CollaborationController::class, 'getRooms'])->name('kolaborasi.rooms');
    Route::post('/api/kolaborasi/create', [CollaborationController::class, 'createRoom'])->name('kolaborasi.create');
    Route::post('/api/kolaborasi/join', [CollaborationController::class, 'joinRoom'])->name('kolaborasi.join');
    Route::post('/api/kolaborasi/leave', [CollaborationController::class, 'leaveRoom'])->name('kolaborasi.leave');
    Route::get('/api/kolaborasi/room/{roomNumber}/data', [CollaborationController::class, 'getRoomData'])->name('kolaborasi.room.data');
    Route::post('/api/kolaborasi/room/{roomNumber}/save', [CollaborationController::class, 'saveRoomData'])->name('kolaborasi.room.save');
    Route::post('/api/kolaborasi/room/{roomNumber}/color', [CollaborationController::class, 'updateColorPart'])->name('kolaborasi.room.color');
    Route::post('/api/kolaborasi/room/{roomNumber}/reset-canvas', [CollaborationController::class, 'resetCanvasPart'])->name('kolaborasi.room.reset-canvas');
    Route::get('/api/kolaborasi/room/{roomNumber}/live-coloring', [CollaborationController::class, 'getLiveColoring'])->name('kolaborasi.room.live-coloring');
    Route::post('/api/kolaborasi/room/{roomNumber}/submit', [CollaborationController::class, 'submitRoom'])->name('kolaborasi.submit');

    // 4. Cek Hasil Kolaborasi (User)
    Route::get('/hasil', [CollaborationController::class, 'hasil'])->name('hasil');
    Route::get('/hasil/{id}', [CollaborationController::class, 'showHasil'])->name('hasil.show');
});

Route::get('/play', function () {
    if (Auth::check()) {
        return redirect()->route('menu');
    }

    return redirect()->route('login');
})->name('play');

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/petunjuk', function () {
        return view('petunjuk');
    })->name('petunjuk');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Kolaborasi Kelompok & Manajemen Sesi
    Route::get('/kolaborasi', [AdminCollaborationController::class, 'index'])->name('kolaborasi.index');
    Route::post('/kolaborasi/sessions', [AdminCollaborationController::class, 'storeSession'])->name('kolaborasi.sessions.store');
    Route::post('/kolaborasi/sessions/{id}/update', [AdminCollaborationController::class, 'updateSession'])->name('kolaborasi.sessions.update');
    Route::post('/kolaborasi/sessions/{id}/activate', [AdminCollaborationController::class, 'activateSession'])->name('kolaborasi.sessions.activate');
    Route::delete('/kolaborasi/sessions/{id}', [AdminCollaborationController::class, 'destroySession'])->name('kolaborasi.sessions.destroy');
    Route::get('/kolaborasi/{id}', [AdminCollaborationController::class, 'show'])->name('kolaborasi.show');
    Route::delete('/kolaborasi/{id}', [AdminCollaborationController::class, 'destroy'])->name('kolaborasi.destroy');

    // Master Bangun Datar
    Route::get('/bangun-datar', [AdminBangunDatarController::class, 'index'])->name('bangun-datar.index');
    Route::get('/bangun-datar/create', [AdminBangunDatarController::class, 'create'])->name('bangun-datar.create');
    Route::post('/bangun-datar', [AdminBangunDatarController::class, 'store'])->name('bangun-datar.store');
    Route::get('/bangun-datar/{id}/edit', [AdminBangunDatarController::class, 'edit'])->name('bangun-datar.edit');
    Route::post('/bangun-datar/{id}', [AdminBangunDatarController::class, 'update'])->name('bangun-datar.update');
    Route::post('/bangun-datar/{id}/delete', [AdminBangunDatarController::class, 'destroy'])->name('bangun-datar.destroy');

    // Data Siswa
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users/{id}/reset-progress', [AdminUserController::class, 'resetProgress'])->name('users.reset-progress');
    Route::post('/users/{id}/delete', [AdminUserController::class, 'destroy'])->name('users.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/page/{slug}', [PageController::class, 'show'])->name('page');
});
