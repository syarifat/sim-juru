<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Grup Route untuk pengguna yang sudah login & terverifikasi
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Halaman Dashboard Utama (Bisa diakses semua role yang login)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Khusus Role Admin
    Route::middleware(['role:Admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->names([
        'index' => 'users.index',
        'create' => 'users.create',
        'store' => 'users.store',
        'edit' => 'users.edit',
        'update' => 'users.update',
        'destroy' => 'users.destroy',
        ]);

        Route::resource('tahun-ajaran', \App\Http\Controllers\Admin\TahunAjaranController::class)->parameters(['tahun-ajaran' => 'tahunAjaran']);
        Route::get('/kelas-mapel', [\App\Http\Controllers\Admin\MasterAkademikController::class, 'index'])->name('kelas-mapel.index');
        Route::post('/kelas', [\App\Http\Controllers\Admin\MasterAkademikController::class, 'storeKelas'])->name('kelas.store');
        Route::put('/kelas/{kelas}', [\App\Http\Controllers\Admin\MasterAkademikController::class, 'updateKelas'])->name('kelas.update');
        Route::delete('/kelas/{kelas}', [\App\Http\Controllers\Admin\MasterAkademikController::class, 'destroyKelas'])->name('kelas.destroy');
        
        Route::post('/mapel', [\App\Http\Controllers\Admin\MasterAkademikController::class, 'storeMapel'])->name('mapel.store');
        Route::put('/mapel/{mapel}', [\App\Http\Controllers\Admin\MasterAkademikController::class, 'updateMapel'])->name('mapel.update');
        Route::delete('/mapel/{mapel}', [\App\Http\Controllers\Admin\MasterAkademikController::class, 'destroyMapel'])->name('mapel.destroy');
        Route::resource('jam-pelajaran', \App\Http\Controllers\Admin\MasterJamPelajaranController::class)->parameters(['jam-pelajaran' => 'jam_pelajaran']);
        Route::resource('jadwal', \App\Http\Controllers\Admin\JadwalController::class);

        // Guru Pengganti Routes
        Route::get('guru-pengganti', [\App\Http\Controllers\Admin\GuruPenggantiController::class, 'index'])->name('guru-pengganti.index');
        Route::get('guru-pengganti/create', [\App\Http\Controllers\Admin\GuruPenggantiController::class, 'create'])->name('guru-pengganti.create');
        Route::post('guru-pengganti', [\App\Http\Controllers\Admin\GuruPenggantiController::class, 'store'])->name('guru-pengganti.store');
        Route::delete('guru-pengganti/{guru_pengganti}', [\App\Http\Controllers\Admin\GuruPenggantiController::class, 'destroy'])->name('guru-pengganti.destroy');
    });

    // Khusus Role Guru
    Route::middleware(['role:Guru'])->prefix('guru')->name('guru.')->group(function () {
        Route::get('/jurnal', function () { return 'Menu Jadwal & Isi Jurnal Guru'; })->name('jurnal');
        Route::get('/riwayat', function () { return 'Menu Riwayat Jurnal'; })->name('riwayat');
    });

    // Khusus Role Kepala Sekolah
    Route::middleware(['role:Kepala_Sekolah'])->prefix('kepsek')->name('kepsek.')->group(function () {
        Route::get('/validasi', function () { return 'Menu Validasi Jurnal oleh Kepsek'; })->name('validasi');
        Route::get('/laporan', function () { return 'Menu Laporan Jurnal'; })->name('laporan');
    });

    // Profile route bawaan breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';