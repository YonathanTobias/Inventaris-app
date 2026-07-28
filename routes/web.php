<?php

use App\Http\Controllers\RuanganController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\LaporanController;

Route::get('/', function () {
    return redirect()->route('barang.index');
});

// Master Data
Route::resource('ruangan', RuanganController::class)->except(['create', 'edit', 'show']);
Route::resource('kategori', KategoriController::class)->except(['create', 'edit', 'show']);

// Master Data Barang Aset & Mutasi
Route::resource('barang', BarangController::class)->except(['create', 'edit', 'show']);
Route::post('barang/{id}/pindah', [BarangController::class, 'pindahRuangan'])->name('barang.pindah');
Route::post('barang/{id}/kurangi', [BarangController::class, 'kurangiStok'])->name('barang.kurangi');

// Laporan & Export Excel
Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
Route::get('laporan/export', [LaporanController::class, 'exportExcel'])->name('laporan.export');