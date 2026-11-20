@extends('layouts.app')

@section('title', 'Manajemen Peminjaman Aset & Ruangan')

@section('content')
<!-- HEADER UTAMA -->
<div class="row g-3 mb-4 align-items-center">
    <div class="col-md-7">
        <h3 class="fw-bold text-dark mb-1" style="letter-spacing: -0.02em;">
            Manajemen Peminjaman Aset & Ruangan
        </h3>
        <p class="text-muted small mb-0">Kelola persetujuan (approval) Kepala Sarpras, serah terima fisik/kunci, dan pemantauan pengembalian.</p>
    </div>
    <div class="col-md-5 text-md-end d-flex gap-2 justify-content-md-end flex-wrap">
        <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Buka Portal Publik
        </a>
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPeminjamanInternal">
            <i class="fa-solid fa-box-open me-1"></i>
            <span>Catat Pinjam Aset</span>
        </button>
        <button class="btn btn-modern-primary" data-bs-toggle="modal" data-bs-target="#modalTambahBookingInternal">
            <i class="fa-solid fa-door-open me-1"></i>
            <span>Catat Booking Ruangan</span>
        </button>
    </div>
</div>

<!-- SEGMENTED CAPSULE PILL SWITCHER (PERSIS SEPERTI GAMBAR) -->
<ul class="nav nav-pills nav-fill nav-pills-portal gap-2 mb-4 p-1.5 bg-light rounded-4 border" id="pills-tab-peminjaman" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ $activeTab === 'aset' ? 'active' : '' }} fw-bold py-2.5 rounded-pill d-flex align-items-center justify-content-center gap-2" id="pills-aset-tab" data-bs-toggle="pill" data-bs-target="#pills-aset" type="button" role="tab" aria-controls="pills-aset" aria-selected="{{ $activeTab === 'aset' ? 'true' : 'false' }}" onclick="updateActiveTab('aset')">
            <i class="fa-solid fa-boxes-stacked"></i>
            <span>Peminjaman Aset Sarpras ({{ number_format($statsAset['total']) }} Item)</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ $activeTab === 'ruangan' ? 'active' : '' }} fw-bold py-2.5 rounded-pill d-flex align-items-center justify-content-center gap-2" id="pills-ruangan-tab" data-bs-toggle="pill" data-bs-target="#pills-ruangan" type="button" role="tab" aria-controls="pills-ruangan" aria-selected="{{ $activeTab === 'ruangan' ? 'true' : 'false' }}" onclick="updateActiveTab('ruangan')">
            <i class="fa-solid fa-door-open"></i>
            <span>Peminjaman Ruangan Kegiatan ({{ number_format($statsRuangan['total']) }} Ruangan)</span>
        </button>
    </li>
</ul>

