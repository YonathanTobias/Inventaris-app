@extends('layouts.app')

@section('title', 'Manajemen Peminjaman Aset')

@section('content')
<div class="row g-3 mb-4 align-items-center">
    <div class="col-md-7">
        <h3 class="fw-bold text-dark mb-1" style="letter-spacing: -0.02em;">
            <i class="fa-solid fa-hand-holding-hand text-primary me-2"></i>Manajemen Peminjaman Aset
        </h3>
        <p class="text-muted small mb-0">Kelola persetujuan (approval) Kepala Sarpras, serah terima barang fisik, dan pengembalian aset.</p>
    </div>
    <div class="col-md-5 text-md-end d-flex gap-2 justify-content-md-end">
        <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Buka Portal Publik
        </a>
        <button class="btn btn-modern-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPeminjamanInternal">
            <i class="fa-solid fa-plus-circle me-1"></i>
            <span>Catat Peminjaman Internal</span>
        </button>
    </div>
</div>

<!-- KARTU STATISTIK TAHAPAN PEMINJAMAN -->
<div class="row g-3 mb-4">
    <div class="col-xl-2 col-md-4 col-sm-6">
        <a href="{{ route('peminjaman.index') }}" class="text-decoration-none">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body p-3 text-center">
                    <div class="text-muted small fw-semibold mb-1">Semua Pengajuan</div>
                    <div class="fs-4 fw-bold text-dark">{{ number_format($stats['total']) }}</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <a href="{{ route('peminjaman.index', ['status' => 'Menunggu']) }}" class="text-decoration-none">
            <div class="card stat-card border-0 shadow-sm h-100 {{ request('status') === 'Menunggu' ? 'border-warning border-2' : '' }}">
                <div class="card-body p-3 text-center">
                    <div class="text-warning small fw-bold mb-1"><i class="fa-solid fa-clock me-1"></i>Menunggu Approval</div>
                    <div class="fs-4 fw-bold text-warning">{{ number_format($stats['menunggu']) }}</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <a href="{{ route('peminjaman.index', ['status' => 'Disetujui']) }}" class="text-decoration-none">
            <div class="card stat-card border-0 shadow-sm h-100 {{ request('status') === 'Disetujui' ? 'border-primary border-2' : '' }}">
                <div class="card-body p-3 text-center">
                    <div class="text-primary small fw-bold mb-1"><i class="fa-solid fa-circle-check me-1"></i>Siap Diambil</div>
                    <div class="fs-4 fw-bold text-primary">{{ number_format($stats['disetujui']) }}</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <a href="{{ route('peminjaman.index', ['status' => 'Diambil']) }}" class="text-decoration-none">
            <div class="card stat-card border-0 shadow-sm h-100 {{ request('status') === 'Diambil' ? 'border-info border-2' : '' }}">
                <div class="card-body p-3 text-center">
                    <div class="text-info small fw-bold mb-1"><i class="fa-solid fa-box-open me-1"></i>Sudah Diambil</div>
                    <div class="fs-4 fw-bold text-info">{{ number_format($stats['diambil']) }}</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <a href="{{ route('peminjaman.index', ['status' => 'Kembali']) }}" class="text-decoration-none">
            <div class="card stat-card border-0 shadow-sm h-100 {{ request('status') === 'Kembali' ? 'border-success border-2' : '' }}">
                <div class="card-body p-3 text-center">
                    <div class="text-success small fw-bold mb-1"><i class="fa-solid fa-check-double me-1"></i>Sudah Kembali</div>
                    <div class="fs-4 fw-bold text-success">{{ number_format($stats['kembali']) }}</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <a href="{{ route('peminjaman.index', ['status' => 'Ditolak']) }}" class="text-decoration-none">
            <div class="card stat-card border-0 shadow-sm h-100 {{ request('status') === 'Ditolak' ? 'border-danger border-2' : '' }}">
                <div class="card-body p-3 text-center">
                    <div class="text-danger small fw-bold mb-1"><i class="fa-solid fa-ban me-1"></i>Ditolak</div>
                    <div class="fs-4 fw-bold text-danger">{{ number_format($stats['ditolak']) }}</div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- FILTER STATUS & PENCARIAN -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('peminjaman.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama peminjam, NIM/NIP, prodi, kode, atau nama barang..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Tahap Status --</option>
                    <option value="Menunggu" {{ request('status') === 'Menunggu' ? 'selected' : '' }}>1. Menunggu Persetujuan Kepala Sarpras</option>
                    <option value="Disetujui" {{ request('status') === 'Disetujui' ? 'selected' : '' }}>2. Disetujui (Siap Diambil)</option>
                    <option value="Diambil" {{ request('status') === 'Diambil' ? 'selected' : '' }}>3. Barang Sudah Diambil (Sedang Dipinjam)</option>
                    <option value="Kembali" {{ request('status') === 'Kembali' ? 'selected' : '' }}>4. Selesai (Sudah Dikembalikan)</option>
                    <option value="Terlambat" {{ request('status') === 'Terlambat' ? 'selected' : '' }}>Terlambat (Lewat Tenggat)</option>
                    <option value="Ditolak" {{ request('status') === 'Ditolak' ? 'selected' : '' }}>Ditolak oleh Kepala Sarpras</option>
                </select>
            </div>
            <div class="col-md-3 text-md-end d-flex gap-2 justify-content-md-end">
                <button type="submit" class="btn btn-sm btn-primary px-3">
                    <i class="fa-solid fa-filter me-1"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- TABEL DAFTAR TRANSAKSI PEMINJAMAN -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0 text-dark">
            <i class="fa-solid fa-list-check text-primary me-2"></i>Daftar Pengajuan & Peminjaman Aset
        </h6>
        <span class="badge bg-light text-secondary border font-monospace">{{ $peminjamans->count() }} Data</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-muted small text-uppercase">
                <tr>
                    <th class="ps-4" style="width: 40px;">No</th>
                    <th>Peminjam & Identitas</th>
                    <th>Aset Dipinjam</th>
                    <th class="text-center">Jumlah</th>
                    <th>Tenggat Kembali</th>
                    <th>Status Peminjaman</th>
                    <th class="text-center pe-4" style="width: 220px;">Tindakan & Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjamans as $index => $p)
                <tr>
                    <td class="ps-4 fw-semibold text-muted">{{ $index + 1 }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-1.5">
                            <span class="badge bg-secondary-subtle text-secondary border px-2 py-0.5" style="font-size: 0.7rem;">{{ $p->kategori_peminjam ?? 'Mahasiswa' }}</span>
                            <span class="fw-bold text-dark">{{ $p->nama_peminjam }}</span>
                        </div>
                        <div class="text-muted small font-monospace mt-0.5">
                            <i class="fa-solid fa-id-card me-1 text-primary"></i>{{ $p->nomor_identitas }}
                            @if($p->prodi_unit)
                                &bull; <span class="text-secondary">{{ $p->prodi_unit }}</span>
                            @endif
                        </div>
                        @if($p->kontak_peminjam)
                            <div class="text-muted small" style="font-size: 0.75rem;">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $p->kontak_peminjam) }}" target="_blank" class="text-success text-decoration-none">
                                    <i class="fa-brands fa-whatsapp me-1"></i>{{ $p->kontak_peminjam }}
                                </a>
                            </div>
                        @endif
                        <div class="mt-1">
                            <span class="badge bg-light text-dark border font-monospace" style="font-size: 0.68rem;">{{ $p->kode_peminjaman }}</span>
                        </div>
                    </td>
                    <td>
                        @if($p->details && $p->details->count() > 1)
                            <div class="fw-bold text-dark">{{ $p->details->first()->barang->nama_barang ?? '-' }}</div>
                            <button type="button" class="btn btn-link btn-sm text-decoration-none p-0 fw-bold text-primary" data-bs-toggle="modal" data-bs-target="#modalRincianAset{{ $p->id }}" style="font-size: 0.75rem;">
                                <i class="fa-solid fa-cart-shopping me-1"></i>+ {{ $p->details->count() - 1 }} Aset Lainnya (Lihat Paket)
                            </button>
                        @elseif($p->details && $p->details->count() === 1)
                            <div class="fw-bold text-dark">{{ $p->details->first()->barang->nama_barang ?? '-' }}</div>
                            <div class="d-flex align-items-center gap-1 mt-0.5">
                                <span class="badge-code py-0">{{ $p->details->first()->barang->kode_barang ?? '-' }}</span>
                                <span class="text-muted small">({{ $p->details->first()->barang->ruangan->nama_ruangan ?? '-' }})</span>
                            </div>
                        @else
                            <div class="fw-bold text-dark">{{ $p->barang->nama_barang ?? 'Aset Telah Dihapus' }}</div>
                            <div class="d-flex align-items-center gap-1 mt-0.5">
                                <span class="badge-code py-0">{{ $p->barang->kode_barang ?? '-' }}</span>
                                <span class="text-muted small">({{ $p->barang->ruangan->nama_ruangan ?? '-' }})</span>
                            </div>
                        @endif
                        @if($p->keperluan)
                            <div class="text-muted small text-truncate mt-1" style="max-width: 250px; font-size: 0.72rem;" title="{{ $p->keperluan }}">
                                <i class="fa-solid fa-circle-info me-1"></i>{{ $p->keperluan }}
                            </div>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fw-bold rounded-pill">
                            {{ $p->jumlah }} Unit
                        </span>
                    </td>
                    <td>
                        <div class="small">
                            <span class="text-muted">Pinjam:</span> {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->isoFormat('D MMM Y') }}
                        </div>
                        <div class="small">
                            <span class="text-muted">Tenggat:</span> 
                            <strong class="{{ in_array($p->status, ['Diambil', 'Terlambat']) && $p->tenggat_kembali < now()->toDateString() ? 'text-danger' : 'text-dark' }}">
                                {{ \Carbon\Carbon::parse($p->tenggat_kembali)->isoFormat('D MMM Y') }}
                            </strong>
                        </div>
                        @if($p->tanggal_diambil)
                            <div class="small text-info" style="font-size: 0.72rem;">
                                <i class="fa-solid fa-box-open me-1"></i>Diambil: {{ $p->tanggal_diambil->isoFormat('D MMM, HH:mm') }}
                            </div>
                        @endif
                        @if($p->tanggal_kembali)
                            <div class="small text-success" style="font-size: 0.72rem;">
                                <i class="fa-solid fa-check-double me-1"></i>Kembali: {{ \Carbon\Carbon::parse($p->tanggal_kembali)->isoFormat('D MMM Y') }}
                            </div>
                        @endif
                    </td>
                    <td>
                        @if($p->status === 'Menunggu')
                            <span class="badge bg-warning text-dark border border-warning px-2.5 py-1 rounded-pill">
                                <i class="fa-solid fa-clock me-1"></i>Menunggu Persetujuan
                            </span>
                        @elseif($p->status === 'Disetujui')
                            <span class="badge bg-primary text-white border border-primary px-2.5 py-1 rounded-pill">
                                <i class="fa-solid fa-circle-check me-1"></i>Disetujui (Siap Diambil)
                            </span>
                        @elseif($p->status === 'Diambil')
                            <span class="badge bg-info text-dark border border-info px-2.5 py-1 rounded-pill">
                                <i class="fa-solid fa-box-open me-1"></i>Barang Sudah Diambil
                            </span>
                        @elseif($p->status === 'Kembali')
                            <span class="badge bg-success-subtle text-success border border-success px-2.5 py-1 rounded-pill">
                                <i class="fa-solid fa-check-double me-1"></i>Sudah Dikembalikan
                            </span>
                            @if($p->kondisi_kembali)
                                <div class="small text-muted mt-0.5" style="font-size: 0.72rem;">Kondisi: <strong>{{ $p->kondisi_kembali }}</strong></div>
                            @endif
                        @elseif($p->status === 'Ditolak')
                            <span class="badge bg-danger text-white border border-danger px-2.5 py-1 rounded-pill">
                                <i class="fa-solid fa-ban me-1"></i>Ditolak
                            </span>
                            @if($p->alasan_penolakan)
                                <div class="small text-danger mt-0.5" style="font-size: 0.72rem;">Alasan: {{ $p->alasan_penolakan }}</div>
                            @endif
                        @else
                            <span class="badge bg-danger text-white border border-danger px-2.5 py-1 rounded-pill">
                                <i class="fa-solid fa-triangle-exclamation me-1"></i>Terlambat
                            </span>
                        @endif
                    </td>
                    <td class="text-center pe-4">
                        <div class="d-inline-flex flex-wrap gap-1 justify-content-center">
                            <!-- AKSI TAHAP 1: APPROVAL KEPALA SARPRAS -->
                            @if($p->status === 'Menunggu')
                                <!-- Tombol Setujui -->
                                <form action="{{ route('peminjaman.approve', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin MENYETUJUI peminjaman aset ini sebagai Kepala Sarpras?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary fw-bold" title="Setujui Peminjaman">
                                        <i class="fa-solid fa-check me-1"></i>Setujui
                                    </button>
                                </form>

                                <!-- Tombol Tolak (Buka Modal) -->
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalTolak{{ $p->id }}" title="Tolak Pengajuan">
                                    <i class="fa-solid fa-xmark"></i> Tolak
                                </button>

                            <!-- AKSI TAHAP 2: SERAH TERIMA BARANG (BARANG SUDAH DIAMBIL) -->
                            @elseif($p->status === 'Disetujui')
                                <form action="{{ route('peminjaman.serahkan', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Konfirmasi bahwa barang fisik telah diserahkan dan diambil oleh peminjam? Stok aset akan resmi dipotong.')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-info text-white fw-bold shadow-sm" title="Serahkan Barang ke Pemohon">
                                        <i class="fa-solid fa-box-open me-1"></i>Serahkan Barang
                                    </button>
                                </form>

                            <!-- AKSI TAHAP 3: PROSES PENGEMBALIAN BARANG -->
                            @elseif(in_array($p->status, ['Diambil', 'Terlambat']))
                                <button type="button" class="btn btn-sm btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#modalKembali{{ $p->id }}" title="Proses Pengembalian Barang">
                                    <i class="fa-solid fa-rotate-left me-1"></i>Kembalikan
                                </button>
                            @endif

                            <!-- Tombol Tiket Bukti Publik -->
                            <a href="{{ route('publik.sukses', $p->kode_peminjaman) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Lihat E-Ticket Digital">
                                <i class="fa-solid fa-receipt"></i>
                            </a>

                            <!-- Tombol Hapus Transaksi -->
                            <form action="{{ route('peminjaman.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(event, this, 'transaksi peminjaman {{ $p->kode_peminjaman }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Data">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>

                        <!-- MODAL TOLAK PENGAJUAN -->
                        @if($p->status === 'Menunggu')
                        <div class="modal fade text-start" id="modalTolak{{ $p->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title fw-bold">
                                            <i class="fa-solid fa-ban me-2"></i>Tolak Permohonan Peminjaman
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('peminjaman.reject', $p->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body p-4">
                                            <p class="small text-muted mb-3">
                                                Anda akan menolak pengajuan <strong>{{ $p->kode_peminjaman }}</strong> atas nama <strong>{{ $p->nama_peminjam }}</strong> untuk aset <strong>{{ $p->barang->nama_barang ?? '-' }}</strong>.
                                            </p>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Alasan Penolakan <span class="text-danger">*</span></label>
                                                <textarea name="alasan_penolakan" class="form-control" rows="3" placeholder="Misal: Alat sedang dalam persiapan simulasi akreditasi / Jadwal bentrok dengan ujian OSCE..." required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger fw-bold">
                                                <i class="fa-solid fa-ban me-1"></i> Konfirmasi Tolak
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- MODAL PROSES PENGEMBALIAN -->
                        @if(in_array($p->status, ['Diambil', 'Terlambat']))
                        <div class="modal fade text-start" id="modalKembali{{ $p->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title fw-bold">
                                            <i class="fa-solid fa-rotate-left me-2"></i>Pengembalian Aset Fisik
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('peminjaman.kembalikan', $p->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body p-4">
                                            <div class="p-3 bg-light rounded-3 border mb-3">
                                                <div class="fw-bold text-dark">{{ $p->barang->nama_barang ?? '-' }}</div>
                                                <div class="text-muted small mt-1">
                                                    Peminjam: <strong>{{ $p->nama_peminjam }}</strong> ({{ $p->nomor_identitas }})
                                                </div>
                                                <div class="text-muted small">
                                                    Jumlah: <strong>{{ $p->jumlah }} Unit</strong> | Ruangan Asal: <strong>{{ $p->barang->ruangan->nama_ruangan ?? '-' }}</strong>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Tanggal Pengembalian <span class="text-danger">*</span></label>
                                                <input type="date" name="tanggal_kembali" class="form-control" value="{{ date('Y-m-d') }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Kondisi Fisik Saat Kembali <span class="text-danger">*</span></label>
                                                <select name="kondisi_kembali" class="form-select" required>
                                                    <option value="Baik" selected>Baik (Lengkap & Berfungsi Normal)</option>
                                                    <option value="Rusak Ringan">Rusak Ringan (Ada keluhan/cacat ringan)</option>
                                                    <option value="Rusak Berat">Rusak Berat (Mati total / pecah / komponen hilang)</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Catatan Pengembalian (Opsional)</label>
                                                <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan kelengkapan atau pemeriksaan saat barang diterima..."></textarea>
                                            </div>

                                            <div class="alert alert-info py-2 px-3 small mb-0 rounded-3">
                                                <i class="fa-solid fa-circle-info me-1"></i>
                                                Setelah diproses, stok fisik aset sebanyak <strong>{{ $p->jumlah }} unit</strong> akan otomatis dikembalikan ke inventaris ruangan asal.
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-success fw-bold px-4">
                                                <i class="fa-solid fa-check-circle me-1"></i> Konfirmasi Pengembalian
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- MODAL RINCIAN PAKET ASET (KERANJANG) -->
                        @if($p->details && $p->details->count() > 0)
                        <div class="modal fade text-start" id="modalRincianAset{{ $p->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-dark text-white">
                                        <h6 class="modal-title fw-bold">
                                            <i class="fa-solid fa-boxes-stacked text-primary me-2"></i>Rincian Paket Aset [{{ $p->kode_peminjaman }}]
                                        </h6>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-3">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover align-middle mb-0 small">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th>Nama Barang</th>
                                                        <th>Ruangan Lab</th>
                                                        <th class="text-center">Jumlah</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($p->details as $d)
                                                    <tr>
                                                        <td>
                                                            <div class="fw-bold text-dark">{{ $d->barang->nama_barang ?? '-' }}</div>
                                                            <span class="badge-code py-0" style="font-size: 0.7rem;">{{ $d->barang->kode_barang ?? '-' }}</span>
                                                        </td>
                                                        <td class="text-muted">{{ $d->barang->ruangan->nama_ruangan ?? '-' }}</td>
                                                        <td class="text-center fw-bold text-primary">{{ $d->jumlah }} Unit</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer py-2">
                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="text-muted mb-2">
                            <i class="fa-solid fa-hand-holding-hand fa-3x opacity-50"></i>
                        </div>
                        <h6 class="fw-bold text-secondary mb-1">Belum Ada Data Peminjaman</h6>
                        <p class="text-muted small mb-3">Pengajuan dari dosen atau mahasiswa di portal publik akan masuk ke sini.</p>
                        <a href="{{ url('/') }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Buka Portal Peminjaman Mandiri
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL CATAT PEMINJAMAN INTERNAL OLEH PETUGAS -->
<div class="modal fade" id="modalTambahPeminjamanInternal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-hand-holding-hand text-primary me-2"></i>Catat Peminjaman Langsung (Internal)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('publik.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Kategori Pemohon <span class="text-danger">*</span></label>
                            <select name="kategori_peminjam" class="form-select" required>
                                <option value="Dosen">Dosen</option>
                                <option value="Mahasiswa" selected>Mahasiswa</option>
                                <option value="Staf / Tendik">Staf / Tendik</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Nama Peminjam <span class="text-danger">*</span></label>
                            <input type="text" name="nama_peminjam" class="form-control" placeholder="Nama peminjam" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">NIM / NIP / NIDN <span class="text-danger">*</span></label>
                            <input type="text" name="nomor_identitas" class="form-control font-monospace" placeholder="Nomor identitas" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Prodi / Unit Kerja <span class="text-danger">*</span></label>
                            <input type="text" name="prodi_unit" class="form-control" placeholder="Misal: S1 Keperawatan" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">No. WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" name="kontak_peminjam" class="form-control" placeholder="081234567890" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Aset yang Dipinjam <span class="text-danger">*</span></label>
                            <select name="barang_id" class="form-select" required>
                                <option value="">-- Pilih Aset (Hanya yang Bisa Dipinjam) --</option>
                                @foreach($barangBisaDipinjam as $b)
                                    <option value="{{ $b->id }}">
                                        {{ $b->nama_barang }} [{{ $b->kode_barang }}] — Ruangan: {{ $b->ruangan->nama_ruangan ?? '-' }} (Sisa: {{ $b->jumlah }} unit)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Jumlah Unit <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah" class="form-control" min="1" value="1" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Tanggal Pinjam <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_pinjam" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Tenggat Kembali <span class="text-danger">*</span></label>
                            <input type="date" name="tenggat_kembali" class="form-control" value="{{ date('Y-m-d', strtotime('+3 days')) }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Keperluan / Alasan <span class="text-danger">*</span></label>
                            <textarea name="keperluan" class="form-control" rows="2" placeholder="Keperluan peminjaman aset..." required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">
                        <i class="fa-solid fa-paper-plane me-1"></i> Simpan Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
