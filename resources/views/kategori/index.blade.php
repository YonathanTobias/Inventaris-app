@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
            <span class="p-2 rounded-3 bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                <i class="fa-solid fa-tags"></i>
            </span>
            <span>Master Data Kategori Barang</span>
        </h4>
        <p class="text-muted small mb-0 ms-md-5">Kelola klasifikasi dan pengelompokan jenis aset dalam sistem inventaris.</p>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="card-stat">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Total Kategori</div>
                    <div class="stat-value mt-1">{{ number_format($stats['total_kategori'] ?? $kategoris->count()) }}</div>
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;"><i class="fa-solid fa-layer-group text-primary me-1"></i>Klasifikasi Aktif</div>
                </div>
                <div class="stat-icon primary">
                    <i class="fa-solid fa-tags"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card-stat">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Barang Terklasifikasi</div>
                    <div class="stat-value mt-1 text-info">{{ number_format($stats['total_barang'] ?? 0) }}</div>
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;"><i class="fa-solid fa-boxes-stacked text-info me-1"></i>Aset Terhubung</div>
                </div>
                <div class="stat-icon info">
                    <i class="fa-solid fa-box-open"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card-stat">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Kategori Terpopuler</div>
                    <div class="stat-value mt-1 text-truncate" style="font-size: 1.25rem; max-width: 180px;" title="{{ $stats['kategori_terbanyak']->nama_kategori ?? '-' }}">
                        {{ $stats['kategori_terbanyak']->nama_kategori ?? '-' }}
                    </div>
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;">
                        <i class="fa-solid fa-fire text-danger me-1"></i>{{ $stats['kategori_terbanyak']->barangs_count ?? 0 }} Barang Terdaftar
                    </div>
                </div>
                <div class="stat-icon danger">
                    <i class="fa-solid fa-crown"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Form Tambah Kategori -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header-modern">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-plus text-primary"></i>
                    <span>Tambah Kategori Baru</span>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('kategori.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-tag"></i></span>
                            <input type="text" name="nama_kategori" class="form-control" placeholder="Misal: Elektronik, Mebel, Kendaraan" required>
                        </div>
                        <div class="form-text small text-muted">Gunakan nama kategori yang umum dan mudah dipahami.</div>
                    </div>
                    <button type="submit" class="btn btn-modern-primary w-100 justify-content-center">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Kategori</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Kategori -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header-modern">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-tags text-primary"></i>
                    <span>Daftar Kategori Terdaftar</span>
                    <span class="badge bg-primary-subtle text-primary rounded-pill ms-2">{{ $kategoris->count() }} Kategori</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern align-middle">
                        <thead>
                            <tr>
                                <th class="ps-4" style="width: 70px;">No</th>
                                <th>Nama Kategori</th>
                                <th class="text-center" style="width: 180px;">Jumlah Barang Terkait</th>
                                <th class="text-center pe-4" style="width: 110px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategoris as $index => $k)
                            <tr>
                                <td class="ps-4 text-muted fw-semibold">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="p-1.5 rounded-2 bg-light border text-primary">
                                            <i class="fa-solid fa-tag" style="font-size: 0.85rem;"></i>
                                        </div>
                                        <span class="fw-bold text-dark">{{ $k->nama_kategori }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info-subtle text-info-emphasis border px-3 py-1.5 fw-bold rounded-pill" style="font-size: 0.8rem;">
                                        <i class="fa-solid fa-box me-1"></i>{{ $k->barangs_count }} Item
                                    </span>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="d-inline-flex gap-1">
                                        <!-- Tombol Edit -->
                                        <button type="button" class="btn-icon btn-icon-primary" data-bs-toggle="modal" data-bs-target="#modalEditKategori{{ $k->id }}" data-bs-toggle="tooltip" title="Edit Kategori">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('kategori.destroy', $k->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(event, this, 'kategori {{ $k->nama_kategori }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon btn-icon-danger" data-bs-toggle="tooltip" title="Hapus Kategori">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- MODAL EDIT KATEGORI -->
                                    <div class="modal fade text-start" id="modalEditKategori{{ $k->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title fw-bold">
                                                        <i class="fa-solid fa-pen-to-square me-2"></i>Edit Kategori
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('kategori.update', $k->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                                                            <input type="text" name="nama_kategori" class="form-control" value="{{ $k->nama_kategori }}" required>
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
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="text-muted mb-2">
                                        <i class="fa-solid fa-tags fa-3x opacity-50"></i>
                                    </div>
                                    <h6 class="fw-bold text-secondary mb-1">Belum Ada Kategori</h6>
                                    <p class="text-muted small mb-0">Tambahkan kategori pertama Anda melalui form di samping.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection