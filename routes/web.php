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

        Route::get('/tahun-ajaran', function () { return 'Menu Kelola Tahun Ajaran'; })->name('tahun-ajaran');
        Route::get('/kelas-mapel', function () { return 'Menu Kelola Kelas & Mapel'; })->name('kelas-mapel');
        Route::get('/jadwal', function () { return 'Menu Kelola Jadwal'; })->name('jadwal');
        Route::get('/guru-pengganti', function () { return 'Menu Kelola Guru Pengganti'; })->name('guru-pengganti');
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