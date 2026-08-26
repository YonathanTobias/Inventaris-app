@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
            <span class="p-2 rounded-3 bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                <i class="fa-solid fa-boxes-stacked"></i>
            </span>
            <span>Master Data Aset & Inventaris</span>
        </h4>
        <p class="text-muted small mb-0 ms-md-5">Kelola, pantau, dan mutasikan seluruh data inventaris aset secara terstruktur.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('barang.label.massal', request()->query()) }}" target="_blank" class="btn btn-outline-dark fw-semibold d-inline-flex align-items-center gap-2 rounded-3 shadow-sm">
            <i class="fa-solid fa-barcode text-primary"></i>
            <span>Cetak Label Stiker (Massal)</span>
        </a>
        <button class="btn btn-modern-primary" data-bs-toggle="modal" data-bs-target="#modalTambahBarang">
            <i class="fa-solid fa-circle-plus"></i>
            <span>Tambah Aset Baru</span>
        </button>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card-stat">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Jenis Aset</div>
                    <div class="stat-value mt-1">{{ number_format($stats['total_jenis'] ?? $barangs->count()) }}</div>
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;"><i class="fa-solid fa-layer-group text-primary me-1"></i>Katalog Aset</div>
                </div>
                <div class="stat-icon primary">
                    <i class="fa-solid fa-box-open"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card-stat">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Total Unit</div>
                    <div class="stat-value mt-1">{{ number_format($stats['total_unit'] ?? $barangs->sum('jumlah')) }}</div>
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;"><i class="fa-solid fa-calculator text-info me-1"></i>Fisik Barang</div>
                </div>
                <div class="stat-icon info">
                    <i class="fa-solid fa-cubes-stacked"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card-stat">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Kondisi Baik</div>
                    <div class="stat-value mt-1 text-success">{{ number_format($stats['total_baik'] ?? $barangs->where('kondisi', 'Baik')->sum('jumlah')) }}</div>
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;"><i class="fa-solid fa-circle-check text-success me-1"></i>Siap Digunakan</div>
                </div>
                <div class="stat-icon success">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card-stat">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Rusak / Servis</div>
                    <div class="stat-value mt-1 text-warning">{{ number_format($stats['total_rusak'] ?? $barangs->where('kondisi', '!=', 'Baik')->sum('jumlah')) }}</div>
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;"><i class="fa-solid fa-triangle-exclamation text-warning me-1"></i>Perlu Tindakan</div>
                </div>
                <div class="stat-icon warning">
                    <i class="fa-solid fa-wrench"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Search Toolbar -->
