<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminCollaborationController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminSpendingItemController;
use App\Http\Controllers\Admin\AdminSubmissionController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BisniskuController;
use App\Http\Controllers\CollaborationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
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
        return view('eksplorasi.bangun-datar');
    })->name('eksplorasi.bangun-datar');
    Route::get('/bangun-datar', function () {
        return redirect()->route('eksplorasi.bangun-datar');
    })->name('bangun-datar');

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
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


Route::middleware('auth')->group(function () {
    // Menu Utama Bisnisku
    Route::get('/bisnisku', [BisniskuController::class, 'index'])->name('bisnisku');
    
    // Sub-menu 1: Ide Bisnisku
    Route::get('/bisnisku/ide-bisnis', [BisniskuController::class, 'ideBisnis'])->name('bisnisku.ide-bisnis');
    Route::post('/bisnisku/ide-bisnis', [BisniskuController::class, 'submitIdeBisnis'])->name('bisnisku.ide-bisnis.submit');

    // Sub-menu 2: Rencana Keuangan
    Route::get('/bisnisku/rencana-keuangan', [BisniskuController::class, 'rencanaKeuangan'])->name('bisnisku.rencana-keuangan');
    Route::post('/bisnisku/rencana-keuangan/game', [BisniskuController::class, 'checkGameBelanja'])->name('bisnisku.rencana-keuangan.game');
    Route::post('/bisnisku/rencana-keuangan/hitung-modal', [BisniskuController::class, 'submitHitungModal'])->name('bisnisku.rencana-keuangan.hitung-modal');

    // Sub-menu 3: Pengembangan Bisnis
    Route::get('/bisnisku/pengembangan-bisnis', [BisniskuController::class, 'pengembanganBisnis'])->name('bisnisku.pengembangan-bisnis');
    Route::post('/bisnisku/pengembangan-bisnis/hitung-total', [BisniskuController::class, 'submitHitungTotalUsaha'])->name('bisnisku.pengembangan-bisnis.hitung-total');
    Route::post('/bisnisku/pengembangan-bisnis/tabungan', [BisniskuController::class, 'submitStudiKasusTabungan'])->name('bisnisku.pengembangan-bisnis.tabungan');
    Route::post('/bisnisku/pengembangan-bisnis/investasi', [BisniskuController::class, 'submitStudiKasusInvestasi'])->name('bisnisku.pengembangan-bisnis.investasi');

    Route::get('/petunjuk', function () {
        return view('petunjuk');
    })->name('petunjuk');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Kolaborasi Kelompok (Data per Kelompok yang Dikumpulkan)
    Route::get('/kolaborasi', [AdminCollaborationController::class, 'index'])->name('kolaborasi.index');
    Route::get('/kolaborasi/{id}', [AdminCollaborationController::class, 'show'])->name('kolaborasi.show');
    Route::delete('/kolaborasi/{id}', [AdminCollaborationController::class, 'destroy'])->name('kolaborasi.destroy');

    // Submissions & Progres Siswa
    Route::get('/submissions', [AdminSubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/export-excel', [AdminSubmissionController::class, 'exportExcel'])->name('submissions.export-excel');
    Route::post('/submissions/{id}/delete', [AdminSubmissionController::class, 'destroy'])->name('submissions.destroy');

    // Kelola Barang Belanja Game
    Route::get('/spending-items', [AdminSpendingItemController::class, 'index'])->name('spending-items.index');
    Route::get('/spending-items/create', [AdminSpendingItemController::class, 'create'])->name('spending-items.create');
    Route::post('/spending-items', [AdminSpendingItemController::class, 'store'])->name('spending-items.store');
    Route::get('/spending-items/{id}/edit', [AdminSpendingItemController::class, 'edit'])->name('spending-items.edit');
    Route::post('/spending-items/{id}', [AdminSpendingItemController::class, 'update'])->name('spending-items.update');
    Route::post('/spending-items/{id}/delete', [AdminSpendingItemController::class, 'destroy'])->name('spending-items.destroy');

    // Data Siswa
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users/{id}/reset-progress', [AdminUserController::class, 'resetProgress'])->name('users.reset-progress');
    Route::post('/users/{id}/delete', [AdminUserController::class, 'destroy'])->name('users.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/page/{slug}', [PageController::class, 'show'])->name('page');
});
