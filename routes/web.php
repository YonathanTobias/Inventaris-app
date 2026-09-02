<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Halaman Utama Publik: Portal Peminjaman Aset Mandiri (Dosen & Mahasiswa)
Route::get('/', [\App\Http\Controllers\PeminjamanPublikController::class, 'index'])->name('home');
Route::post('/pinjam', [\App\Http\Controllers\PeminjamanPublikController::class, 'store'])->name('publik.store');
Route::get('/pinjam/lacak', [\App\Http\Controllers\PeminjamanPublikController::class, 'lacak'])->name('publik.lacak');
Route::get('/pinjam/sukses/{kode}', [\App\Http\Controllers\PeminjamanPublikController::class, 'sukses'])->name('publik.sukses');

// Halaman Publik: Verifikasi Scan QR Code Aset (Bisa diakses tanpa login)
Route::get('/cek/{kode_barang}', [BarangController::class, 'cekAsetPublik'])->name('aset.cek');

// Guest Routes (Autentikasi)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1')->name('login.process');
});

// Authenticated Routes (Harus Login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Master Data Ruangan & Kategori
    Route::get('ruangan/{id}/label', [BarangController::class, 'cetakLabelRuangan'])->name('ruangan.label');
    Route::resource('ruangan', RuanganController::class)->except(['create', 'edit', 'show']);
    Route::resource('kategori', KategoriController::class)->except(['create', 'edit', 'show']);

    // Master Data Barang Aset & Mutasi
    Route::get('barang/label/massal', [BarangController::class, 'cetakLabelMassal'])->name('barang.label.massal');
    Route::get('barang/{id}/label', [BarangController::class, 'cetakLabel'])->name('barang.label');
    Route::resource('barang', BarangController::class)->except(['create', 'edit', 'show']);
    Route::post('barang/pindah', [BarangController::class, 'pindahRuangan'])->name('barang.pindah.global');
    Route::post('barang/{id}/pindah', [BarangController::class, 'pindahRuangan'])->name('barang.pindah');
    Route::post('barang/{id}/kurangi', [BarangController::class, 'kurangiStok'])->name('barang.kurangi');

    // Manajemen Peminjaman Aset (Approval Sarpras, Serah Terima, & Pengembalian)
    Route::resource('peminjaman', \App\Http\Controllers\PeminjamanController::class)->only(['index', 'store', 'destroy']);
    Route::post('peminjaman/{id}/approve', [\App\Http\Controllers\PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('peminjaman/{id}/reject', [\App\Http\Controllers\PeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::post('peminjaman/{id}/serahkan', [\App\Http\Controllers\PeminjamanController::class, 'serahkan'])->name('peminjaman.serahkan');
    Route::post('peminjaman/{id}/kembalikan', [\App\Http\Controllers\PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');

    // Laporan & Export Excel
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/export', [LaporanController::class, 'exportExcel'])->name('laporan.export');

    // Manajemen Pengguna (Khusus Admin IT / Super User)
    Route::middleware('role:it')->group(function () {
        Route::resource('user', UserController::class)->except(['create', 'edit', 'show']);
    });
});