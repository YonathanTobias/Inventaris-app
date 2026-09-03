@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 no-print d-print-none page-web-header">
    <div>
        <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
            <span class="p-2 rounded-3 bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                <i class="fa-solid fa-file-invoice"></i>
            </span>
            <span>Pusat Laporan & Rekapitulasi Sarpras</span>
        </h4>
        <p class="text-muted small mb-0 ms-md-5">Cetak dan unduh rekapitulasi data inventaris KIR, laporan peminjaman aset, dan booking ruangan.</p>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4 no-print">
    <div class="col-6 col-lg-3">
        <div class="card-stat">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Aset Terdaftar</div>
                    <div class="stat-value mt-1">{{ number_format($stats['total_aset_tercatat']) }}</div>
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;"><i class="fa-solid fa-boxes-stacked text-primary me-1"></i>{{ number_format($stats['total_unit_tercatat']) }} Unit Fisik</div>
                </div>
                <div class="stat-icon primary">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card-stat">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Ruangan Terdata</div>
                    <div class="stat-value mt-1 text-success">{{ number_format($stats['total_ruangan']) }}</div>
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;"><i class="fa-solid fa-door-open text-success me-1"></i>Titik Lokasi KIR</div>
                </div>
                <div class="stat-icon success">
                    <i class="fa-solid fa-door-open"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card-stat">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Peminjaman Aset</div>
                    <div class="stat-value mt-1 text-info">{{ number_format($stats['total_pinjam_aset']) }}</div>
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;"><i class="fa-solid fa-hand-holding-hand text-info me-1"></i>Data Transaksi</div>
                </div>
                <div class="stat-icon info">
                    <i class="fa-solid fa-hand-holding-hand"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card-stat">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Booking Ruangan</div>
                    <div class="stat-value mt-1 text-warning">{{ number_format($stats['total_pinjam_ruangan']) }}</div>
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;"><i class="fa-solid fa-calendar-check text-warning me-1"></i>Jadwal Kegiatan</div>
                </div>
                <div class="stat-icon warning">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- NAVIGASI TAB UTAMA LAPORAN -->
<div class="card border-0 shadow-sm rounded-4 mb-4 no-print">
    <div class="card-body p-2">
        <ul class="nav nav-pills nav-fill gap-2" id="pills-tab-laporan" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold py-2.5 rounded-3 {{ request('tab') == 'peminjaman-aset' || request('tab') == 'peminjaman-ruangan' ? '' : 'active' }}" id="tab-kir-btn" data-bs-toggle="pill" data-bs-target="#tab-kir" type="button" role="tab" onclick="setLaporanTab('kir')">
                    <i class="fa-solid fa-file-excel me-1.5 text-success"></i> 1. Kartu Inventaris Ruangan (KIR) & Data Aset
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold py-2.5 rounded-3 {{ request('tab') == 'peminjaman-aset' ? 'active' : '' }}" id="tab-pinjam-aset-btn" data-bs-toggle="pill" data-bs-target="#tab-pinjam-aset" type="button" role="tab" onclick="setLaporanTab('peminjaman-aset')">
                    <i class="fa-solid fa-hand-holding-hand me-1.5 text-primary"></i> 2. Laporan Peminjaman Aset / Barang
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold py-2.5 rounded-3 {{ request('tab') == 'peminjaman-ruangan' ? 'active' : '' }}" id="tab-pinjam-ruangan-btn" data-bs-toggle="pill" data-bs-target="#tab-pinjam-ruangan" type="button" role="tab" onclick="setLaporanTab('peminjaman-ruangan')">
                    <i class="fa-solid fa-calendar-check me-1.5 text-warning"></i> 3. Laporan Peminjaman Ruangan
                </button>
            </li>
        </ul>
    </div>
</div>