<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('barang.index') }}" class="row g-2 align-items-center">
            <div class="col-lg-4 col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari Kode, Nama Aset, Tahun..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <select name="ruangan_id" class="form-select">
                    <option value="">-- Semua Ruangan --</option>
                    @foreach($ruangans as $r)
                        <option value="{{ $r->id }}" {{ request('ruangan_id') == $r->id ? 'selected' : '' }}>
                            {{ $r->nama_ruangan }} ({{ $r->kode_ruangan }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <select name="kategori_id" class="form-select">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <select name="kondisi" class="form-select">
                    <option value="">-- Kondisi --</option>
                    <option value="Baik" {{ request('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                    <option value="Rusak Ringan" {{ request('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="Rusak Berat" {{ request('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
            </div>
            <div class="col-lg-1 col-md-4 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100 fw-bold" data-bs-toggle="tooltip" title="Terapkan Filter">
                    <i class="fa-solid fa-filter"></i>
                </button>
                @if(request()->hasAny(['search', 'ruangan_id', 'kategori_id', 'kondisi']))
                    <a href="{{ route('barang.index') }}" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Reset Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Table Card -->
<div class="card">
    <div class="card-header-modern">
        <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-list-check text-primary"></i>
            <span>Daftar Aset Inventaris</span>
            <span class="badge bg-primary-subtle text-primary rounded-pill ms-2">{{ $barangs->count() }} Data</span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern align-middle">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 140px;">Kode Aset</th>
                        <th>Informasi Aset</th>
                        <th>Kategori</th>
                        <th>Lokasi Ruangan</th>
                        <th class="text-center" style="width: 100px;">Jumlah</th>
                        <th>Kondisi</th>
                        <th class="text-center pe-4" style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangs as $b)
                    <tr>
                        <td class="ps-4">
                            <span class="badge-code">{{ $b->kode_barang }}</span>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $b->nama_barang }}</div>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                @if($b->tahun_pengadaan)
                                    <span class="badge bg-light text-muted border px-2 py-0" style="font-size: 0.72rem;">
                                        <i class="fa-regular fa-calendar me-1"></i>{{ $b->tahun_pengadaan }}
                                    </span>
                                @endif
                                @if($b->keterangan)
                                    <span class="text-muted small text-truncate" style="max-width: 220px; font-size: 0.75rem;" title="{{ $b->keterangan }}">
                                        <i class="fa-regular fa-comment-dots me-1"></i>{{ $b->keterangan }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge-category">
                                <i class="fa-solid fa-tag me-1 text-secondary" style="font-size: 0.7rem;"></i>{{ $b->kategori->nama_kategori ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="p-1 rounded bg-light border text-primary d-inline-flex">
                                    <i class="fa-solid fa-door-open" style="font-size: 0.85rem;"></i>
                                </div>
                                <span class="fw-medium">{{ $b->ruangan->nama_ruangan ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-dark-subtle text-dark border px-2.5 py-1.5 fw-bold rounded-pill" style="font-size: 0.85rem;">
                                {{ number_format($b->jumlah) }} Unit
                            </span>
                        </td>
                        <td>
                            @if($b->kondisi == 'Baik')
                                <span class="badge-status badge-status-baik">Baik</span>
                            @elseif($b->kondisi == 'Rusak Ringan')
                                <span class="badge-status badge-status-ringan">Rusak Ringan</span>
                            @else
                                <span class="badge-status badge-status-berat">Rusak Berat</span>
                            @endif
                        </td>
                        <td class="text-center pe-4">
                            <div class="d-inline-flex gap-1">
                                <!-- Tombol Cetak Label 5x2.5cm -->
                                <a href="{{ route('barang.label', $b->id) }}" target="_blank" class="btn-icon btn-icon-dark border" data-bs-toggle="tooltip" title="Cetak Label Stiker (5 x 2.5 cm)">
                                    <i class="fa-solid fa-barcode text-dark"></i>
                                </a>

                                <!-- Tombol Edit -->
                                <button type="button" class="btn-icon btn-icon-primary" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $b->id }}" data-bs-toggle="tooltip" title="Edit Aset">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>

                                <!-- Tombol Pindah Ruangan -->
                                <button type="button" class="btn-icon btn-icon-info" data-bs-toggle="modal" data-bs-target="#modalPindah{{ $b->id }}" data-bs-toggle="tooltip" title="Mutasi / Pindah Ruangan">
                                    <i class="fa-solid fa-right-left"></i>
                                </button>

                                <!-- Tombol Kurangi Stok -->
                                <button type="button" class="btn-icon btn-icon-warning" data-bs-toggle="modal" data-bs-target="#modalKurang{{ $b->id }}" data-bs-toggle="tooltip" title="Kurangi / Catat Kerusakan">
                                    <i class="fa-solid fa-minus"></i>
                                </button>

                                <!-- Tombol Hapus -->
                                <form action="{{ route('barang.destroy', $b->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(event, this, 'aset {{ $b->nama_barang }} ({{ $b->kode_barang }})')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon btn-icon-danger" data-bs-toggle="tooltip" title="Hapus Aset">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>

                            <!-- MODAL EDIT BARANG -->
                            <div class="modal fade text-start" id="modalEdit{{ $b->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title fw-bold">
                                                <i class="fa-solid fa-pen-to-square me-2"></i>Edit Data Aset
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('barang.update', $b->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Kode Aset / Barcode <span class="text-danger">*</span></label>
                                                        <input type="text" name="kode_barang" class="form-control font-monospace" value="{{ $b->kode_barang }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Nama Barang / Aset <span class="text-danger">*</span></label>
                                                        <input type="text" name="nama_barang" class="form-control" value="{{ $b->nama_barang }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                                        <select name="kategori_id" class="form-select" required>
                                                            @foreach($kategoris as $k)
                                                                <option value="{{ $k->id }}" {{ $b->kategori_id == $k->id ? 'selected' : '' }}>
                                                                    {{ $k->nama_kategori }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Lokasi Ruangan <span class="text-danger">*</span></label>
                                                        <select name="ruangan_id" class="form-select" required>
                                                            @foreach($ruangans as $r)
                                                                <option value="{{ $r->id }}" {{ $b->ruangan_id == $r->id ? 'selected' : '' }}>
                                                                    {{ $r->nama_ruangan }} ({{ $r->kode_ruangan }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Jumlah / Unit <span class="text-danger">*</span></label>
                                                        <input type="number" name="jumlah" class="form-control" value="{{ $b->jumlah }}" min="0" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Kondisi <span class="text-danger">*</span></label>
                                                        <select name="kondisi" class="form-select" required>
                                                            <option value="Baik" {{ $b->kondisi == 'Baik' ? 'selected' : '' }}>Baik</option>
                                                            <option value="Rusak Ringan" {{ $b->kondisi == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                                            <option value="Rusak Berat" {{ $b->kondisi == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Tahun Pengadaan</label>
                                                        <input type="text" name="tahun_pengadaan" class="form-control" value="{{ $b->tahun_pengadaan }}" placeholder="Contoh: 2024">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label">Keterangan / Spesifikasi</label>
                                                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan tambahan spesifikasi barang...">{{ $b->keterangan }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary fw-bold">
                                                    <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- MODAL PINDAH RUANGAN -->
                            <div class="modal fade text-start" id="modalPindah{{ $b->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-info text-white">
                                            <h5 class="modal-title fw-bold">
                                                <i class="fa-solid fa-right-left me-2"></i>Mutasi / Pindah Ruangan
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('barang.pindah', $b->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="p-3 bg-light rounded-3 border mb-3">
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span class="text-muted small">Nama Aset:</span>
                                                        <strong class="text-dark">{{ $b->nama_barang }}</strong>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span class="text-muted small">Kode Aset:</span>
                                                        <span class="badge-code py-0">{{ $b->kode_barang }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span class="text-muted small">Lokasi Asal:</span>
                                                        <span class="badge bg-primary-subtle text-primary">{{ $b->ruangan->nama_ruangan }} (Stok: {{ $b->jumlah }})</span>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Pilih Ruangan Tujuan <span class="text-danger">*</span></label>
                                                    <select name="ruangan_tujuan_id" class="form-select" required>
                                                        <option value="">-- Pilih Tujuan --</option>
                                                        @foreach($ruangans->where('id', '!=', $b->ruangan_id) as $r)
                                                            <option value="{{ $r->id }}">{{ $r->nama_ruangan }} ({{ $r->kode_ruangan }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Jumlah Dipindahkan (Maks: {{ $b->jumlah }}) <span class="text-danger">*</span></label>
                                                    <input type="number" name="jumlah" class="form-control" max="{{ $b->jumlah }}" min="1" value="1" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Keterangan / Alasan Pemindahan</label>
                                                    <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Kebutuhan Praktikum Lab / Instruksi Pimpinan">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-info text-white fw-bold">
                                                    <i class="fa-solid fa-paper-plane me-1"></i> Proses Pemindahan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- MODAL KURANGI STOK -->
                            <div class="modal fade text-start" id="modalKurang{{ $b->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning text-dark">
                                            <h5 class="modal-title fw-bold">
                                                <i class="fa-solid fa-circle-minus me-2"></i>Pengurangan / Kerusakan Aset
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('barang.kurangi', $b->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="p-3 bg-light rounded-3 border mb-3">
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span class="text-muted small">Aset:</span>
                                                        <strong class="text-dark">{{ $b->nama_barang }} ({{ $b->kode_barang }})</strong>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span class="text-muted small">Stok Tersedia:</span>
                                                        <span class="badge bg-primary-subtle text-primary border px-2 py-1 fw-bold">{{ $b->jumlah }} Unit</span>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Jumlah Berkurang (Maks: {{ $b->jumlah }}) <span class="text-danger">*</span></label>
                                                    <input type="number" name="jumlah" class="form-control" max="{{ $b->jumlah }}" min="1" value="1" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Alasan Pengurangan <span class="text-danger">*</span></label>
                                                    <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Rusak Total / Hilang / Afkir / Dihibahkan" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-warning fw-bold">
                                                    <i class="fa-solid fa-check me-1"></i> Simpan Pengurangan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted mb-2">
                                <i class="fa-solid fa-box-open fa-3x opacity-50"></i>
                            </div>
                            <h6 class="fw-bold text-secondary mb-1">Belum Ada Data Aset</h6>
                            <p class="text-muted small mb-3">Data aset yang Anda cari atau tambahkan akan muncul di sini.</p>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahBarang">
                                <i class="fa-solid fa-plus me-1"></i> Tambah Aset Pertama
                            </button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH BARANG BARU -->
<div class="modal fade" id="modalTambahBarang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-circle-plus text-primary me-2"></i>Tambah Aset Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('barang.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Kode Aset / Barcode <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-barcode"></i></span>
                                <input type="text" name="kode_barang" class="form-control font-monospace" placeholder="Misal: AST-LAB-001" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Barang / Aset <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-box"></i></span>
                                <input type="text" name="nama_barang" class="form-control" placeholder="Misal: Laptop Dell Vostro 3400" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoris as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lokasi / Ruangan <span class="text-danger">*</span></label>
                            <select name="ruangan_id" class="form-select" required>
                                <option value="">-- Pilih Ruangan --</option>
                                @foreach($ruangans as $r)
                                    <option value="{{ $r->id }}">{{ $r->nama_ruangan }} ({{ $r->kode_ruangan }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jumlah Awal <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah" class="form-control" value="1" min="1" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kondisi Fisik <span class="text-danger">*</span></label>
                            <select name="kondisi" class="form-select" required>
                                <option value="Baik" selected>Baik</option>
                                <option value="Rusak Ringan">Rusak Ringan</option>
                                <option value="Rusak Berat">Rusak Berat</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tahun Pengadaan</label>
                            <input type="text" name="tahun_pengadaan" class="form-control" placeholder="Contoh: 2024" value="{{ date('Y') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keterangan / Spesifikasi (Opsional)</label>
                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan spesifikasi teknis atau nomor seri tambahan..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-modern-primary">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Aset Baru</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection