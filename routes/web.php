<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Grup Route untuk pengguna yang sudah login & terverifikasi
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Halaman Dashboard Utama (Bisa diakses semua role yang login)
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

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

    // Laporan Jurnal - bisa diakses Admin dan Kepala Sekolah
    Route::middleware(['role:Admin,Kepala_Sekolah'])->group(function () {
        Route::get('admin/laporan-jurnal', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('admin.laporan.index');
    });

    // Khusus Role Guru
    Route::middleware(['role:Guru'])->prefix('guru')->name('guru.')->group(function () {
        // Jurnal Routes
        Route::get('jurnal', [\App\Http\Controllers\Guru\JurnalController::class, 'index'])->name('jurnal.index');
        Route::get('jurnal/create/{jadwal}', [\App\Http\Controllers\Guru\JurnalController::class, 'create'])->name('jurnal.create');
        Route::post('jurnal', [\App\Http\Controllers\Guru\JurnalController::class, 'store'])->name('jurnal.store');
        Route::get('jurnal/{jurnal}/edit', [\App\Http\Controllers\Guru\JurnalController::class, 'edit'])->name('jurnal.edit');
        Route::put('jurnal/{jurnal}', [\App\Http\Controllers\Guru\JurnalController::class, 'update'])->name('jurnal.update');
        Route::get('riwayat-jurnal', [\App\Http\Controllers\Guru\JurnalController::class, 'riwayat'])->name('jurnal.riwayat');
    });

    // Khusus Role Kepala Sekolah
    Route::middleware(['role:Kepala_Sekolah'])->prefix('kepsek')->name('kepsek.')->group(function () {
        // Validasi Jurnal Routes
        Route::get('validasi-jurnal', [\App\Http\Controllers\Kepsek\ValidasiJurnalController::class, 'index'])->name('validasi.index');
        Route::get('validasi-jurnal/{jurnal}/edit', [\App\Http\Controllers\Kepsek\ValidasiJurnalController::class, 'edit'])->name('validasi.edit');
        Route::put('validasi-jurnal/{jurnal}', [\App\Http\Controllers\Kepsek\ValidasiJurnalController::class, 'update'])->name('validasi.update');
        // Kepsek akses laporan jurnal pakai route yang sama dengan admin
    });

    // Profile route bawaan breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';