<!-- ========================= ISI TAB LAPORAN ========================= -->
<div class="tab-content" id="pills-tabContentLaporan">

    <!-- ================= TAB 1: KARTU INVENTARIS RUANGAN (KIR) ================= -->
    <div class="tab-pane fade {{ request('tab') == 'peminjaman-aset' || request('tab') == 'peminjaman-ruangan' ? '' : 'show active' }}" id="tab-kir" role="tabpanel">
        
        <!-- Filter & Actions KIR -->
        <div class="card mb-4 no-print">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-filter text-primary"></i>
                        <span>Filter Ruangan & Cetak KIR</span>
                    </h6>
                    <button class="btn btn-sm btn-outline-primary fw-semibold rounded-pill px-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePengaturanTTD">
                        <i class="fa-solid fa-pen-nib me-1"></i> Atur Penandatangan Laporan
                    </button>
                </div>

                <form action="{{ route('laporan.export') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-lg-4 col-md-12">
                        <label class="form-label">Filter Ruangan / Tipe Laporan</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-door-open"></i></span>
                            <select name="ruangan_id" class="form-select" onchange="window.location.href='{{ route('laporan.index') }}?tab=kir&ruangan_id=' + this.value">
                                <option value="">-- SEMUA RUANGAN (REKAP GLOBAL) --</option>
                                @foreach($ruangans as $r)
                                    <option value="{{ $r->id }}" {{ request('ruangan_id') == $r->id ? 'selected' : '' }}>
                                        KIR - {{ $r->nama_ruangan }} ({{ $r->kode_ruangan }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3">
                        <button type="submit" class="btn btn-success w-100 fw-bold py-2 d-flex align-items-center justify-content-center gap-1.5 shadow-sm">
                            <i class="fa-solid fa-file-excel"></i>
                            <span>Export Excel</span>
                        </button>
                    </div>
                    <div class="col-lg-2 col-md-3">
                        <button type="button" onclick="triggerPrintTab('kir')" class="btn btn-outline-dark w-100 py-2 d-flex align-items-center justify-content-center gap-1.5">
                            <i class="fa-solid fa-print"></i>
                            <span>Cetak KIR</span>
                        </button>
                    </div>
                    <div class="col-lg-3 col-md-4">
                        @if(request('ruangan_id'))
                            <a href="{{ route('ruangan.label', request('ruangan_id')) }}" target="_blank" class="btn btn-primary w-100 py-2 fw-semibold d-flex align-items-center justify-content-center gap-1.5 shadow-sm">
                                <i class="fa-solid fa-barcode"></i>
                                <span>Cetak Label Ruangan</span>
                            </a>
                        @else
                            <a href="{{ route('barang.label.massal') }}" target="_blank" class="btn btn-primary w-100 py-2 fw-semibold d-flex align-items-center justify-content-center gap-1.5 shadow-sm">
                                <i class="fa-solid fa-barcode"></i>
                                <span>Cetak Label Global</span>
                            </a>
                        @endif
                    </div>
                    <div class="col-lg-1 col-md-2">
                        <a href="{{ route('laporan.index') }}?tab=kir" class="btn btn-outline-secondary w-100 py-2 d-flex align-items-center justify-content-center gap-1" title="Reset">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    </div>
                </form>

                <!-- FORM PENGATURAN TTD (AUTO-SAVE) -->
                <div class="collapse show mt-3 pt-3 border-top" id="collapsePengaturanTTD">
                    <div class="p-3 bg-light rounded-3 border">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small fw-bold text-secondary text-uppercase" style="letter-spacing: 0.03em;">
                                <i class="fa-solid fa-file-signature text-primary me-1"></i>Pejabat Penandatangan Dokumen Cetak:
                            </span>
                            <small class="text-muted" style="font-size: 0.72rem;">Tersimpan otomatis di browser</small>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary mb-1">Ketua STIKes Panti Waluya</label>
                                <input type="text" id="inputNamaKetua" class="form-control form-control-sm mb-1" placeholder="Nama Lengkap & Gelar Ketua" oninput="syncKIRSignatures()">
                                <input type="text" id="inputNipKetua" class="form-control form-control-sm font-monospace" placeholder="NIDN / NIP Ketua" oninput="syncKIRSignatures()">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary mb-1">Kepala Bagian Sarpras</label>
                                <input type="text" id="inputNamaKabag" class="form-control form-control-sm mb-1" placeholder="Nama Lengkap & Gelar Kabag Sarpras" oninput="syncKIRSignatures()">
                                <input type="text" id="inputNipKabag" class="form-control form-control-sm font-monospace" placeholder="NIK / NIP Kabag Sarpras" oninput="syncKIRSignatures()">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary mb-1">Tempat & Tanggal Dokumen</label>
                                <input type="text" id="inputTglKIR" class="form-control form-control-sm mb-1" value="Malang, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}" oninput="syncKIRSignatures()">
                                <small class="text-muted d-block" style="font-size: 0.72rem;">Format: <em>Kota, Tanggal Bulan Tahun</em></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Web Table Preview KIR -->
        <div class="card mb-4 no-print">
            <div class="card-header-modern d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">
                    <i class="fa-solid fa-boxes-stacked text-primary me-2"></i>
                    @if(request('ruangan_id'))
                        Data Aset Ruangan: {{ $ruangans->find(request('ruangan_id'))->nama_ruangan ?? '' }}
                    @else
                        Rekapitulasi Seluruh Aset Sarpras (Global)
                    @endif
                </h6>
                <span class="badge bg-light text-secondary border font-monospace">{{ $barangs->count() }} Item</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th>Kode Aset</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Lokasi Ruangan</th>
                            <th class="text-center">Jumlah</th>
                            <th>Kondisi</th>
                            <th>Tahun</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangs as $index => $b)
                        <tr>
                            <td class="ps-4 fw-semibold text-muted">{{ $index + 1 }}</td>
                            <td><span class="badge-code">{{ $b->kode_barang }}</span></td>
                            <td class="fw-bold text-dark">{{ $b->nama_barang }}</td>
                            <td><span class="badge-category">{{ $b->kategori->nama_kategori ?? '-' }}</span></td>
                            <td><span class="badge-room-code">{{ $b->ruangan->nama_ruangan ?? '-' }}</span></td>
                            <td class="text-center fw-bold font-monospace">{{ $b->jumlah }}</td>
                            <td>
                                @if($b->kondisi === 'Baik')
                                    <span class="badge bg-success-subtle text-success border border-success px-2.5 py-1 rounded-pill">Baik</span>
                                @elseif($b->kondisi === 'Rusak Ringan')
                                    <span class="badge bg-warning-subtle text-warning border border-warning px-2.5 py-1 rounded-pill">Rusak Ringan</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger px-2.5 py-1 rounded-pill">Rusak Berat</span>
                                @endif
                            </td>
                            <td class="text-muted font-monospace small">{{ $b->tahun_pengadaan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">Tidak ada data aset pada filter ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- ================= TAB 2: LAPORAN PEMINJAMAN ASET / BARANG ================= -->
    <div class="tab-pane fade {{ request('tab') == 'peminjaman-aset' ? 'show active' : '' }}" id="tab-pinjam-aset" role="tabpanel">
        
        <!-- Filter Form Peminjaman Aset -->
        <div class="card mb-4 no-print">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-filter text-primary"></i>
                    <span>Filter Laporan Peminjaman Aset / Barang</span>
                </h6>

                <form action="{{ route('laporan.index') }}" method="GET" class="row g-3 align-items-end" id="formFilterPinjamAset">
                    <input type="hidden" name="tab" value="peminjaman-aset">

                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-secondary">Tanggal Mulai</label>
                        <input type="date" name="tgl_mulai_aset" class="form-control" value="{{ request('tgl_mulai_aset') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-secondary">Tanggal Selesai</label>
                        <input type="date" name="tgl_selesai_aset" class="form-control" value="{{ request('tgl_selesai_aset') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-secondary">Status Peminjaman</label>
                        <select name="status_aset" class="form-select">
                            <option value="">-- Semua Status --</option>
                            <option value="Menunggu" {{ request('status_aset') == 'Menunggu' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                            <option value="Disetujui" {{ request('status_aset') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="Diambil" {{ request('status_aset') == 'Diambil' ? 'selected' : '' }}>Barang Diambil</option>
                            <option value="Kembali" {{ request('status_aset') == 'Kembali' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                            <option value="Terlambat" {{ request('status_aset') == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                            <option value="Ditolak" {{ request('status_aset') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                            <i class="fa-solid fa-magnifying-glass me-1"></i> Filter
                        </button>
                        <a href="{{ route('laporan.index') }}?tab=peminjaman-aset" class="btn btn-outline-secondary py-2" title="Reset Filter">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    </div>

                    <div class="col-12 pt-3 border-top d-flex gap-2 justify-content-end flex-wrap">
                        <!-- Export Excel -->
                        <a href="{{ route('laporan.peminjaman-aset.export', request()->all()) }}" class="btn btn-success fw-bold px-3 py-2 shadow-sm">
                            <i class="fa-solid fa-file-excel me-1.5"></i> Export Excel (.xlsx)
                        </a>
                        <!-- Print A4 -->
                        <button type="button" onclick="triggerPrintTab('peminjaman-aset')" class="btn btn-dark fw-bold px-3 py-2 shadow-sm">
                            <i class="fa-solid fa-print me-1.5"></i> Cetak Laporan Peminjaman Aset
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Web Table Preview Peminjaman Aset -->
        <div class="card mb-4 no-print">
            <div class="card-header-modern d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">
                    <i class="fa-solid fa-hand-holding-hand text-primary me-2"></i>
                    Rekapitulasi Peminjaman Aset Sarpras
                </h6>
                <span class="badge bg-light text-secondary border font-monospace">{{ $peminjamanAsets->count() }} Data</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th>Kode Pinjam</th>
                            <th>Pemohon & Identitas</th>
                            <th>Daftar Aset yang Dipinjam</th>
                            <th class="text-center">Jumlah</th>
                            <th>Jadwal Pinjam & Tenggat</th>
                            <th class="text-center">Status</th>
                            <th>Keperluan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamanAsets as $index => $pa)
                        <tr>
                            <td class="ps-4 fw-semibold text-muted">{{ $index + 1 }}</td>
                            <td><span class="badge-code">{{ $pa->kode_peminjaman }}</span></td>
                            <td>
                                <div class="fw-bold text-dark">{{ $pa->nama_peminjam }}</div>
                                <div class="text-muted small font-monospace">
                                    {{ $pa->nomor_identitas }} &bull; {{ $pa->prodi_unit ?? '-' }}
                                </div>
                            </td>
                            <td>
                                @if($pa->details && $pa->details->count() > 0)
                                    @foreach($pa->details as $d)
                                        <div class="small text-dark fw-semibold">&bull; {{ $d->barang->nama_barang ?? '-' }} ({{ $d->jumlah }} unit)</div>
                                    @endforeach
                                @else
                                    <div class="small text-dark fw-semibold">{{ $pa->barang->nama_barang ?? '-' }} ({{ $pa->jumlah }} unit)</div>
                                @endif
                            </td>
                            <td class="text-center fw-bold font-monospace">{{ $pa->jumlah }}</td>
                            <td>
                                <div class="small">
                                    <span class="text-muted">Pinjam:</span> {{ \Carbon\Carbon::parse($pa->tanggal_pinjam)->locale('id')->translatedFormat('d M Y') }}
                                </div>
                                <div class="small">
                                    <span class="text-muted">Tenggat:</span> <strong>{{ \Carbon\Carbon::parse($pa->tenggat_kembali)->locale('id')->translatedFormat('d M Y') }}</strong>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($pa->status === 'Menunggu')
                                    <span class="badge bg-warning text-dark border border-warning px-2.5 py-1 rounded-pill">Menunggu</span>
                                @elseif($pa->status === 'Disetujui')
                                    <span class="badge bg-primary text-white border border-primary px-2.5 py-1 rounded-pill">Disetujui</span>
                                @elseif($pa->status === 'Diambil')
                                    <span class="badge bg-info text-dark border border-info px-2.5 py-1 rounded-pill">Diambil</span>
                                @elseif($pa->status === 'Kembali')
                                    <span class="badge bg-success-subtle text-success border border-success px-2.5 py-1 rounded-pill">Kembali</span>
                                @elseif($pa->status === 'Ditolak')
                                    <span class="badge bg-danger text-white border border-danger px-2.5 py-1 rounded-pill">Ditolak</span>
                                @else
                                    <span class="badge bg-danger text-white border border-danger px-2.5 py-1 rounded-pill">Terlambat</span>
                                @endif
                            </td>
                            <td class="small text-muted text-truncate" style="max-width: 200px;" title="{{ $pa->keperluan }}">
                                {{ $pa->keperluan ?? '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">Belum ada data peminjaman aset pada periode filter ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- ================= TAB 3: LAPORAN PEMINJAMAN RUANGAN ================= -->
    <div class="tab-pane fade {{ request('tab') == 'peminjaman-ruangan' ? 'show active' : '' }}" id="tab-pinjam-ruangan" role="tabpanel">
        
        <!-- Filter Form Peminjaman Ruangan -->
        <div class="card mb-4 no-print">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-filter text-primary"></i>
                    <span>Filter Laporan Peminjaman / Booking Ruangan</span>
                </h6>

                <form action="{{ route('laporan.index') }}" method="GET" class="row g-3 align-items-end" id="formFilterPinjamRuangan">
                    <input type="hidden" name="tab" value="peminjaman-ruangan">

                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-secondary">Tanggal Mulai</label>
                        <input type="date" name="tgl_mulai_ruangan" class="form-control" value="{{ request('tgl_mulai_ruangan') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-secondary">Tanggal Selesai</label>
                        <input type="date" name="tgl_selesai_ruangan" class="form-control" value="{{ request('tgl_selesai_ruangan') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-secondary">Filter Ruangan</label>
                        <select name="ruangan_id_filter" class="form-select">
                            <option value="">-- Semua Ruangan --</option>
                            @foreach($ruangans as $r)
                                <option value="{{ $r->id }}" {{ request('ruangan_id_filter') == $r->id ? 'selected' : '' }}>
                                    {{ $r->nama_ruangan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-secondary">Status Booking</label>
                        <select name="status_ruangan" class="form-select">
                            <option value="">-- Semua Status --</option>
                            <option value="Menunggu" {{ request('status_ruangan') == 'Menunggu' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                            <option value="Disetujui" {{ request('status_ruangan') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="Digunakan" {{ request('status_ruangan') == 'Digunakan' ? 'selected' : '' }}>Sedang Digunakan</option>
                            <option value="Selesai" {{ request('status_ruangan') == 'Selesai' ? 'selected' : '' }}>Selesai Pemakaian</option>
                            <option value="Ditolak" {{ request('status_ruangan') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    <div class="col-12 d-flex gap-2 justify-content-between flex-wrap pt-3 border-top">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary fw-bold px-4 py-2 shadow-sm">
                                <i class="fa-solid fa-magnifying-glass me-1"></i> Terapkan Filter
                            </button>
                            <a href="{{ route('laporan.index') }}?tab=peminjaman-ruangan" class="btn btn-outline-secondary py-2 px-3" title="Reset Filter">
                                <i class="fa-solid fa-rotate-left"></i> Reset
                            </a>
                        </div>
                        <div class="d-flex gap-2">
                            <!-- Export Excel -->
                            <a href="{{ route('laporan.peminjaman-ruangan.export', request()->all()) }}" class="btn btn-success fw-bold px-3 py-2 shadow-sm">
                                <i class="fa-solid fa-file-excel me-1.5"></i> Export Excel (.xlsx)
                            </a>
                            <!-- Print A4 -->
                            <button type="button" onclick="triggerPrintTab('peminjaman-ruangan')" class="btn btn-dark fw-bold px-3 py-2 shadow-sm">
                                <i class="fa-solid fa-print me-1.5"></i> Cetak Laporan Booking Ruangan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Web Table Preview Peminjaman Ruangan -->
        <div class="card mb-4 no-print">
            <div class="card-header-modern d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">
                    <i class="fa-solid fa-calendar-check text-primary me-2"></i>
                    Rekapitulasi Peminjaman & Booking Ruangan Kegiatan
                </h6>
                <span class="badge bg-light text-secondary border font-monospace">{{ $peminjamanRuangans->count() }} Data</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th>Kode Booking</th>
                            <th>Pemohon & Identitas</th>
                            <th>Ruangan Kegiatan</th>
                            <th>Jadwal & Waktu Pemakaian</th>
                            <th class="text-center">Status</th>
                            <th>Keperluan Kegiatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamanRuangans as $index => $pr)
                        <tr>
                            <td class="ps-4 fw-semibold text-muted">{{ $index + 1 }}</td>
                            <td><span class="badge-code">{{ $pr->kode_booking }}</span></td>
                            <td>
                                <div class="fw-bold text-dark">{{ $pr->nama_peminjam }}</div>
                                <div class="text-muted small font-monospace">
                                    {{ $pr->nomor_identitas }} &bull; {{ $pr->prodi_unit ?? '-' }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $pr->ruangan->nama_ruangan ?? '-' }}</div>
                                <span class="badge-code py-0">{{ $pr->ruangan->kode_ruangan ?? '-' }}</span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark small">
                                    {{ \Carbon\Carbon::parse($pr->tanggal_pemakaian)->locale('id')->translatedFormat('l, d M Y') }}
                                </div>
                                <div class="small text-primary font-monospace mt-0.5">
                                    {{ date('H:i', strtotime($pr->jam_mulai)) }} - {{ date('H:i', strtotime($pr->jam_selesai)) }} WIB
                                </div>
                            </td>
                            <td class="text-center">
                                @if($pr->status === 'Menunggu')
                                    <span class="badge bg-warning text-dark border border-warning px-2.5 py-1 rounded-pill">Menunggu</span>
                                @elseif($pr->status === 'Disetujui')
                                    <span class="badge bg-primary text-white border border-primary px-2.5 py-1 rounded-pill">Disetujui</span>
                                @elseif($pr->status === 'Digunakan')
                                    <span class="badge bg-info text-dark border border-info px-2.5 py-1 rounded-pill">Sedang Digunakan</span>
                                @elseif($pr->status === 'Selesai')
                                    <span class="badge bg-success-subtle text-success border border-success px-2.5 py-1 rounded-pill">Selesai</span>
                                @elseif($pr->status === 'Ditolak')
                                    <span class="badge bg-danger text-white border border-danger px-2.5 py-1 rounded-pill">Ditolak</span>
                                @endif
                            </td>
                            <td class="small text-muted text-truncate" style="max-width: 220px;" title="{{ $pr->keperluan }}">
                                {{ $pr->keperluan ?? '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Belum ada data booking ruangan pada periode filter ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<!-- ========================================================================= -->
<!-- DOKUMEN CETAK KHUSUS PRINT A4 (TERSEMBUNYI SAAT TAMPILAN WEB)            -->
<!-- ========================================================================= -->

<!-- 1. DOKUMEN CETAK KIR A4 -->
<div id="printDocKIR" class="kir-print-document">
    <!-- KOP SURAT RESMI STIKES PANTI WALUYA MALANG -->
    <div class="d-flex align-items-center justify-content-between pb-2 mb-3" style="border-bottom: 3px double #000000; font-family: 'Times New Roman', Times, serif;">
        <div style="width: 85px;" class="text-center flex-shrink-0">
            <img src="{{ asset('images/logo-stikes.png') }}" alt="Logo STIKES" style="width: 78px; height: auto;" onerror="this.style.display='none'">
        </div>
        <div class="text-center flex-grow-1 px-2">
            <h4 class="fw-bold mb-0 text-uppercase" style="font-size: 1.3rem; letter-spacing: 0.03em; line-height: 1.15; color: #000; font-family: 'Times New Roman', Times, serif;">
                SEKOLAH TINGGI ILMU KESEHATAN
            </h4>
            <h3 class="fw-bold mb-1 text-uppercase" style="font-size: 1.55rem; letter-spacing: 0.04em; line-height: 1.15; color: #000; font-family: 'Times New Roman', Times, serif;">
                PANTI WALUYA MALANG
            </h3>
            <p class="mb-0 text-dark" style="font-size: 0.85rem; line-height: 1.25; font-family: 'Times New Roman', Times, serif;">
                Jalan Yulius Usman No. 62 Malang &ndash; 65117 Telp (0341) 369003 Fax. 368737
            </p>
            <p class="mb-0 text-dark" style="font-size: 0.85rem; line-height: 1.25; font-family: 'Times New Roman', Times, serif;">
                Email : <u>stikes.pantiwaluyamlg@gmail.com</u>, website : <u>www.stikespantiwaluya.ac.id</u>
            </p>
        </div>
        <div style="width: 85px;" class="flex-shrink-0"></div>
    </div>

    <div class="text-center mb-3">
        <h5 class="fw-bold text-uppercase mb-1 text-decoration-underline" style="font-family: 'Times New Roman', Times, serif; font-size: 1.1rem;">
            KARTU INVENTARIS RUANGAN (KIR)
        </h5>
        <div class="fw-semibold small text-uppercase" style="font-family: 'Times New Roman', Times, serif;">
            @if(request('ruangan_id'))
                LOKASI RUANGAN : {{ $ruangans->find(request('ruangan_id'))->nama_ruangan ?? '-' }} ({{ $ruangans->find(request('ruangan_id'))->kode_ruangan ?? '-' }})
            @else
                REKAPITULASI ASET SELURUH RUANGAN (GLOBAL)
            @endif
        </div>
    </div>

    <table class="table-kir-print">
        <thead>
            <tr>
                <th style="width: 30px;" class="text-center">NO</th>
                <th style="width: 95px;" class="text-center">KODE ASET</th>
                <th>NAMA BARANG / ASET</th>
                <th style="width: 100px;">KATEGORI</th>
                <th style="width: 100px;">RUANGAN</th>
                <th style="width: 45px;" class="text-center">JML</th>
                <th style="width: 75px;" class="text-center">KONDISI</th>
                <th style="width: 50px;" class="text-center">TAHUN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barangs as $index => $b)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center font-monospace">{{ $b->kode_barang }}</td>
                <td class="fw-bold">{{ $b->nama_barang }}</td>
                <td>{{ $b->kategori->nama_kategori ?? '-' }}</td>
                <td>{{ $b->ruangan->nama_ruangan ?? '-' }}</td>
                <td class="text-center fw-bold">{{ $b->jumlah }}</td>
                <td class="text-center">{{ $b->kondisi }}</td>
                <td class="text-center">{{ $b->tahun_pengadaan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tanda Tangan KIR -->
    <div class="kir-ttd-container mt-4 pt-2">
        <div class="d-flex justify-content-between align-items-start" style="font-family: 'Times New Roman', Times, serif;">
            <div class="text-center" style="width: 320px;">
                <p class="mb-0" style="font-size: 0.95rem;">Mengetahui,</p>
                <p class="fw-bold mb-0" style="font-size: 0.95rem;">Ketua STIKes Panti Waluya</p>
                <div style="height: 60px;"></div>
                <p class="fw-bold mb-0 text-decoration-underline print-nama-ketua" style="font-size: 0.95rem; white-space: nowrap;">apt. Wida Padminingsih, S.Farm., M.Farm.</p>
                <p class="mb-0 small font-monospace print-nip-ketua" style="font-size: 0.85rem;">NIDN. 0725048201</p>
            </div>
            <div class="text-center" style="width: 320px;">
                <p class="mb-0 print-tgl-dokumen" style="font-size: 0.95rem;">Malang, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</p>
                <p class="fw-bold mb-0" style="font-size: 0.95rem;">Kepala Bagian Sarana & Prasarana</p>
                <div style="height: 60px;"></div>
                <p class="fw-bold mb-0 text-decoration-underline print-nama-kabag" style="font-size: 0.95rem; white-space: nowrap;">Petrus Tobias, S.Kom.</p>
                <p class="mb-0 small font-monospace print-nip-kabag" style="font-size: 0.85rem;">NIK. 2021.08.045</p>
            </div>
        </div>
    </div>
</div>

<!-- 2. DOKUMEN CETAK LAPORAN PEMINJAMAN ASET A4 -->
<div id="printDocPinjamAset" class="kir-print-document">
    <!-- KOP SURAT RESMI STIKES PANTI WALUYA MALANG -->
    <div class="d-flex align-items-center justify-content-between pb-2 mb-3" style="border-bottom: 3px double #000000; font-family: 'Times New Roman', Times, serif;">
        <div style="width: 85px;" class="text-center flex-shrink-0">
            <img src="{{ asset('images/logo-stikes.png') }}" alt="Logo STIKES" style="width: 78px; height: auto;" onerror="this.style.display='none'">
        </div>
        <div class="text-center flex-grow-1 px-2">
            <h4 class="fw-bold mb-0 text-uppercase" style="font-size: 1.3rem; letter-spacing: 0.03em; line-height: 1.15; color: #000; font-family: 'Times New Roman', Times, serif;">
                SEKOLAH TINGGI ILMU KESEHATAN
            </h4>
            <h3 class="fw-bold mb-1 text-uppercase" style="font-size: 1.55rem; letter-spacing: 0.04em; line-height: 1.15; color: #000; font-family: 'Times New Roman', Times, serif;">
                PANTI WALUYA MALANG
            </h3>
            <p class="mb-0 text-dark" style="font-size: 0.85rem; line-height: 1.25; font-family: 'Times New Roman', Times, serif;">
                Jalan Yulius Usman No. 62 Malang &ndash; 65117 Telp (0341) 369003 Fax. 368737
            </p>
            <p class="mb-0 text-dark" style="font-size: 0.85rem; line-height: 1.25; font-family: 'Times New Roman', Times, serif;">
                Email : <u>stikes.pantiwaluyamlg@gmail.com</u>, website : <u>www.stikespantiwaluya.ac.id</u>
            </p>
        </div>
        <div style="width: 85px;" class="flex-shrink-0"></div>
    </div>

    <div class="text-center mb-3">
        <h5 class="fw-bold text-uppercase mb-1 text-decoration-underline" style="font-family: 'Times New Roman', Times, serif; font-size: 1.1rem;">
            LAPORAN REKAPITULASI PEMINJAMAN ASET & BARANG
        </h5>
        <div class="fw-semibold small" style="font-family: 'Times New Roman', Times, serif;">
            Periode: 
            @if(request('tgl_mulai_aset') && request('tgl_selesai_aset'))
                {{ date('d/m/Y', strtotime(request('tgl_mulai_aset'))) }} s/d {{ date('d/m/Y', strtotime(request('tgl_selesai_aset'))) }}
            @else
                Semua Periode Transaksi
            @endif
            @if(request('status_aset'))
                | Status: {{ request('status_aset') }}
            @endif
        </div>
    </div>

    <table class="table-kir-print">
        <thead>
            <tr>
                <th style="width: 30px;" class="text-center">NO</th>
                <th style="width: 90px;" class="text-center">KODE</th>
                <th>NAMA PEMINJAM / IDENTITAS</th>
                <th>DAFTAR ASET YANG DIPINJAM</th>
                <th style="width: 40px;" class="text-center">JML</th>
                <th style="width: 75px;" class="text-center">TGL PINJAM</th>
                <th style="width: 75px;" class="text-center">TENGGAT</th>
                <th style="width: 70px;" class="text-center">STATUS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjamanAsets as $index => $pa)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center font-monospace">{{ $pa->kode_peminjaman }}</td>
                <td>
                    <div class="fw-bold">{{ $pa->nama_peminjam }}</div>
                    <small class="text-muted">{{ $pa->nomor_identitas }} - {{ $pa->prodi_unit ?? '-' }}</small>
                </td>
                <td>
                    @if($pa->details && $pa->details->count() > 0)
                        @foreach($pa->details as $d)
                            <div>&bull; {{ $d->barang->nama_barang ?? '-' }} ({{ $d->jumlah }} unit)</div>
                        @endforeach
                    @else
                        <div>{{ $pa->barang->nama_barang ?? '-' }} ({{ $pa->jumlah }} unit)</div>
                    @endif
                </td>
                <td class="text-center fw-bold">{{ $pa->jumlah }}</td>
                <td class="text-center">{{ date('d/m/Y', strtotime($pa->tanggal_pinjam)) }}</td>
                <td class="text-center">{{ date('d/m/Y', strtotime($pa->tenggat_kembali)) }}</td>
                <td class="text-center fw-bold">{{ $pa->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tanda Tangan Laporan Aset -->
    <div class="kir-ttd-container mt-4 pt-2">
        <div class="d-flex justify-content-between align-items-start" style="font-family: 'Times New Roman', Times, serif;">
            <div class="text-center" style="width: 320px;">
                <p class="mb-0" style="font-size: 0.95rem;">Mengetahui,</p>
                <p class="fw-bold mb-0" style="font-size: 0.95rem;">Ketua STIKes Panti Waluya</p>
                <div style="height: 60px;"></div>
                <p class="fw-bold mb-0 text-decoration-underline print-nama-ketua" style="font-size: 0.95rem; white-space: nowrap;">apt. Wida Padminingsih, S.Farm., M.Farm.</p>
                <p class="mb-0 small font-monospace print-nip-ketua" style="font-size: 0.85rem;">NIDN. 0725048201</p>
            </div>
            <div class="text-center" style="width: 320px;">
                <p class="mb-0 print-tgl-dokumen" style="font-size: 0.95rem;">Malang, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</p>
                <p class="fw-bold mb-0" style="font-size: 0.95rem;">Kepala Bagian Sarana & Prasarana</p>
                <div style="height: 60px;"></div>
                <p class="fw-bold mb-0 text-decoration-underline print-nama-kabag" style="font-size: 0.95rem; white-space: nowrap;">Petrus Tobias, S.Kom.</p>
                <p class="mb-0 small font-monospace print-nip-kabag" style="font-size: 0.85rem;">NIK. 2021.08.045</p>
            </div>
        </div>
    </div>
</div>

<!-- 3. DOKUMEN CETAK LAPORAN PEMINJAMAN RUANGAN A4 -->
<div id="printDocPinjamRuangan" class="kir-print-document">
    <!-- KOP SURAT RESMI STIKES PANTI WALUYA MALANG -->
    <div class="d-flex align-items-center justify-content-between pb-2 mb-3" style="border-bottom: 3px double #000000; font-family: 'Times New Roman', Times, serif;">
        <div style="width: 85px;" class="text-center flex-shrink-0">
            <img src="{{ asset('images/logo-stikes.png') }}" alt="Logo STIKES" style="width: 78px; height: auto;" onerror="this.style.display='none'">
        </div>
        <div class="text-center flex-grow-1 px-2">
            <h4 class="fw-bold mb-0 text-uppercase" style="font-size: 1.3rem; letter-spacing: 0.03em; line-height: 1.15; color: #000; font-family: 'Times New Roman', Times, serif;">
                SEKOLAH TINGGI ILMU KESEHATAN
            </h4>
            <h3 class="fw-bold mb-1 text-uppercase" style="font-size: 1.55rem; letter-spacing: 0.04em; line-height: 1.15; color: #000; font-family: 'Times New Roman', Times, serif;">
                PANTI WALUYA MALANG
            </h3>
            <p class="mb-0 text-dark" style="font-size: 0.85rem; line-height: 1.25; font-family: 'Times New Roman', Times, serif;">
                Jalan Yulius Usman No. 62 Malang &ndash; 65117 Telp (0341) 369003 Fax. 368737
            </p>
            <p class="mb-0 text-dark" style="font-size: 0.85rem; line-height: 1.25; font-family: 'Times New Roman', Times, serif;">
                Email : <u>stikes.pantiwaluyamlg@gmail.com</u>, website : <u>www.stikespantiwaluya.ac.id</u>
            </p>
        </div>
        <div style="width: 85px;" class="flex-shrink-0"></div>
    </div>

    <div class="text-center mb-3">
        <h5 class="fw-bold text-uppercase mb-1 text-decoration-underline" style="font-family: 'Times New Roman', Times, serif; font-size: 1.1rem;">
            LAPORAN REKAPITULASI PEMINJAMAN & BOOKING RUANGAN
        </h5>
        <div class="fw-semibold small" style="font-family: 'Times New Roman', Times, serif;">
            Periode: 
            @if(request('tgl_mulai_ruangan') && request('tgl_selesai_ruangan'))
                {{ date('d/m/Y', strtotime(request('tgl_mulai_ruangan'))) }} s/d {{ date('d/m/Y', strtotime(request('tgl_selesai_ruangan'))) }}
            @else
                Semua Periode Booking
            @endif
            @if(request('ruangan_id_filter'))
                | Ruangan: {{ $ruangans->find(request('ruangan_id_filter'))->nama_ruangan ?? '-' }}
            @endif
            @if(request('status_ruangan'))
                | Status: {{ request('status_ruangan') }}
            @endif
        </div>
    </div>

    <table class="table-kir-print">
        <thead>
            <tr>
                <th style="width: 30px;" class="text-center">NO</th>
                <th style="width: 95px;" class="text-center">KODE</th>
                <th>NAMA PEMOHON / IDENTITAS</th>
                <th>RUANGAN KEGIATAN</th>
                <th style="width: 85px;" class="text-center">TGL PAKAI</th>
                <th style="width: 85px;" class="text-center">JAM KEGIATAN</th>
                <th style="width: 70px;" class="text-center">STATUS</th>
                <th>KEPERLUAN ACARA</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjamanRuangans as $index => $pr)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center font-monospace">{{ $pr->kode_booking }}</td>
                <td>
                    <div class="fw-bold">{{ $pr->nama_peminjam }}</div>
                    <small class="text-muted">{{ $pr->nomor_identitas }} - {{ $pr->prodi_unit ?? '-' }}</small>
                </td>
                <td>
                    <div class="fw-bold">{{ $pr->ruangan->nama_ruangan ?? '-' }}</div>
                    <small class="font-monospace text-muted">{{ $pr->ruangan->kode_ruangan ?? '-' }}</small>
                </td>
                <td class="text-center">{{ date('d/m/Y', strtotime($pr->tanggal_pemakaian)) }}</td>
                <td class="text-center">{{ date('H:i', strtotime($pr->jam_mulai)) }} - {{ date('H:i', strtotime($pr->jam_selesai)) }}</td>
                <td class="text-center fw-bold">{{ $pr->status }}</td>
                <td>{{ $pr->keperluan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tanda Tangan Laporan Ruangan -->
    <div class="kir-ttd-container mt-4 pt-2">
        <div class="d-flex justify-content-between align-items-start" style="font-family: 'Times New Roman', Times, serif;">
            <div class="text-center" style="width: 320px;">
                <p class="mb-0" style="font-size: 0.95rem;">Mengetahui,</p>
                <p class="fw-bold mb-0" style="font-size: 0.95rem;">Ketua STIKes Panti Waluya</p>
                <div style="height: 60px;"></div>
                <p class="fw-bold mb-0 text-decoration-underline print-nama-ketua" style="font-size: 0.95rem; white-space: nowrap;">apt. Wida Padminingsih, S.Farm., M.Farm.</p>
                <p class="mb-0 small font-monospace print-nip-ketua" style="font-size: 0.85rem;">NIDN. 0725048201</p>
            </div>
            <div class="text-center" style="width: 320px;">
                <p class="mb-0 print-tgl-dokumen" style="font-size: 0.95rem;">Malang, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</p>
                <p class="fw-bold mb-0" style="font-size: 0.95rem;">Kepala Bagian Sarana & Prasarana</p>
                <div style="height: 60px;"></div>
                <p class="fw-bold mb-0 text-decoration-underline print-nama-kabag" style="font-size: 0.95rem; white-space: nowrap;">Petrus Tobias, S.Kom.</p>
                <p class="mb-0 small font-monospace print-nip-kabag" style="font-size: 0.85rem;">NIK. 2021.08.045</p>
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS Tampilan Web */
    .kir-print-document {
        display: none;
    }

    /* CSS Khusus Cetak Print A4 */
    @media print {
        @page {
            size: A4 portrait;
            margin: 10mm 12mm;
        }
        .page-web-header, .navbar-custom, .no-print, .d-print-none, .card-stat, form, .modal, .footer-custom, .alert, .nav-pills, .card {
            display: none !important;
        }
        .kir-print-document {
            display: none !important;
        }
        body.printing-kir #printDocKIR {
            display: block !important;
            width: 100%;
            color: #000000 !important;
            background: #ffffff !important;
        }
        body.printing-peminjaman-aset #printDocPinjamAset {
            display: block !important;
            width: 100%;
            color: #000000 !important;
            background: #ffffff !important;
        }
        body.printing-peminjaman-ruangan #printDocPinjamRuangan {
            display: block !important;
            width: 100%;
            color: #000000 !important;
            background: #ffffff !important;
        }
        .table-kir-print {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8.5pt;
            margin-bottom: 15px;
        }
        .table-kir-print th, .table-kir-print td {
            border: 1px solid #000000;
            padding: 5px 6px;
        }
        .table-kir-print th {
            background-color: #f0f0f0 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            font-weight: bold;
            text-align: center;
        }
        .kir-ttd-container {
            page-break-inside: avoid;
            margin-top: 20px;
        }
    }
</style>

<script>
    let currentPrintTarget = 'kir';

    function setLaporanTab(tabName) {
        currentPrintTarget = tabName;
        let url = new URL(window.location);
        url.searchParams.set('tab', tabName);
        window.history.pushState({}, '', url);
    }

    function triggerPrintTab(target) {
        currentPrintTarget = target;
        
        document.body.classList.remove('printing-kir', 'printing-peminjaman-aset', 'printing-peminjaman-ruangan');
        
        if (target === 'kir') {
            document.body.classList.add('printing-kir');
        } else if (target === 'peminjaman-aset') {
            document.body.classList.add('printing-peminjaman-aset');
        } else if (target === 'peminjaman-ruangan') {
            document.body.classList.add('printing-peminjaman-ruangan');
        }

        setTimeout(function() {
            window.print();
        }, 150);
    }

    window.addEventListener('afterprint', function() {
        document.body.classList.remove('printing-kir', 'printing-peminjaman-aset', 'printing-peminjaman-ruangan');
    });

    // Auto-save dan sinkronisasi penandatangan
    const DEFAULT_KETUA_NAMA = 'apt. Wida Padminingsih, S.Farm., M.Farm.';
    const DEFAULT_KETUA_NIP  = 'NIDN. 0725048201';
    const DEFAULT_KABAG_NAMA = 'Petrus Tobias, S.Kom.';
    const DEFAULT_KABAG_NIP  = 'NIK. 2021.08.045';

    function loadKIRSignatures() {
        let savedKetuaNama = localStorage.getItem('stikes_kir_ketua_nama') || DEFAULT_KETUA_NAMA;
        let savedKetuaNip  = localStorage.getItem('stikes_kir_ketua_nip') || DEFAULT_KETUA_NIP;
        let savedKabagNama = localStorage.getItem('stikes_kir_kabag_nama') || DEFAULT_KABAG_NAMA;
        let savedKabagNip  = localStorage.getItem('stikes_kir_kabag_nip') || DEFAULT_KABAG_NIP;
        let savedTgl       = localStorage.getItem('stikes_kir_tgl_dokumen') || document.getElementById('inputTglKIR').value;

        document.getElementById('inputNamaKetua').value = savedKetuaNama;
        document.getElementById('inputNipKetua').value  = savedKetuaNip;
        document.getElementById('inputNamaKabag').value = savedKabagNama;
        document.getElementById('inputNipKabag').value  = savedKabagNip;
        document.getElementById('inputTglKIR').value    = savedTgl;

        applySignaturesToPrint(savedKetuaNama, savedKetuaNip, savedKabagNama, savedKabagNip, savedTgl);
    }

    function syncKIRSignatures() {
        let ketuaNama = document.getElementById('inputNamaKetua').value || DEFAULT_KETUA_NAMA;
        let ketuaNip  = document.getElementById('inputNipKetua').value || DEFAULT_KETUA_NIP;
        let kabagNama = document.getElementById('inputNamaKabag').value || DEFAULT_KABAG_NAMA;
        let kabagNip  = document.getElementById('inputNipKabag').value || DEFAULT_KABAG_NIP;
        let tgl       = document.getElementById('inputTglKIR').value;

        localStorage.setItem('stikes_kir_ketua_nama', ketuaNama);
        localStorage.setItem('stikes_kir_ketua_nip', ketuaNip);
        localStorage.setItem('stikes_kir_kabag_nama', kabagNama);
        localStorage.setItem('stikes_kir_kabag_nip', kabagNip);
        localStorage.setItem('stikes_kir_tgl_dokumen', tgl);

        applySignaturesToPrint(ketuaNama, ketuaNip, kabagNama, kabagNip, tgl);
    }

    function applySignaturesToPrint(ketuaNama, ketuaNip, kabagNama, kabagNip, tgl) {
        document.querySelectorAll('.print-nama-ketua').forEach(el => el.innerText = ketuaNama);
        document.querySelectorAll('.print-nip-ketua').forEach(el => el.innerText = ketuaNip);
        document.querySelectorAll('.print-nama-kabag').forEach(el => el.innerText = kabagNama);
        document.querySelectorAll('.print-nip-kabag').forEach(el => el.innerText = kabagNip);
        document.querySelectorAll('.print-tgl-dokumen').forEach(el => el.innerText = tgl);
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadKIRSignatures();

        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab');
        if (activeTab === 'peminjaman-aset') {
            let btn = document.getElementById('tab-pinjam-aset-btn');
            if (btn) btn.click();
        } else if (activeTab === 'peminjaman-ruangan') {
            let btn = document.getElementById('tab-pinjam-ruangan-btn');
            if (btn) btn.click();
        }
    });
</script>
@endsection