<!-- KONTEN TAB ASET vs RUANGAN -->
<div class="tab-content" id="pills-tabContentPeminjaman">

    <!-- ======================================================== -->
    <!-- TAB 1: PEMINJAMAN ASET SARPRAS                           -->
    <!-- ======================================================== -->
    <div class="tab-pane fade {{ $activeTab === 'aset' ? 'show active' : '' }}" id="pills-aset" role="tabpanel" aria-labelledby="pills-aset-tab">
        
        <!-- KARTU STATISTIK TAHAPAN PEMINJAMAN ASET -->
        <div class="row g-3 mb-4">
            <div class="col-xl-2 col-md-4 col-sm-6">
                <a href="{{ route('peminjaman.index', ['tab' => 'aset']) }}" class="text-decoration-none">
                    <div class="card card-stat-filter h-100 {{ (!request('status') && $activeTab === 'aset') ? 'active-stat active-stat-primary' : '' }}">
                        <div class="card-body p-3 text-center">
                            <div class="text-muted small fw-semibold mb-1">Semua Pengajuan</div>
                            <div class="fs-4 fw-bold text-dark">{{ number_format($statsAset['total']) }}</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <a href="{{ route('peminjaman.index', ['tab' => 'aset', 'status' => 'Menunggu']) }}" class="text-decoration-none">
                    <div class="card card-stat-filter h-100 {{ (request('status') === 'Menunggu' && $activeTab === 'aset') ? 'active-stat active-stat-warning' : '' }}">
                        <div class="card-body p-3 text-center">
                            <div class="text-warning small fw-bold mb-1">Menunggu Approval</div>
                            <div class="fs-4 fw-bold text-warning">{{ number_format($statsAset['menunggu']) }}</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <a href="{{ route('peminjaman.index', ['tab' => 'aset', 'status' => 'Disetujui']) }}" class="text-decoration-none">
                    <div class="card card-stat-filter h-100 {{ (request('status') === 'Disetujui' && $activeTab === 'aset') ? 'active-stat active-stat-primary' : '' }}">
                        <div class="card-body p-3 text-center">
                            <div class="text-primary small fw-bold mb-1">Siap Diambil</div>
                            <div class="fs-4 fw-bold text-primary">{{ number_format($statsAset['disetujui']) }}</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <a href="{{ route('peminjaman.index', ['tab' => 'aset', 'status' => 'Diambil']) }}" class="text-decoration-none">
                    <div class="card card-stat-filter h-100 {{ (request('status') === 'Diambil' && $activeTab === 'aset') ? 'active-stat active-stat-info' : '' }}">
                        <div class="card-body p-3 text-center">
                            <div class="text-info small fw-bold mb-1">Sudah Diambil</div>
                            <div class="fs-4 fw-bold text-info">{{ number_format($statsAset['diambil']) }}</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <a href="{{ route('peminjaman.index', ['tab' => 'aset', 'status' => 'Kembali']) }}" class="text-decoration-none">
                    <div class="card card-stat-filter h-100 {{ (request('status') === 'Kembali' && $activeTab === 'aset') ? 'active-stat active-stat-success' : '' }}">
                        <div class="card-body p-3 text-center">
                            <div class="text-success small fw-bold mb-1">Sudah Kembali</div>
                            <div class="fs-4 fw-bold text-success">{{ number_format($statsAset['kembali'] ?? 0) }}</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <a href="{{ route('peminjaman.index', ['tab' => 'aset', 'status' => 'Ditolak']) }}" class="text-decoration-none">
                    <div class="card card-stat-filter h-100 {{ (request('status') === 'Ditolak' && $activeTab === 'aset') ? 'active-stat active-stat-danger' : '' }}">
                        <div class="card-body p-3 text-center">
                            <div class="text-danger small fw-bold mb-1">Ditolak</div>
                            <div class="fs-4 fw-bold text-danger">{{ number_format($statsAset['ditolak'] ?? 0) }}</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- FILTER STATUS & PENCARIAN ASET -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <form action="{{ route('peminjaman.index') }}" method="GET" class="row g-2 align-items-center">
                    <input type="hidden" name="tab" value="aset">
                    <div class="col-md-5">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Cari peminjam, NIM/NIP, prodi, kode, atau nama aset..." value="{{ $activeTab === 'aset' ? request('search') : '' }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">-- Semua Tahap Status Aset --</option>
                            <option value="Menunggu" {{ ($activeTab === 'aset' && request('status') === 'Menunggu') ? 'selected' : '' }}>1. Menunggu Persetujuan Kepala Sarpras</option>
                            <option value="Disetujui" {{ ($activeTab === 'aset' && request('status') === 'Disetujui') ? 'selected' : '' }}>2. Disetujui (Siap Diambil)</option>
                            <option value="Diambil" {{ ($activeTab === 'aset' && request('status') === 'Diambil') ? 'selected' : '' }}>3. Barang Sudah Diambil (Sedang Dipinjam)</option>
                            <option value="Kembali" {{ ($activeTab === 'aset' && request('status') === 'Kembali') ? 'selected' : '' }}>4. Selesai (Sudah Dikembalikan)</option>
                            <option value="Terlambat" {{ ($activeTab === 'aset' && request('status') === 'Terlambat') ? 'selected' : '' }}>Terlambat (Lewat Tenggat)</option>
                            <option value="Ditolak" {{ ($activeTab === 'aset' && request('status') === 'Ditolak') ? 'selected' : '' }}>Ditolak oleh Kepala Sarpras</option>
                        </select>
                    </div>
                    <div class="col-md-3 text-md-end d-flex gap-2 justify-content-md-end">
                        <button type="submit" class="btn btn-sm btn-primary px-3">
                            Filter
                        </button>
                        @if($activeTab === 'aset' && request()->hasAny(['search', 'status']))
                            <a href="{{ route('peminjaman.index', ['tab' => 'aset']) }}" class="btn btn-sm btn-outline-secondary">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- TABEL DAFTAR TRANSAKSI PEMINJAMAN ASET -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark">
                    Daftar Pengajuan & Peminjaman Aset Sarpras
                </h6>
                <span class="badge bg-light text-secondary border font-monospace">{{ $peminjamansAset->count() }} Data</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4" style="width: 48px;">No</th>
                            <th style="min-width: 250px;">Peminjam & Identitas</th>
                            <th style="min-width: 240px;">Aset Dipinjam</th>
                            <th class="text-center" style="width: 95px;">Jumlah</th>
                            <th style="min-width: 190px;">Jadwal / Tenggat</th>
                            <th class="text-center" style="min-width: 140px;">Status</th>
                            <th class="text-center pe-4" style="width: 180px; min-width: 170px;">Tindakan & Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamansAset as $index => $p)
                        <tr>
                            <td class="ps-4 fw-semibold text-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="avatar-initial rounded-circle fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; font-size: 0.85rem;">
                                        {{ strtoupper(substr($p->nama_peminjam, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 0.92rem; line-height: 1.2;">{{ $p->nama_peminjam }}</div>
                                        <div class="d-flex align-items-center gap-1.5 mt-1 flex-wrap">
                                            <span class="badge bg-secondary-subtle text-secondary border px-2 py-0.5 rounded-pill" style="font-size: 0.68rem;">
                                                {{ $p->kategori_peminjam ?? 'Mahasiswa' }}
                                            </span>
                                            <span class="text-muted small font-monospace">
                                                {{ $p->nomor_identitas }}
                                                @if($p->prodi_unit)
                                                    &bull; <span class="text-secondary fw-semibold">{{ $p->prodi_unit }}</span>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                                            @if($p->kontak_peminjam)
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $p->kontak_peminjam) }}" target="_blank" class="badge bg-success-subtle text-success border border-success-subtle text-decoration-none px-2 py-0.5 rounded-pill" style="font-size: 0.72rem;">
                                                    <i class="fa-brands fa-whatsapp me-1"></i>{{ $p->kontak_peminjam }}
                                                </a>
                                            @endif
                                            <span class="badge bg-light text-muted border font-monospace px-2 py-0.5 rounded" style="font-size: 0.68rem;">
                                                {{ $p->kode_peminjaman }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($p->details && $p->details->count() > 1)
                                    <div class="fw-bold text-dark">{{ $p->details->first()->barang->nama_barang ?? '-' }}</div>
                                    <div class="mt-1">
                                        <button type="button" class="btn btn-sm btn-outline-primary py-0.5 px-2 rounded-pill fw-semibold" data-bs-toggle="modal" data-bs-target="#modalRincianAset{{ $p->id }}" style="font-size: 0.75rem;">
                                            +{{ $p->details->count() - 1 }} Aset Lainnya (Paket)
                                        </button>
                                    </div>
                                @elseif($p->details && $p->details->count() === 1)
                                    <div class="fw-bold text-dark">{{ $p->details->first()->barang->nama_barang ?? '-' }}</div>
                                    <div class="d-flex align-items-center gap-1.5 mt-1 flex-wrap">
                                        <span class="badge-code py-0.5 px-1.5">{{ $p->details->first()->barang->kode_barang ?? '-' }}</span>
                                        <span class="text-muted small">{{ $p->details->first()->barang->ruangan->nama_ruangan ?? '-' }}</span>
                                    </div>
                                @else
                                    <div class="fw-bold text-dark">{{ $p->barang->nama_barang ?? 'Aset Telah Dihapus' }}</div>
                                    <div class="d-flex align-items-center gap-1.5 mt-1 flex-wrap">
                                        <span class="badge-code py-0.5 px-1.5">{{ $p->barang->kode_barang ?? '-' }}</span>
                                        <span class="text-muted small">{{ $p->barang->ruangan->nama_ruangan ?? '-' }}</span>
                                    </div>
                                @endif
                                @if($p->keperluan)
                                    <div class="text-muted small mt-1.5 text-truncate" style="max-width: 240px; font-size: 0.75rem;" title="{{ $p->keperluan }}">
                                        "{{ $p->keperluan }}"
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 fw-bold rounded-pill" style="font-size: 0.78rem;">
                                    {{ $p->jumlah }} Unit
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <div class="small d-flex align-items-center gap-1">
                                        <span class="text-muted" style="min-width: 52px;">Pinjam:</span>
                                        <span class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->locale('id')->translatedFormat('d M Y') }}</span>
                                    </div>
                                    <div class="small d-flex align-items-center gap-1">
                                        <span class="text-muted" style="min-width: 52px;">Tenggat:</span>
                                        <strong class="{{ in_array($p->status, ['Diambil', 'Terlambat']) && $p->tenggat_kembali < now()->toDateString() ? 'text-danger' : 'text-dark' }}">
                                            {{ \Carbon\Carbon::parse($p->tenggat_kembali)->locale('id')->translatedFormat('d M Y') }}
                                        </strong>
                                    </div>
                                    @if($p->tanggal_diambil)
                                        <div class="small text-info mt-0.5" style="font-size: 0.72rem;">
                                            Diambil: {{ $p->tanggal_diambil->locale('id')->translatedFormat('d M, H:i') }}
                                        </div>
                                    @endif
                                    @if($p->tanggal_kembali)
                                        <div class="small text-success mt-0.5" style="font-size: 0.72rem;">
                                            Kembali: {{ \Carbon\Carbon::parse($p->tanggal_kembali)->locale('id')->translatedFormat('d M Y') }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                @if($p->status === 'Menunggu')
                                    <span class="badge bg-warning text-dark border border-warning px-2.5 py-1.5 rounded-pill text-nowrap">
                                        Menunggu
                                    </span>
                                @elseif($p->status === 'Disetujui')
                                    <span class="badge bg-primary text-white border border-primary px-2.5 py-1.5 rounded-pill text-nowrap">
                                        Disetujui
                                    </span>
                                @elseif($p->status === 'Diambil')
                                    <span class="badge bg-info text-dark border border-info px-2.5 py-1.5 rounded-pill text-nowrap">
                                        Diambil
                                    </span>
                                @elseif($p->status === 'Kembali')
                                    <span class="badge bg-success-subtle text-success border border-success px-2.5 py-1.5 rounded-pill text-nowrap">
                                        Kembali
                                    </span>
                                    @if($p->kondisi_kembali)
                                        <div class="small text-muted mt-1" style="font-size: 0.72rem;">Kondisi: <strong>{{ $p->kondisi_kembali }}</strong></div>
                                    @endif
                                @elseif($p->status === 'Ditolak')
                                    <span class="badge bg-danger text-white border border-danger px-2.5 py-1.5 rounded-pill text-nowrap">
                                        Ditolak
                                    </span>
                                    @if($p->alasan_penolakan)
                                        <div class="small text-danger mt-1 text-truncate" style="font-size: 0.72rem; max-width: 130px;" title="{{ $p->alasan_penolakan }}">Alasan: {{ $p->alasan_penolakan }}</div>
                                    @endif
                                @else
                                    <span class="badge bg-danger text-white border border-danger px-2.5 py-1.5 rounded-pill text-nowrap">
                                        Terlambat
                                    </span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center align-items-center gap-1.5 flex-nowrap">
                                    <!-- AKSI TAHAP 1: APPROVAL KEPALA SARPRAS -->
                                    @if($p->status === 'Menunggu')
                                        <form action="{{ route('peminjaman.approve', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin MENYETUJUI peminjaman aset ini sebagai Kepala Sarpras?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary fw-bold text-nowrap" title="Setujui Peminjaman">
                                                Setujui
                                            </button>
                                        </form>

                                        <button type="button" class="btn btn-sm btn-outline-danger text-nowrap" data-bs-toggle="modal" data-bs-target="#modalTolakAset{{ $p->id }}" title="Tolak Pengajuan">
                                            Tolak
                                        </button>

                                    <!-- AKSI TAHAP 2: SERAH TERIMA BARANG -->
                                    @elseif($p->status === 'Disetujui')
                                        <form action="{{ route('peminjaman.serahkan', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Konfirmasi bahwa barang fisik telah diserahkan dan diambil oleh peminjam? Stok aset akan resmi dipotong.')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-info text-white fw-bold shadow-sm text-nowrap" title="Serahkan Barang ke Pemohon">
                                                Serahkan Barang
                                            </button>
                                        </form>

                                    <!-- AKSI TAHAP 3: PENGEMBALIAN BARANG -->
                                    @elseif(in_array($p->status, ['Diambil', 'Terlambat']))
                                        <button type="button" class="btn btn-sm btn-success fw-bold shadow-sm text-nowrap" data-bs-toggle="modal" data-bs-target="#modalKembali{{ $p->id }}" title="Proses Pengembalian Barang">
                                            Kembalikan
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

                                <!-- MODAL TOLAK ASET -->
                                @if($p->status === 'Menunggu')
                                <div class="modal fade text-start" id="modalTolakAset{{ $p->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title fw-bold">
                                                    Tolak Permohonan Peminjaman Aset
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
                                                        <textarea name="alasan_penolakan" class="form-control" rows="3" placeholder="Misal: Alat sedang dalam perbaikan / Persiapan ujian OSCE..." required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger fw-bold">
                                                        Konfirmasi Tolak
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- MODAL PENGEMBALIAN ASET -->
                                @if(in_array($p->status, ['Diambil', 'Terlambat']))
                                <div class="modal fade text-start" id="modalKembali{{ $p->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title fw-bold">
                                                    Pengembalian Aset Fisik
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
                                                            <option value="Rusak Berat">Rusak Berat (Mati total / komponen hilang)</option>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Catatan Pengembalian (Opsional)</label>
                                                        <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan kelengkapan atau pemeriksaan saat barang diterima..."></textarea>
                                                    </div>

                                                    <div class="alert alert-info py-2 px-3 small mb-0 rounded-3">
                                                        Setelah diproses, stok fisik aset sebanyak <strong>{{ $p->jumlah }} unit</strong> akan otomatis dikembalikan ke inventaris ruangan asal.
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-success fw-bold px-4">
                                                        Konfirmasi Pengembalian
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- MODAL RINCIAN PAKET ASET -->
                                @if($p->details && $p->details->count() > 0)
                                <div class="modal fade text-start" id="modalRincianAset{{ $p->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-dark text-white">
                                                <h6 class="modal-title fw-bold">
                                                    Rincian Paket Aset [{{ $p->kode_peminjaman }}]
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
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <h6 class="fw-bold text-secondary mb-1">Belum Ada Data Peminjaman Aset</h6>
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
    </div>

    <!-- ======================================================== -->
    <!-- TAB 2: PEMINJAMAN RUANGAN KEGIATAN                       -->
    <!-- ======================================================== -->
    <div class="tab-pane fade {{ $activeTab === 'ruangan' ? 'show active' : '' }}" id="pills-ruangan" role="tabpanel" aria-labelledby="pills-ruangan-tab">
        
        <!-- KARTU STATISTIK TAHAPAN BOOKING RUANGAN -->
        <div class="row g-3 mb-4">
            <div class="col-xl-2 col-md-4 col-sm-6">
                <a href="{{ route('peminjaman.index', ['tab' => 'ruangan']) }}" class="text-decoration-none">
                    <div class="card card-stat-filter h-100 {{ (!request('status') && $activeTab === 'ruangan') ? 'active-stat active-stat-primary' : '' }}">
                        <div class="card-body p-3 text-center">
                            <div class="text-muted small fw-semibold mb-1">Semua Permohonan</div>
                            <div class="fs-4 fw-bold text-dark">{{ number_format($statsRuangan['total']) }}</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <a href="{{ route('peminjaman.index', ['tab' => 'ruangan', 'status' => 'Menunggu']) }}" class="text-decoration-none">
                    <div class="card card-stat-filter h-100 {{ (request('status') === 'Menunggu' && $activeTab === 'ruangan') ? 'active-stat active-stat-warning' : '' }}">
                        <div class="card-body p-3 text-center">
                            <div class="text-warning small fw-bold mb-1">Menunggu Approval</div>
                            <div class="fs-4 fw-bold text-warning">{{ number_format($statsRuangan['menunggu']) }}</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <a href="{{ route('peminjaman.index', ['tab' => 'ruangan', 'status' => 'Disetujui']) }}" class="text-decoration-none">
                    <div class="card card-stat-filter h-100 {{ (request('status') === 'Disetujui' && $activeTab === 'ruangan') ? 'active-stat active-stat-primary' : '' }}">
                        <div class="card-body p-3 text-center">
                            <div class="text-primary small fw-bold mb-1">Terkunci (Siap Pakai)</div>
                            <div class="fs-4 fw-bold text-primary">{{ number_format($statsRuangan['disetujui']) }}</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <a href="{{ route('peminjaman.index', ['tab' => 'ruangan', 'status' => 'Digunakan']) }}" class="text-decoration-none">
                    <div class="card card-stat-filter h-100 {{ (request('status') === 'Digunakan' && $activeTab === 'ruangan') ? 'active-stat active-stat-info' : '' }}">
                        <div class="card-body p-3 text-center">
                            <div class="text-info small fw-bold mb-1">Sedang Digunakan</div>
                            <div class="fs-4 fw-bold text-info">{{ number_format($statsRuangan['digunakan']) }}</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <a href="{{ route('peminjaman.index', ['tab' => 'ruangan', 'status' => 'Selesai']) }}" class="text-decoration-none">
                    <div class="card card-stat-filter h-100 {{ (request('status') === 'Selesai' && $activeTab === 'ruangan') ? 'active-stat active-stat-success' : '' }}">
                        <div class="card-body p-3 text-center">
                            <div class="text-success small fw-bold mb-1">Selesai</div>
                            <div class="fs-4 fw-bold text-success">{{ number_format($statsRuangan['selesai']) }}</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <a href="{{ route('peminjaman.index', ['tab' => 'ruangan', 'status' => 'Ditolak']) }}" class="text-decoration-none">
                    <div class="card card-stat-filter h-100 {{ (request('status') === 'Ditolak' && $activeTab === 'ruangan') ? 'active-stat active-stat-danger' : '' }}">
                        <div class="card-body p-3 text-center">
                            <div class="text-danger small fw-bold mb-1">Ditolak</div>
                            <div class="fs-4 fw-bold text-danger">{{ number_format($statsRuangan['ditolak']) }}</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- FILTER STATUS & PENCARIAN RUANGAN -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <form action="{{ route('peminjaman.index') }}" method="GET" class="row g-2 align-items-center">
                    <input type="hidden" name="tab" value="ruangan">
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Cari pemohon, NIM/NIP, prodi, kode, ruangan..." value="{{ $activeTab === 'ruangan' ? request('search') : '' }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">-- Semua Status Ruangan --</option>
                            <option value="Menunggu" {{ ($activeTab === 'ruangan' && request('status') === 'Menunggu') ? 'selected' : '' }}>1. Menunggu Approval Kepala Sarpras</option>
                            <option value="Disetujui" {{ ($activeTab === 'ruangan' && request('status') === 'Disetujui') ? 'selected' : '' }}>2. Disetujui (Jadwal Terkunci)</option>
                            <option value="Digunakan" {{ ($activeTab === 'ruangan' && request('status') === 'Digunakan') ? 'selected' : '' }}>3. Sedang Digunakan</option>
                            <option value="Selesai" {{ ($activeTab === 'ruangan' && request('status') === 'Selesai') ? 'selected' : '' }}>4. Selesai Pemakaian</option>
                            <option value="Ditolak" {{ ($activeTab === 'ruangan' && request('status') === 'Ditolak') ? 'selected' : '' }}>Ditolak oleh Kepala Sarpras</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ $activeTab === 'ruangan' ? request('tanggal') : '' }}" onchange="this.form.submit()" title="Filter Berdasarkan Tanggal Pemakaian">
                    </div>
                    <div class="col-md-2 text-md-end d-flex gap-1 justify-content-md-end">
                        <button type="submit" class="btn btn-sm btn-primary px-3">
                            Filter
                        </button>
                        @if($activeTab === 'ruangan' && request()->hasAny(['search', 'status', 'tanggal']))
                            <a href="{{ route('peminjaman.index', ['tab' => 'ruangan']) }}" class="btn btn-sm btn-outline-secondary">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- TABEL DAFTAR TRANSAKSI BOOKING RUANGAN -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark">
                    Daftar Pengajuan & Booking Ruangan Kegiatan
                </h6>
                <span class="badge bg-light text-secondary border font-monospace">{{ $peminjamansRuangan->count() }} Data</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4" style="width: 48px;">No</th>
                            <th style="min-width: 250px;">Pemohon & Identitas</th>
                            <th style="min-width: 220px;">Ruangan</th>
                            <th style="min-width: 200px;">Jadwal & Waktu Pemakaian</th>
                            <th class="text-center" style="min-width: 140px;">Status Booking</th>
                            <th class="text-center pe-4" style="width: 200px; min-width: 180px;">Tindakan & Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamansRuangan as $index => $p)
                        <tr>
                            <td class="ps-4 fw-semibold text-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="avatar-initial rounded-circle fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; font-size: 0.85rem;">
                                        {{ strtoupper(substr($p->nama_peminjam, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 0.92rem; line-height: 1.2;">{{ $p->nama_peminjam }}</div>
                                        <div class="d-flex align-items-center gap-1.5 mt-1 flex-wrap">
                                            <span class="badge bg-secondary-subtle text-secondary border px-2 py-0.5 rounded-pill" style="font-size: 0.68rem;">
                                                {{ $p->kategori_peminjam ?? 'Mahasiswa / Ormawa' }}
                                            </span>
                                            <span class="text-muted small font-monospace">
                                                {{ $p->nomor_identitas }}
                                                @if($p->prodi_unit)
                                                    &bull; <span class="text-secondary fw-semibold">{{ $p->prodi_unit }}</span>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                                            @if($p->kontak_peminjam)
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $p->kontak_peminjam) }}" target="_blank" class="badge bg-success-subtle text-success border border-success-subtle text-decoration-none px-2 py-0.5 rounded-pill" style="font-size: 0.72rem;">
                                                    <i class="fa-brands fa-whatsapp me-1"></i>{{ $p->kontak_peminjam }}
                                                </a>
                                            @endif
                                            <span class="badge bg-light text-muted border font-monospace px-2 py-0.5 rounded" style="font-size: 0.68rem;">
                                                {{ $p->kode_booking }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $p->ruangan->nama_ruangan ?? 'Ruangan Telah Dihapus' }}</div>
                                <div class="d-flex align-items-center gap-1.5 mt-1 flex-wrap">
                                    <span class="badge-code py-0.5 px-1.5">{{ $p->ruangan->kode_ruangan ?? '-' }}</span>
                                </div>
                                @if($p->keperluan)
                                    <div class="text-muted small mt-1.5 text-truncate" style="max-width: 240px; font-size: 0.75rem;" title="{{ $p->keperluan }}">
                                        "{{ $p->keperluan }}"
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <div class="fw-semibold text-dark small">
                                        {{ \Carbon\Carbon::parse($p->tanggal_pemakaian)->locale('id')->translatedFormat('l, d M Y') }}
                                    </div>
                                    <div class="small text-primary font-monospace">
                                        {{ date('H:i', strtotime($p->jam_mulai)) }} - {{ date('H:i', strtotime($p->jam_selesai)) }} WIB
                                    </div>
                                    @if($p->waktu_masuk)
                                        <div class="small text-info mt-0.5" style="font-size: 0.72rem;">
                                            Dibuka: {{ $p->waktu_masuk->locale('id')->translatedFormat('d M, H:i') }}
                                        </div>
                                    @endif
                                    @if($p->waktu_selesai)
                                        <div class="small text-success mt-0.5" style="font-size: 0.72rem;">
                                            Selesai: {{ $p->waktu_selesai->locale('id')->translatedFormat('d M, H:i') }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                @if($p->status === 'Menunggu')
                                    <span class="badge bg-warning text-dark border border-warning px-2.5 py-1.5 rounded-pill text-nowrap">
                                        Menunggu
                                    </span>
                                @elseif($p->status === 'Disetujui')
                                    <span class="badge bg-primary text-white border border-primary px-2.5 py-1.5 rounded-pill text-nowrap">
                                        Disetujui
                                    </span>
                                @elseif($p->status === 'Digunakan')
                                    <span class="badge bg-info text-dark border border-info px-2.5 py-1.5 rounded-pill text-nowrap">
                                        Sedang Digunakan
                                    </span>
                                @elseif($p->status === 'Selesai')
                                    <span class="badge bg-success-subtle text-success border border-success px-2.5 py-1.5 rounded-pill text-nowrap">
                                        Selesai
                                    </span>
                                @elseif($p->status === 'Ditolak')
                                    <span class="badge bg-danger text-white border border-danger px-2.5 py-1.5 rounded-pill text-nowrap">
                                        Ditolak
                                    </span>
                                    @if($p->alasan_penolakan)
                                        <div class="small text-danger mt-1 text-truncate" style="font-size: 0.72rem; max-width: 130px;" title="{{ $p->alasan_penolakan }}">{{ $p->alasan_penolakan }}</div>
                                    @endif
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center align-items-center gap-1.5 flex-nowrap">
                                    <!-- AKSI TAHAP 1: APPROVAL KEPALA SARPRAS -->
                                    @if($p->status === 'Menunggu')
                                        <form action="{{ route('peminjaman-ruangan.setujui', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Setujui permohonan booking ruangan ini? Jadwal akan dikunci untuk pemohon.')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary fw-bold shadow-sm text-nowrap" title="Setujui Booking">
                                                Setujui
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-outline-danger text-nowrap" data-bs-toggle="modal" data-bs-target="#modalTolakRuangan{{ $p->id }}" title="Tolak Booking">
                                            Tolak
                                        </button>

                                    <!-- AKSI TAHAP 2: BUKA RUANGAN / MULAI DIGUNAKAN -->
                                    @elseif($p->status === 'Disetujui')
                                        <form action="{{ route('peminjaman-ruangan.serahkan', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Konfirmasi ruangan telah dibuka untuk kegiatan pemohon? Status akan berubah menjadi [Sedang Digunakan].')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-info text-white fw-bold shadow-sm text-nowrap" title="Buka Ruangan untuk Kegiatan">
                                                Buka Ruangan
                                            </button>
                                        </form>

                                    <!-- AKSI TAHAP 3: SELESAI & KUNCI RUANGAN -->
                                    @elseif($p->status === 'Digunakan')
                                        <button type="button" class="btn btn-sm btn-success fw-bold shadow-sm text-nowrap" data-bs-toggle="modal" data-bs-target="#modalSelesaiRuangan{{ $p->id }}" title="Selesai & Kunci Ruangan">
                                            Selesai & Kunci
                                        </button>
                                    @endif

                                    <!-- Tombol Tiket Bukti Publik -->
                                    <a href="{{ route('publik.ruangan.sukses', $p->kode_booking) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Lihat E-Ticket Digital">
                                        <i class="fa-solid fa-receipt"></i>
                                    </a>

                                    <!-- Tombol Hapus Transaksi -->
                                    <form action="{{ route('peminjaman-ruangan.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(event, this, 'data booking ruangan {{ $p->kode_booking }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Data">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- MODAL TOLAK BOOKING RUANGAN -->
                                @if($p->status === 'Menunggu')
                                <div class="modal fade text-start" id="modalTolakRuangan{{ $p->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title fw-bold">
                                                    Tolak Permohonan Booking Ruangan
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('peminjaman-ruangan.reject', $p->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body p-4">
                                                    <p class="small text-muted mb-3">
                                                        Anda akan menolak pengajuan booking <strong>{{ $p->kode_booking }}</strong> atas nama <strong>{{ $p->nama_peminjam }}</strong> untuk ruangan <strong>{{ $p->ruangan->nama_ruangan ?? '-' }}</strong>.
                                                    </p>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Alasan Penolakan <span class="text-danger">*</span></label>
                                                        <textarea name="alasan_penolakan" class="form-control" rows="3" placeholder="Misal: Ruangan sedang dalam pemeliharaan/perbaikan AC / Dipakai untuk acara resmi kampus..." required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger fw-bold">
                                                        Konfirmasi Tolak
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- MODAL SELESAI PEMAKAIAN RUANGAN -->
                                @if($p->status === 'Digunakan')
                                <div class="modal fade text-start" id="modalSelesaiRuangan{{ $p->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title fw-bold">
                                                    Konfirmasi Selesai Pemakaian Ruangan
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('peminjaman-ruangan.selesai', $p->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body p-4">
                                                    <div class="p-3 bg-light rounded-3 border mb-3">
                                                        <div class="fw-bold text-dark fs-6">{{ $p->ruangan->nama_ruangan ?? '-' }}</div>
                                                        <div class="text-muted small mt-1">
                                                            Pemohon: <strong>{{ $p->nama_peminjam }}</strong> ({{ $p->nomor_identitas }})
                                                        </div>
                                                        <div class="text-muted small">
                                                            Waktu: {{ \Carbon\Carbon::parse($p->tanggal_pemakaian)->isoFormat('D MMM Y') }} ({{ date('H:i', strtotime($p->jam_mulai)) }} - {{ date('H:i', strtotime($p->jam_selesai)) }} WIB)
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Catatan Pemeriksaan Ruangan (Opsional)</label>
                                                        <textarea name="catatan_kondisi" class="form-control" rows="3" placeholder="Catatan kebersihan, kondisi AC/lampu/proyektor setelah selesai dipakai..."></textarea>
                                                    </div>

                                                    <div class="alert alert-info py-2 px-3 small mb-0 rounded-3">
                                                        Setelah dikonfirmasi, status booking akan selesai dan ruangan tercatat telah dikunci kembali oleh petugas Sarpras.
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-success fw-bold px-4">
                                                        Selesai & Kunci Ruangan
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <h6 class="fw-bold text-secondary mb-1">Belum Ada Data Booking Ruangan</h6>
                                <p class="text-muted small mb-3">Pengajuan dari dosen atau mahasiswa di portal publik akan masuk ke sini.</p>
                                <a href="{{ url('/#tab-ruangan') }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Buka Portal Booking Ruangan
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- ======================================================== -->
<!-- MODAL FORM INPUT INTERNAL: ASET & RUANGAN                -->
<!-- ======================================================== -->

<!-- MODAL CATAT PEMINJAMAN ASET INTERNAL OLEH PETUGAS -->
<div class="modal fade" id="modalTambahPeminjamanInternal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold">
                    Catat Peminjaman Langsung Aset (Internal)
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
                                        {{ $b->nama_barang }} [{{ $b->kode_barang }}] • Ruangan: {{ $b->ruangan->nama_ruangan ?? '-' }} (Sisa: {{ $b->jumlah }} unit)
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
                        Simpan Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL CATAT BOOKING RUANGAN INTERNAL OLEH PETUGAS -->
<div class="modal fade" id="modalTambahBookingInternal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold">
                    Catat Booking Ruangan Internal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('publik.ruangan.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Kategori Pemohon <span class="text-danger">*</span></label>
                            <select name="kategori_peminjam" class="form-select" required>
                                <option value="Dosen">Dosen</option>
                                <option value="Mahasiswa / Ormawa" selected>Mahasiswa / Ormawa</option>
                                <option value="Staf / Tendik">Staf / Tendik</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Nama Pemohon <span class="text-danger">*</span></label>
                            <input type="text" name="nama_peminjam" class="form-control" placeholder="Nama lengkap" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">NIM / NIP <span class="text-danger">*</span></label>
                            <input type="text" name="nomor_identitas" class="form-control font-monospace" placeholder="Nomor identitas" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Prodi / Unit Kerja <span class="text-danger">*</span></label>
                            <input type="text" name="prodi_unit" class="form-control" placeholder="Misal: S1 Keperawatan / BEM" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">No. WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" name="kontak_peminjam" class="form-control" placeholder="081234567890" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Ruangan yang Dipinjam <span class="text-danger">*</span></label>
                            <select name="ruangan_id" class="form-select" required>
                                <option value="">-- Pilih Ruangan (Hanya yang Bisa Dipinjam) --</option>
                                @foreach($ruanganBisaDipinjam as $r)
                                    <option value="{{ $r->id }}">
                                        {{ $r->nama_ruangan }} [{{ $r->kode_ruangan }}]
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Tanggal Pemakaian <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_pemakaian" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" name="jam_mulai" class="form-control" value="08:00" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" name="jam_selesai" class="form-control" value="12:00" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Keperluan / Acara <span class="text-danger">*</span></label>
                            <textarea name="keperluan" class="form-control" rows="2" placeholder="Keperluan pemakaian ruangan..." required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">
                        Simpan Booking Ruangan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updateActiveTab(tabName) {
        const url = new URL(window.location);
        url.searchParams.set('tab', tabName);
        // Hapus filter query string ketika berpindah tab secara dinamis agar bersih
        url.searchParams.delete('status');
        url.searchParams.delete('search');
        url.searchParams.delete('tanggal');
        window.history.replaceState({}, '', url);
    }
</script>
@endsection
