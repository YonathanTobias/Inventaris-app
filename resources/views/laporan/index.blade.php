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
        <div class="card border-0 shadow-sm rounded-4 h-100 p-3 card-stat-modern">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold small text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.04em;">Aset Terdaftar</span>
                    <div class="fs-3 fw-bold text-dark mt-1">{{ number_format($stats['total_aset_tercatat']) }}</div>
                    <div class="text-muted small mt-1 d-flex align-items-center gap-1.5" style="font-size: 0.78rem; font-weight: 500;">
                        <i class="fa-solid fa-boxes-stacked text-primary"></i>
                        <span><strong>{{ number_format($stats['total_unit_tercatat']) }}</strong> Unit Fisik</span>
                    </div>
                </div>
                <div class="rounded-4 p-3 d-flex align-items-center justify-content-center bg-primary-subtle text-primary shadow-xs" style="width: 50px; height: 50px; font-size: 1.35rem;">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 p-3 card-stat-modern">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold small text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.04em;">Ruangan Terdata</span>
                    <div class="fs-3 fw-bold text-success mt-1">{{ number_format($stats['total_ruangan']) }}</div>
                    <div class="text-muted small mt-1 d-flex align-items-center gap-1.5" style="font-size: 0.78rem; font-weight: 500;">
                        <i class="fa-solid fa-door-open text-success"></i>
                        <span>Titik Lokasi KIR</span>
                    </div>
                </div>
                <div class="rounded-4 p-3 d-flex align-items-center justify-content-center bg-success-subtle text-success shadow-xs" style="width: 50px; height: 50px; font-size: 1.35rem;">
                    <i class="fa-solid fa-door-open"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 p-3 card-stat-modern">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold small text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.04em;">Peminjaman Aset</span>
                    <div class="fs-3 fw-bold text-info mt-1">{{ number_format($stats['total_pinjam_aset']) }}</div>
                    <div class="text-muted small mt-1 d-flex align-items-center gap-1.5" style="font-size: 0.78rem; font-weight: 500;">
                        <i class="fa-solid fa-hand-holding-hand text-info"></i>
                        <span>Data Transaksi</span>
                    </div>
                </div>
                <div class="rounded-4 p-3 d-flex align-items-center justify-content-center bg-info-subtle text-info shadow-xs" style="width: 50px; height: 50px; font-size: 1.35rem;">
                    <i class="fa-solid fa-hand-holding-hand"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 p-3 card-stat-modern">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold small text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.04em;">Booking Ruangan</span>
                    <div class="fs-3 fw-bold text-warning mt-1">{{ number_format($stats['total_pinjam_ruangan']) }}</div>
                    <div class="text-muted small mt-1 d-flex align-items-center gap-1.5" style="font-size: 0.78rem; font-weight: 500;">
                        <i class="fa-solid fa-calendar-check text-warning"></i>
                        <span>Jadwal Kegiatan</span>
                    </div>
                </div>
                <div class="rounded-4 p-3 d-flex align-items-center justify-content-center bg-warning-subtle text-warning shadow-xs" style="width: 50px; height: 50px; font-size: 1.35rem;">
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
        
        <!-- TOOLBAR TERPADU (Filter, Search & Action Group) -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 no-print">
            <div class="card-body p-3">
                <div class="row g-2.5 align-items-center">
                    <!-- Sisi Kiri: Filter Ruangan & Search Bar -->
                    <div class="col-lg-6 col-12">
                        <div class="row g-2">
                            <div class="col-md-6 col-12">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-door-open"></i></span>
                                    <select name="ruangan_id" class="form-select border-start-0 ps-0" onchange="window.location.href='{{ route('laporan.index') }}?tab=kir&ruangan_id=' + this.value">
                                        <option value="">-- SEMUA RUANGAN (REKAP GLOBAL) --</option>
                                        @foreach($ruangans as $r)
                                            <option value="{{ $r->id }}" {{ request('ruangan_id') == $r->id ? 'selected' : '' }}>
                                                KIR - {{ $r->nama_ruangan }} ({{ $r->kode_ruangan }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                                    <input type="text" id="tableSearchKIR" class="form-control border-start-0 ps-0" placeholder="Cari nama aset, kode, kategori..." oninput="onKIRTableFilter()">
                                    <button class="btn btn-outline-secondary border-start-0 text-muted d-none" type="button" id="btnClearSearchKIR" onclick="clearKIRSearch()" title="Hapus pencarian"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sisi Kanan: Atur Penandatangan, Cetak Dropdown, Export Excel, Reset -->
                    <div class="col-lg-6 col-12 d-flex gap-2 justify-content-lg-end flex-wrap align-items-center">
                        <!-- Tombol Atur Penandatangan (Modal Trigger) -->
                        <button type="button" class="btn btn-outline-primary fw-semibold px-3 py-2 rounded-3 d-inline-flex align-items-center gap-1.5" data-bs-toggle="modal" data-bs-target="#modalPengaturanTTD">
                            <i class="fa-solid fa-pen-nib"></i>
                            <span>Atur Penandatangan</span>
                        </button>

                        <!-- Dropdown Aksi Cetak Bertingkat -->
                        <div class="btn-group">
                            <button type="button" onclick="triggerPrintTab('kir')" class="btn btn-dark fw-bold px-3 py-2 rounded-start-3 d-inline-flex align-items-center gap-1.5 shadow-sm">
                                <i class="fa-solid fa-print"></i>
                                <span>Cetak KIR</span>
                            </button>
                            <button type="button" class="btn btn-dark dropdown-toggle dropdown-toggle-split px-2.5 rounded-end-3 shadow-sm" data-bs-toggle="dropdown" aria-expanded="false" title="Pilihan Cetak">
                                <span class="visually-hidden">Toggle Cetak</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg rounded-3 py-2 border-0 mt-1">
                                <li>
                                    <button type="button" class="dropdown-item py-2 px-3 d-flex align-items-center gap-2.5" onclick="triggerPrintTab('kir')">
                                        <div class="p-1.5 rounded-2 bg-primary-subtle text-primary"><i class="fa-solid fa-file-lines"></i></div>
                                        <div>
                                            <div class="fw-semibold text-dark">Cetak Lembar KIR (A4)</div>
                                            <small class="text-muted">Format resmi bertandatangan untuk ruangan</small>
                                        </div>
                                    </button>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    @if(request('ruangan_id'))
                                        <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2.5" href="{{ route('ruangan.label', request('ruangan_id')) }}" target="_blank">
                                            <div class="p-1.5 rounded-2 bg-secondary-subtle text-secondary"><i class="fa-solid fa-barcode"></i></div>
                                            <div>
                                                <div class="fw-semibold text-dark">Cetak Label QR Ruangan Ini</div>
                                                <small class="text-muted">Stiker barcode aset khusus ruangan terpilih</small>
                                            </div>
                                        </a>
                                    @else
                                        <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2.5" href="{{ route('barang.label.massal') }}" target="_blank">
                                            <div class="p-1.5 rounded-2 bg-secondary-subtle text-secondary"><i class="fa-solid fa-barcode"></i></div>
                                            <div>
                                                <div class="fw-semibold text-dark">Cetak Label Stiker Global</div>
                                                <small class="text-muted">Cetak barcode seluruh aset terdaftar</small>
                                            </div>
                                        </a>
                                    @endif
                                </li>
                            </ul>
                        </div>

                        <!-- Export Excel -->
                        <a href="{{ route('laporan.export', request()->query()) }}" class="btn btn-success fw-bold px-3 py-2 rounded-3 d-inline-flex align-items-center gap-1.5 shadow-sm">
                            <i class="fa-solid fa-file-excel"></i>
                            <span>Export Excel</span>
                        </a>

                        <!-- Reset -->
                        <a href="{{ route('laporan.index') }}?tab=kir" class="btn btn-light border text-muted px-2.5 py-2 rounded-3" title="Reset Filter">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Data Aset Preview KIR -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 no-print overflow-hidden">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="p-1.5 rounded-2 bg-primary-subtle text-primary"><i class="fa-solid fa-boxes-stacked"></i></span>
                    <h6 class="fw-bold mb-0 text-dark">
                        @if(request('ruangan_id'))
                            Data Aset Ruangan: {{ $ruangans->find(request('ruangan_id'))->nama_ruangan ?? '' }}
                        @else
                            Rekapitulasi Seluruh Aset Sarpras (Global)
                        @endif
                    </h6>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center gap-1.5 text-muted small">
                        <span>Tampilkan:</span>
                        <select id="kirPageSizeSelect" class="form-select form-select-sm py-1 px-2" style="width: 75px;" onchange="changeKIRPageSize(this.value)">
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="-1">Semua</option>
                        </select>
                    </div>
                    <span class="badge bg-light text-secondary border font-monospace px-2.5 py-1.5 rounded-pill" id="badgeTotalItemsKIR">{{ $barangs->count() }} Total Aset</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tableKIR">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="text-center" style="width: 55px;" role="button" onclick="sortKIRTable('no')">
                                <span>No</span> <i class="fa-solid fa-sort small ms-0.5 text-muted" id="sortIcon-no"></i>
                            </th>
                            <th class="text-start ps-3" style="width: 140px;" role="button" onclick="sortKIRTable('kode')">
                                <span>Kode Aset</span> <i class="fa-solid fa-sort small ms-0.5 text-muted" id="sortIcon-kode"></i>
                            </th>
                            <th class="text-start" role="button" onclick="sortKIRTable('nama')">
                                <span>Nama Barang</span> <i class="fa-solid fa-sort small ms-0.5 text-muted" id="sortIcon-nama"></i>
                            </th>
                            <th class="text-start" role="button" onclick="sortKIRTable('kategori')">
                                <span>Kategori</span> <i class="fa-solid fa-sort small ms-0.5 text-muted" id="sortIcon-kategori"></i>
                            </th>
                            <th class="text-start" role="button" onclick="sortKIRTable('ruangan')">
                                <span>Lokasi Ruangan</span> <i class="fa-solid fa-sort small ms-0.5 text-muted" id="sortIcon-ruangan"></i>
                            </th>
                            <th class="text-end pe-4" style="width: 95px;" role="button" onclick="sortKIRTable('jumlah')">
                                <span>Jumlah</span> <i class="fa-solid fa-sort small ms-0.5 text-muted" id="sortIcon-jumlah"></i>
                            </th>
                            <th class="text-center" style="width: 130px;" role="button" onclick="sortKIRTable('kondisi')">
                                <span>Kondisi</span> <i class="fa-solid fa-sort small ms-0.5 text-muted" id="sortIcon-kondisi"></i>
                            </th>
                            <th class="text-center" style="width: 85px;" role="button" onclick="sortKIRTable('tahun')">
                                <span>Tahun</span> <i class="fa-solid fa-sort small ms-0.5 text-muted" id="sortIcon-tahun"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tbodyKIR">
                        @forelse($barangs as $index => $b)
                        <tr class="kir-row-item" 
                            data-no="{{ $index + 1 }}" 
                            data-kode="{{ strtolower($b->kode_barang) }}" 
                            data-nama="{{ strtolower($b->nama_barang) }}" 
                            data-kategori="{{ strtolower($b->kategori->nama_kategori ?? '') }}" 
                            data-ruangan="{{ strtolower($b->ruangan->nama_ruangan ?? '') }}" 
                            data-jumlah="{{ $b->jumlah }}" 
                            data-kondisi="{{ $b->kondisi }}" 
                            data-tahun="{{ $b->tahun_pengadaan ?? 0 }}">
                            <td class="text-center fw-semibold text-muted col-no">{{ $index + 1 }}</td>
                            <td class="text-start ps-3"><span class="badge-code">{{ $b->kode_barang }}</span></td>
                            <td class="text-start fw-bold text-dark">{{ $b->nama_barang }}</td>
                            <td class="text-start"><span class="badge-category">{{ $b->kategori->nama_kategori ?? '-' }}</span></td>
                            <td class="text-start"><span class="badge-room-code">{{ $b->ruangan->nama_ruangan ?? '-' }}</span></td>
                            <td class="text-end pe-4 fw-bold font-monospace text-dark" style="font-size: 0.95rem;">{{ $b->jumlah }}</td>
                            <td class="text-center">
                                @if($b->kondisi === 'Baik')
                                    <span class="badge-kondisi-status kondisi-baik">
                                        <i class="fa-solid fa-circle-check"></i> Baik
                                    </span>
                                @elseif($b->kondisi === 'Rusak Ringan')
                                    <span class="badge-kondisi-status kondisi-ringan">
                                        <i class="fa-solid fa-triangle-exclamation"></i> Rusak Ringan
                                    </span>
                                @else
                                    <span class="badge-kondisi-status kondisi-berat">
                                        <i class="fa-solid fa-circle-xmark"></i> Rusak Berat
                                    </span>
                                @endif
                            </td>
                            <td class="text-center text-muted font-monospace small">{{ $b->tahun_pengadaan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr id="emptyRowKIR">
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-inbox fs-2 d-block mb-2 text-secondary opacity-50"></i>
                                Tidak ada data aset pada filter ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer Pagination & Count Info -->
            <div class="card-footer bg-white border-top py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="text-muted small" id="kirPaginationInfo">
                    Menampilkan <span class="fw-bold text-dark" id="kirShowingStart">1</span> &ndash; <span class="fw-bold text-dark" id="kirShowingEnd">{{ min(10, $barangs->count()) }}</span> dari <span class="fw-bold text-dark" id="kirTotalFiltered">{{ $barangs->count() }}</span> data aset
                </div>
                <nav aria-label="Navigasi Halaman KIR" id="kirPaginationNav">
                    <ul class="pagination pagination-sm mb-0 gap-1" id="kirPaginationList">
                        <!-- Generates via JS -->
                    </ul>
                </nav>
            </div>
        </div>

    </div>

    <!-- ================= TAB 2: LAPORAN PEMINJAMAN ASET / BARANG ================= -->
    <div class="tab-pane fade {{ request('tab') == 'peminjaman-aset' ? 'show active' : '' }}" id="tab-pinjam-aset" role="tabpanel">
        
        <!-- Filter Form Peminjaman Aset -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 no-print">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-filter text-primary"></i>
                        <span>Filter Periode & Status Peminjaman Aset</span>
                    </h6>
                    <button type="button" class="btn btn-sm btn-outline-primary fw-semibold rounded-3 px-3" data-bs-toggle="modal" data-bs-target="#modalPengaturanTTD">
                        <i class="fa-solid fa-pen-nib me-1"></i> Atur Penandatangan
                    </button>
                </div>

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
                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm rounded-3">
                            <i class="fa-solid fa-magnifying-glass me-1"></i> Terapkan
                        </button>
                        <a href="{{ route('laporan.index') }}?tab=peminjaman-aset" class="btn btn-outline-secondary py-2 rounded-3" title="Reset Filter">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    </div>

                    <div class="col-12 pt-3 border-top d-flex gap-2 justify-content-end flex-wrap">
                        <!-- Export Excel -->
                        <a href="{{ route('laporan.peminjaman-aset.export', request()->all()) }}" class="btn btn-success fw-bold px-3.5 py-2 shadow-sm rounded-3 d-inline-flex align-items-center gap-1.5">
                            <i class="fa-solid fa-file-excel"></i>
                            <span>Export Excel (.xlsx)</span>
                        </a>
                        <!-- Print A4 -->
                        <button type="button" onclick="triggerPrintTab('peminjaman-aset')" class="btn btn-dark fw-bold px-3.5 py-2 shadow-sm rounded-3 d-inline-flex align-items-center gap-1.5">
                            <i class="fa-solid fa-print"></i>
                            <span>Cetak Laporan Peminjaman Aset</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Web Table Preview Peminjaman Aset -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 no-print overflow-hidden">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="p-1.5 rounded-2 bg-info-subtle text-info"><i class="fa-solid fa-hand-holding-hand"></i></span>
                    <h6 class="fw-bold mb-0 text-dark">Rekapitulasi Peminjaman Aset Sarpras</h6>
                </div>
                <span class="badge bg-light text-secondary border font-monospace px-2.5 py-1.5 rounded-pill">{{ $peminjamanAsets->count() }} Data Transaksi</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="text-center" style="width: 55px;">No</th>
                            <th class="text-start ps-3" style="width: 140px;">Kode Pinjam</th>
                            <th class="text-start">Pemohon & Identitas</th>
                            <th class="text-start">Daftar Aset yang Dipinjam</th>
                            <th class="text-end pe-4" style="width: 95px;">Jumlah</th>
                            <th class="text-start">Jadwal Pinjam & Tenggat</th>
                            <th class="text-center" style="width: 130px;">Status</th>
                            <th class="text-start">Keperluan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamanAsets as $index => $pa)
                        <tr>
                            <td class="text-center fw-semibold text-muted">{{ $index + 1 }}</td>
                            <td class="text-start ps-3"><span class="badge-code">{{ $pa->kode_peminjaman }}</span></td>
                            <td class="text-start">
                                <div class="fw-bold text-dark">{{ $pa->nama_peminjam }}</div>
                                <div class="text-muted small font-monospace">
                                    {{ $pa->nomor_identitas }} &bull; {{ $pa->prodi_unit ?? '-' }}
                                </div>
                            </td>
                            <td class="text-start">
                                @if($pa->details && $pa->details->count() > 0)
                                    @foreach($pa->details as $d)
                                        <div class="small text-dark fw-semibold">&bull; {{ $d->barang->nama_barang ?? '-' }} ({{ $d->jumlah }} unit)</div>
                                    @endforeach
                                @else
                                    <div class="small text-dark fw-semibold">{{ $pa->barang->nama_barang ?? '-' }} ({{ $pa->jumlah }} unit)</div>
                                @endif
                            </td>
                            <td class="text-end pe-4 fw-bold font-monospace text-dark">{{ $pa->jumlah }}</td>
                            <td class="text-start">
                                <div class="small">
                                    <span class="text-muted">Pinjam:</span> {{ \Carbon\Carbon::parse($pa->tanggal_pinjam)->locale('id')->translatedFormat('d M Y') }}
                                </div>
                                <div class="small">
                                    <span class="text-muted">Tenggat:</span> <strong>{{ \Carbon\Carbon::parse($pa->tenggat_kembali)->locale('id')->translatedFormat('d M Y') }}</strong>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($pa->status === 'Menunggu')
                                    <span class="badge-kondisi-status kondisi-ringan"><i class="fa-solid fa-clock"></i> Menunggu</span>
                                @elseif($pa->status === 'Disetujui')
                                    <span class="badge-kondisi-status kondisi-info"><i class="fa-solid fa-thumbs-up"></i> Disetujui</span>
                                @elseif($pa->status === 'Diambil')
                                    <span class="badge-kondisi-status kondisi-diambil"><i class="fa-solid fa-box-open"></i> Diambil</span>
                                @elseif($pa->status === 'Kembali')
                                    <span class="badge-kondisi-status kondisi-baik"><i class="fa-solid fa-circle-check"></i> Kembali</span>
                                @elseif($pa->status === 'Ditolak')
                                    <span class="badge-kondisi-status kondisi-berat"><i class="fa-solid fa-circle-xmark"></i> Ditolak</span>
                                @else
                                    <span class="badge-kondisi-status kondisi-berat"><i class="fa-solid fa-triangle-exclamation"></i> Terlambat</span>
                                @endif
                            </td>
                            <td class="text-start small text-muted text-truncate" style="max-width: 200px;" title="{{ $pa->keperluan }}">
                                {{ $pa->keperluan ?? '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-inbox fs-2 d-block mb-2 text-secondary opacity-50"></i>
                                Belum ada data peminjaman aset pada periode filter ini.
                            </td>
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
        <div class="card border-0 shadow-sm rounded-4 mb-4 no-print">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-filter text-primary"></i>
                        <span>Filter Periode & Ruangan Kegiatan</span>
                    </h6>
                    <button type="button" class="btn btn-sm btn-outline-primary fw-semibold rounded-3 px-3" data-bs-toggle="modal" data-bs-target="#modalPengaturanTTD">
                        <i class="fa-solid fa-pen-nib me-1"></i> Atur Penandatangan
                    </button>
                </div>

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
                            <button type="submit" class="btn btn-primary fw-bold px-4 py-2 shadow-sm rounded-3">
                                <i class="fa-solid fa-magnifying-glass me-1"></i> Terapkan Filter
                            </button>
                            <a href="{{ route('laporan.index') }}?tab=peminjaman-ruangan" class="btn btn-outline-secondary py-2 px-3 rounded-3" title="Reset Filter">
                                <i class="fa-solid fa-rotate-left"></i> Reset
                            </a>
                        </div>
                        <div class="d-flex gap-2">
                            <!-- Export Excel -->
                            <a href="{{ route('laporan.peminjaman-ruangan.export', request()->all()) }}" class="btn btn-success fw-bold px-3.5 py-2 shadow-sm rounded-3 d-inline-flex align-items-center gap-1.5">
                                <i class="fa-solid fa-file-excel"></i>
                                <span>Export Excel (.xlsx)</span>
                            </a>
                            <!-- Print A4 -->
                            <button type="button" onclick="triggerPrintTab('peminjaman-ruangan')" class="btn btn-dark fw-bold px-3.5 py-2 shadow-sm rounded-3 d-inline-flex align-items-center gap-1.5">
                                <i class="fa-solid fa-print"></i>
                                <span>Cetak Laporan Booking Ruangan</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Web Table Preview Peminjaman Ruangan -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 no-print overflow-hidden">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="p-1.5 rounded-2 bg-warning-subtle text-warning"><i class="fa-solid fa-calendar-check"></i></span>
                    <h6 class="fw-bold mb-0 text-dark">Rekapitulasi Peminjaman & Booking Ruangan Kegiatan</h6>
                </div>
                <span class="badge bg-light text-secondary border font-monospace px-2.5 py-1.5 rounded-pill">{{ $peminjamanRuangans->count() }} Jadwal Booking</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="text-center" style="width: 55px;">No</th>
                            <th class="text-start ps-3" style="width: 140px;">Kode Booking</th>
                            <th class="text-start">Pemohon & Identitas</th>
                            <th class="text-start">Ruangan Kegiatan</th>
                            <th class="text-start">Jadwal & Waktu Pemakaian</th>
                            <th class="text-center" style="width: 130px;">Status</th>
                            <th class="text-start">Keperluan Kegiatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamanRuangans as $index => $pr)
                        <tr>
                            <td class="text-center fw-semibold text-muted">{{ $index + 1 }}</td>
                            <td class="text-start ps-3"><span class="badge-code">{{ $pr->kode_booking }}</span></td>
                            <td class="text-start">
                                <div class="fw-bold text-dark">{{ $pr->nama_peminjam }}</div>
                                <div class="text-muted small font-monospace">
                                    {{ $pr->nomor_identitas }} &bull; {{ $pr->prodi_unit ?? '-' }}
                                </div>
                            </td>
                            <td class="text-start">
                                <div class="fw-bold text-dark">{{ $pr->ruangan->nama_ruangan ?? '-' }}</div>
                                <span class="badge-code py-0">{{ $pr->ruangan->kode_ruangan ?? '-' }}</span>
                            </td>
                            <td class="text-start">
                                <div class="fw-bold text-dark small">
                                    {{ \Carbon\Carbon::parse($pr->tanggal_pemakaian)->locale('id')->translatedFormat('l, d M Y') }}
                                </div>
                                <div class="small text-primary font-monospace mt-0.5">
                                    {{ date('H:i', strtotime($pr->jam_mulai)) }} - {{ date('H:i', strtotime($pr->jam_selesai)) }} WIB
                                </div>
                            </td>
                            <td class="text-center">
                                @if($pr->status === 'Menunggu')
                                    <span class="badge-kondisi-status kondisi-ringan"><i class="fa-solid fa-clock"></i> Menunggu</span>
                                @elseif($pr->status === 'Disetujui')
                                    <span class="badge-kondisi-status kondisi-info"><i class="fa-solid fa-thumbs-up"></i> Disetujui</span>
                                @elseif($pr->status === 'Digunakan')
                                    <span class="badge-kondisi-status kondisi-diambil"><i class="fa-solid fa-door-open"></i> Digunakan</span>
                                @elseif($pr->status === 'Selesai')
                                    <span class="badge-kondisi-status kondisi-baik"><i class="fa-solid fa-circle-check"></i> Selesai</span>
                                @elseif($pr->status === 'Ditolak')
                                    <span class="badge-kondisi-status kondisi-berat"><i class="fa-solid fa-circle-xmark"></i> Ditolak</span>
                                @endif
                            </td>
                            <td class="text-start small text-muted text-truncate" style="max-width: 220px;" title="{{ $pr->keperluan }}">
                                {{ $pr->keperluan ?? '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-inbox fs-2 d-block mb-2 text-secondary opacity-50"></i>
                                Belum ada data booking ruangan pada periode filter ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<!-- ========================================================================= -->
<!-- MODAL PENGATURAN PEJABAT PENANDATANGAN DOKUMEN CETAK (AJAX + DB PERSIST)  -->
<!-- ========================================================================= -->
<div class="modal fade" id="modalPengaturanTTD" tabindex="-1" aria-labelledby="modalPengaturanTTDLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-bottom px-4 py-3 bg-light">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="rounded-3 bg-primary-subtle text-primary p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 1.1rem;">
                        <i class="fa-solid fa-pen-nib"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0 text-dark" id="modalPengaturanTTDLabel">Pengaturan Pejabat Penandatangan</h5>
                        <small class="text-muted">Konfigurasi nama & NIP pejabat pada lembar cetak dokumen KIR dan laporan</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formSimpanTTD" action="{{ route('laporan.ttd.update') }}" method="POST" onsubmit="saveSignaturesToDB(event)">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info d-flex align-items-center gap-2.5 py-2.5 px-3 rounded-3 mb-4 small border-0 bg-primary-subtle text-primary-emphasis">
                        <i class="fa-solid fa-circle-info fs-5 flex-shrink-0"></i>
                        <span>Data penandatangan akan disimpan permanen ke database dan otomatis tercetak pada lembar KIR serta laporan berkala.</span>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card border rounded-3 p-3 bg-light-subtle h-100">
                                <label class="form-label fw-bold text-dark mb-2.5 d-flex align-items-center gap-2">
                                    <span class="p-1 rounded bg-primary text-white"><i class="fa-solid fa-user-tie small"></i></span>
                                    <span>Ketua STIKes Panti Waluya</span>
                                </label>
                                <div class="mb-2.5">
                                    <label class="form-label small text-muted mb-1">Nama Lengkap & Gelar</label>
                                    <input type="text" name="nama_ketua" id="inputNamaKetua" class="form-control" value="{{ $pejabat['nama_ketua'] }}" placeholder="Nama Lengkap & Gelar Ketua" oninput="syncKIRSignaturesLive()" required>
                                </div>
                                <div>
                                    <label class="form-label small text-muted mb-1">NIDN / NIP Ketua</label>
                                    <input type="text" name="nip_ketua" id="inputNipKetua" class="form-control font-monospace" value="{{ $pejabat['nip_ketua'] }}" placeholder="NIDN / NIP Ketua" oninput="syncKIRSignaturesLive()">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border rounded-3 p-3 bg-light-subtle h-100">
                                <label class="form-label fw-bold text-dark mb-2.5 d-flex align-items-center gap-2">
                                    <span class="p-1 rounded bg-primary text-white"><i class="fa-solid fa-user-gear small"></i></span>
                                    <span>Kepala Bagian Sarpras</span>
                                </label>
                                <div class="mb-2.5">
                                    <label class="form-label small text-muted mb-1">Nama Lengkap & Gelar</label>
                                    <input type="text" name="nama_kabag_sarpras" id="inputNamaKabag" class="form-control" value="{{ $pejabat['nama_kabag_sarpras'] }}" placeholder="Nama Lengkap & Gelar Kabag Sarpras" oninput="syncKIRSignaturesLive()" required>
                                </div>
                                <div>
                                    <label class="form-label small text-muted mb-1">NIK / NIP Kabag Sarpras</label>
                                    <input type="text" name="nip_kabag_sarpras" id="inputNipKabag" class="form-control font-monospace" value="{{ $pejabat['nip_kabag_sarpras'] }}" placeholder="NIK / NIP Kabag Sarpras" oninput="syncKIRSignaturesLive()">
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card border rounded-3 p-3 bg-light-subtle">
                                <div class="row g-2.5 align-items-center">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-dark mb-1 d-flex align-items-center gap-1.5">
                                            <i class="fa-solid fa-location-dot text-primary"></i> Kota / Lokasi Terbit Dokumen
                                        </label>
                                        <input type="text" name="kota_dokumen" id="inputKotaDokumen" class="form-control" value="{{ $pejabat['kota_dokumen'] }}" placeholder="Kota Dokumen (e.g. Malang)" oninput="syncKIRSignaturesLive()">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small text-muted mb-1">Pratinjau Format Tanggal Cetak</label>
                                        <div class="p-2 bg-white rounded border text-muted small">
                                            Hasil cetak: <strong class="text-dark" id="previewTglDokumen">{{ $pejabat['kota_dokumen'] }}, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top px-4 py-3 bg-light d-flex justify-content-between align-items-center">
                    <span id="ttdStatusSave" class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill d-none">
                        <i class="fa-solid fa-check me-1"></i> Perubahan berhasil disimpan ke database!
                    </span>
                    <div class="d-flex gap-2 ms-auto">
                        <button type="button" class="btn btn-outline-secondary px-3.5 py-2 rounded-3 fw-semibold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" id="btnSimpanTTD" class="btn btn-primary px-4 py-2 rounded-3 fw-bold shadow-sm d-inline-flex align-items-center gap-1.5">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span>Simpan ke Database</span>
                        </button>
                    </div>
                </div>
            </form>
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
                <p class="fw-bold mb-0 text-decoration-underline print-nama-ketua" style="font-size: 0.95rem; white-space: nowrap;">{{ $pejabat['nama_ketua'] }}</p>
                <p class="mb-0 small font-monospace print-nip-ketua" style="font-size: 0.85rem;">{{ $pejabat['nip_ketua'] }}</p>
            </div>
            <div class="text-center" style="width: 320px;">
                <p class="mb-0 print-tgl-dokumen" style="font-size: 0.95rem;">{{ $pejabat['kota_dokumen'] }}, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</p>
                <p class="fw-bold mb-0" style="font-size: 0.95rem;">Kepala Bagian Sarana & Prasarana</p>
                <div style="height: 60px;"></div>
                <p class="fw-bold mb-0 text-decoration-underline print-nama-kabag" style="font-size: 0.95rem; white-space: nowrap;">{{ $pejabat['nama_kabag_sarpras'] }}</p>
                <p class="mb-0 small font-monospace print-nip-kabag" style="font-size: 0.85rem;">{{ $pejabat['nip_kabag_sarpras'] }}</p>
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
                <p class="fw-bold mb-0 text-decoration-underline print-nama-ketua" style="font-size: 0.95rem; white-space: nowrap;">{{ $pejabat['nama_ketua'] }}</p>
                <p class="mb-0 small font-monospace print-nip-ketua" style="font-size: 0.85rem;">{{ $pejabat['nip_ketua'] }}</p>
            </div>
            <div class="text-center" style="width: 320px;">
                <p class="mb-0 print-tgl-dokumen" style="font-size: 0.95rem;">{{ $pejabat['kota_dokumen'] }}, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</p>
                <p class="fw-bold mb-0" style="font-size: 0.95rem;">Kepala Bagian Sarana & Prasarana</p>
                <div style="height: 60px;"></div>
                <p class="fw-bold mb-0 text-decoration-underline print-nama-kabag" style="font-size: 0.95rem; white-space: nowrap;">{{ $pejabat['nama_kabag_sarpras'] }}</p>
                <p class="mb-0 small font-monospace print-nip-kabag" style="font-size: 0.85rem;">{{ $pejabat['nip_kabag_sarpras'] }}</p>
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
                <p class="fw-bold mb-0 text-decoration-underline print-nama-ketua" style="font-size: 0.95rem; white-space: nowrap;">{{ $pejabat['nama_ketua'] }}</p>
                <p class="mb-0 small font-monospace print-nip-ketua" style="font-size: 0.85rem;">{{ $pejabat['nip_ketua'] }}</p>
            </div>
            <div class="text-center" style="width: 320px;">
                <p class="mb-0 print-tgl-dokumen" style="font-size: 0.95rem;">{{ $pejabat['kota_dokumen'] }}, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</p>
                <p class="fw-bold mb-0" style="font-size: 0.95rem;">Kepala Bagian Sarana & Prasarana</p>
                <div style="height: 60px;"></div>
                <p class="fw-bold mb-0 text-decoration-underline print-nama-kabag" style="font-size: 0.95rem; white-space: nowrap;">{{ $pejabat['nama_kabag_sarpras'] }}</p>
                <p class="mb-0 small font-monospace print-nip-kabag" style="font-size: 0.85rem;">{{ $pejabat['nip_kabag_sarpras'] }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/laporan.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/laporan.js') }}"></script>
@endpush