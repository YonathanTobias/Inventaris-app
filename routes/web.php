<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PeminjamanPublikController;
use App\Http\Controllers\PeminjamanRuanganController;
use App\Http\Controllers\PeminjamanRuanganPublikController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Halaman Utama Publik: Portal Peminjaman Aset & Ruangan Mandiri (Dosen & Mahasiswa)
Route::get('/', [PeminjamanPublikController::class, 'index'])->name('home');
Route::post('/pinjam', [PeminjamanPublikController::class, 'store'])->name('publik.store');
Route::get('/pinjam/lacak', [PeminjamanPublikController::class, 'lacak'])->name('publik.lacak');
Route::get('/pinjam/sukses/{kode}', [PeminjamanPublikController::class, 'sukses'])->name('publik.sukses');

// Publik: Booking Ruangan
Route::post('/booking-ruangan', [PeminjamanRuanganPublikController::class, 'store'])->name('publik.ruangan.store');
Route::get('/booking-ruangan/sukses/{kode}', [PeminjamanRuanganPublikController::class, 'sukses'])->name('publik.ruangan.sukses');
Route::get('/api/jadwal-ruangan', [PeminjamanRuanganPublikController::class, 'jadwal'])->name('publik.ruangan.jadwal');

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
    Route::get('ruangan/export', [RuanganController::class, 'exportExcel'])->name('ruangan.export');
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
    Route::resource('peminjaman', PeminjamanController::class)->only(['index', 'store', 'destroy']);
    Route::post('peminjaman/{id}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('peminjaman/{id}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::post('peminjaman/{id}/serahkan', [PeminjamanController::class, 'serahkan'])->name('peminjaman.serahkan');
    Route::post('peminjaman/{id}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');

    // Manajemen Peminjaman Ruangan (Approval Sarpras, Serah Kunci, & Selesai)
    Route::resource('peminjaman-ruangan', PeminjamanRuanganController::class)->only(['index', 'destroy']);
    Route::post('peminjaman-ruangan/{id}/approve', [PeminjamanRuanganController::class, 'approve'])->name('peminjaman-ruangan.approve');
    Route::post('peminjaman-ruangan/{id}/reject', [PeminjamanRuanganController::class, 'reject'])->name('peminjaman-ruangan.reject');
    Route::post('peminjaman-ruangan/{id}/serahkan', [PeminjamanRuanganController::class, 'serahkan'])->name('peminjaman-ruangan.serahkan');
    Route::post('peminjaman-ruangan/{id}/selesai', [PeminjamanRuanganController::class, 'selesai'])->name('peminjaman-ruangan.selesai');

    // Laporan & Export Excel
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/export', [LaporanController::class, 'exportExcel'])->name('laporan.export');
    Route::get('laporan/peminjaman-aset/export', [LaporanController::class, 'exportPeminjamanAset'])->name('laporan.peminjaman-aset.export');
    Route::get('laporan/peminjaman-ruangan/export', [LaporanController::class, 'exportPeminjamanRuangan'])->name('laporan.peminjaman-ruangan.export');
    Route::post('laporan/pengaturan-ttd', [LaporanController::class, 'updateTtd'])->name('laporan.ttd.update');

    // Manajemen Pengguna (Khusus Admin IT / Super User)
    Route::middleware('role:it')->group(function () {
        Route::resource('user', UserController::class)->except(['create', 'edit', 'show']);
    });
});