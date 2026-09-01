@extends('layouts.app')

@section('content')
<!-- Page Header (Hanya Tampil di Layar Web, Dihilangkan Total Saat Cetak) -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 no-print d-print-none page-web-header">
    <div>
        <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
            <span class="p-2 rounded-3 bg-success-subtle text-success d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                <i class="fa-solid fa-file-excel"></i>
            </span>
            <span>Laporan & Kartu Inventaris Ruangan (KIR)</span>
        </h4>
        <p class="text-muted small mb-0 ms-md-5">Cetak dan unduh rekapitulasi data aset per ruangan atau secara menyeluruh.</p>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4 no-print">
    <div class="col-6 col-lg-3">
        <div class="card-stat">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Aset Terfilter</div>
                    <div class="stat-value mt-1">{{ number_format($stats['total_aset_tercatat'] ?? $barangs->count()) }}</div>
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;"><i class="fa-solid fa-file-lines text-primary me-1"></i>Katalog Data</div>
                </div>
                <div class="stat-icon primary">
                    <i class="fa-solid fa-file-invoice"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card-stat">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Total Unit Fisik</div>
                    <div class="stat-value mt-1 text-info">{{ number_format($stats['total_unit_tercatat'] ?? $barangs->sum('jumlah')) }}</div>
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;"><i class="fa-solid fa-calculator text-info me-1"></i>Kuantitas Barang</div>
                </div>
                <div class="stat-icon info">
                    <i class="fa-solid fa-cubes"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card-stat">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Ruangan Terdata</div>
                    <div class="stat-value mt-1 text-success">{{ number_format($stats['total_ruangan'] ?? $ruangans->count()) }}</div>
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;"><i class="fa-solid fa-door-open text-success me-1"></i>Titik Lokasi</div>
                </div>
                <div class="stat-icon success">
                    <i class="fa-solid fa-building-circle-check"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card-stat">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Riwayat Mutasi</div>
                    <div class="stat-value mt-1 text-warning">{{ number_format($stats['total_mutasi'] ?? $mutasis->count()) }}</div>
                    <div class="text-muted small mt-1" style="font-size: 0.75rem;"><i class="fa-solid fa-clock-rotate-left text-warning me-1"></i>Log Perubahan</div>
                </div>
                <div class="stat-icon warning">
                    <i class="fa-solid fa-timeline"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Card Area Filter & Export Excel -->
<div class="card mb-4 no-print">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
            <i class="fa-solid fa-filter text-primary"></i>
            <span>Pilih Ruangan & Ekspor Dokumen</span>
        </h6>
        <form action="{{ route('laporan.export') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-lg-4 col-md-12">
                <label class="form-label">Filter Ruangan / Tipe Laporan</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-door-open"></i></span>
                    <select name="ruangan_id" class="form-select" onchange="window.location.href='{{ route('laporan.index') }}?ruangan_id=' + this.value">
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
                <button type="button" onclick="window.print()" class="btn btn-outline-dark w-100 py-2 d-flex align-items-center justify-content-center gap-1.5">
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
                <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary w-100 py-2 d-flex align-items-center justify-content-center gap-1" title="Reset">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<style>
    @media print {
        .page-web-header, .navbar-custom, .no-print, .d-print-none, .card-stat, form, .modal, .footer-custom, .alert {
            display: none !important;
        }
        .kir-print-document {
            display: block !important;
            width: 100%;
            color: #000000 !important;
            background: #ffffff !important;
        }
        .table-kir-print {
            width: 100%;
            border-collapse: collapse !important;
            font-size: 8.5pt !important;
            margin-top: 8px;
            color: #000000 !important;
        }
        .table-kir-print th, .table-kir-print td {
            border: 1px solid #000000 !important;
            padding: 5px 6px !important;
            vertical-align: middle;
        }
        .table-kir-print th {
            background-color: #f1f5f9 !important;
            font-weight: 700;
            text-align: center;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .table-kir-print tfoot td {
            background-color: #f8fafc !important;
            font-weight: 700;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>

<!-- DOKUMEN CETAK RESMI KARTU INVENTARIS RUANGAN (KIR) - HANYA TAMPIL SAAT PRINT -->
<div class="d-none d-print-block kir-print-document mb-4">
    <!-- Kop Surat Resmi (Tanpa Logo) -->
    <div style="text-align: center; margin-bottom: 6px;">
        <div style="font-size: 10.5pt; font-weight: 700; letter-spacing: 0.05em; margin-bottom: 2px;">YAYASAN PANTI WALUYA SAWAHAN MALANG</div>
        <div style="font-size: 13pt; font-weight: 800; color: #000; margin-bottom: 3px;">SEKOLAH TINGGI ILMU KESEHATAN PANTI WALUYA MALANG</div>
        <div style="font-size: 8pt; color: #222; margin-bottom: 2px;">Program Studi: Profesi Ners &bull; Sarjana Keperawatan &bull; D-III Keperawatan &bull; D-III Kebidanan &bull; Sarjana Farmasi</div>
        <div style="font-size: 8pt; color: #222;">Jl. Yulius Usman No. 62, Malang 65117 | Telp. (0341) 369003 | Website: www.pantiwaluya.ac.id</div>
    </div>
    
    <!-- Garis Kop Surat Ganda Formal -->
    <div style="border-top: 2px solid #000000; border-bottom: 1px solid #000000; height: 3px; margin: 4px 0 14px 0;"></div>

    <!-- Judul Dokumen -->
    <div style="text-align: center; margin-bottom: 14px;">
        <h4 style="font-size: 12pt; font-weight: 800; margin: 0; text-transform: uppercase; letter-spacing: 0.04em;">KARTU INVENTARIS RUANGAN (KIR)</h4>
        <div style="font-size: 8.5pt; font-weight: 600; text-transform: uppercase; color: #333;">TAHUN ANGGARAN {{ date('Y') }}</div>
    </div>

    <!-- Metadata Informasi Ruangan -->
    @php
        $selectedRuangan = request('ruangan_id') ? $ruangans->firstWhere('id', request('ruangan_id')) : null;
    @endphp
    <table style="width: 100%; font-size: 8.5pt; margin-bottom: 8px; line-height: 1.5; border: none;">
        <tr>
            <td style="width: 17%; font-weight: bold; border: none;">Ruangan / Unit</td>
            <td style="width: 2%; border: none;">:</td>
            <td style="width: 46%; border: none;"><strong>{{ $selectedRuangan ? $selectedRuangan->nama_ruangan : 'Rekapitulasi Global (Seluruh Ruangan)' }}</strong></td>
            <td style="width: 15%; font-weight: bold; border: none;">Tanggal Cetak</td>
            <td style="width: 2%; border: none;">:</td>
            <td style="width: 18%; border: none;">{{ date('d F Y') }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; border: none;">Kode Ruangan</td>
            <td style="border: none;">:</td>
            <td style="border: none;"><strong>{{ $selectedRuangan ? $selectedRuangan->kode_ruangan : '-' }}</strong></td>
            <td style="font-weight: bold; border: none;">Petugas Cetak</td>
            <td style="border: none;">:</td>
            <td style="border: none;">{{ auth()->user()->name ?? 'Admin Sarpras' }}</td>
        </tr>
    </table>

    <!-- Tabel Daftar Aset Formal untuk Cetak KIR -->
    <table class="table-kir-print">
        <thead>
            <tr>
                <th style="width: 28px; text-align: center;">No</th>
                <th style="width: 125px; text-align: center;">Kode Aset</th>
                <th>Nama Barang / Aset</th>
                <th style="width: 130px;">Kategori</th>
                @if(!$selectedRuangan)
                    <th style="width: 120px;">Ruangan</th>
                @endif
                <th style="width: 50px; text-align: center;">Tahun</th>
                <th style="width: 75px; text-align: center;">Kondisi</th>
                <th style="width: 60px; text-align: center;">Jumlah</th>
                <th style="width: 110px;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barangs as $index => $b)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td style="text-align: center; font-family: monospace; font-weight: 700; white-space: nowrap;">
                    {{ $b->kode_barang }}
                </td>
                <td>
                    <strong>{{ $b->nama_barang }}</strong>
                </td>
                <td>{{ $b->kategori->nama_kategori ?? '-' }}</td>
                @if(!$selectedRuangan)
                    <td>{{ $b->ruangan->nama_ruangan ?? '-' }}</td>
                @endif
                <td style="text-align: center;">{{ $b->tahun_pengadaan ?? '-' }}</td>
                <td style="text-align: center;">
                    {{ $b->kondisi }}
                </td>
                <td style="text-align: center; font-weight: 700;">
                    {{ number_format($b->jumlah) }} Unit
                </td>
                <td style="font-size: 7.5pt; color: #333;">{{ $b->keterangan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ $selectedRuangan ? '8' : '9' }}" style="text-align: center; padding: 14px;">
                    Tidak ada data aset terdaftar pada ruangan ini.
                </td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f1f5f9; font-weight: 700;">
                <td colspan="{{ $selectedRuangan ? '6' : '7' }}" style="text-align: right; padding-right: 8px;">
                    Total Keseluruhan:
                </td>
                <td style="text-align: center;">{{ number_format($barangs->sum('jumlah')) }} Unit</td>
                <td>({{ $barangs->count() }} Jenis Aset)</td>
            </tr>
        </tfoot>
    </table>
</div>

<!-- Preview Data Laporan & Log Mutasi (TAMPILAN WEB) -->
<div class="row g-4 no-print">
    <!-- Preview Tabel Aset -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header-modern">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-table-list text-primary"></i>
                    <span>Preview Data Aset</span>
                    @if(request('ruangan_id'))
                        <span class="badge bg-primary-subtle text-primary ms-1">
                            {{ $ruangans->firstWhere('id', request('ruangan_id'))->nama_ruangan ?? '' }}
                        </span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary-emphasis border ms-1">Global (Semua Ruangan)</span>
                    @endif
                </div>
                <span class="badge bg-secondary-subtle text-secondary rounded-pill">{{ $barangs->count() }} Item Aset</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern align-middle">
                        <thead>
                            <tr>
                                <th class="ps-4" style="width: 130px;">Kode</th>
                                <th>Nama Barang / Aset</th>
                                <th>Ruangan</th>
                                <th class="text-center" style="width: 100px;">Jumlah</th>
                                <th class="pe-4">Kondisi</th>
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
                                    <div class="small text-muted">{{ $b->kategori->nama_kategori ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1.5 text-secondary">
                                        <i class="fa-solid fa-door-open text-primary" style="font-size: 0.8rem;"></i>
                                        <span class="fw-medium">{{ $b->ruangan->nama_ruangan ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-dark-subtle text-dark border px-2.5 py-1.5 fw-bold rounded-pill">
                                        {{ number_format($b->jumlah) }}
                                    </span>
                                </td>
                                <td class="pe-4">
                                    @if($b->kondisi == 'Baik')
                                        <span class="badge-status badge-status-baik">Baik</span>
                                    @elseif($b->kondisi == 'Rusak Ringan')
                                        <span class="badge-status badge-status-ringan">Rusak Ringan</span>
                                    @else
                                        <span class="badge-status badge-status-berat">Rusak Berat</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted mb-2">
                                        <i class="fa-regular fa-folder-open fa-3x opacity-50"></i>
                                    </div>
                                    <h6 class="fw-bold text-secondary mb-1">Data Tidak Ditemukan</h6>
                                    <p class="text-muted small mb-0">Tidak ada item aset yang sesuai dengan kriteria filter.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Log Riwayat Mutasi & Kerusakan (Timeline Style) -->
    <div class="col-lg-4 no-print">
        <div class="card">
            <div class="card-header-modern">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-primary"></i>
                    <span>Log Mutasi Terkini</span>
                </div>
                <span class="badge bg-primary-subtle text-primary rounded-pill">{{ $mutasis->count() }} Aktivitas</span>
            </div>
            <div class="card-body p-3" style="max-height: 520px; overflow-y: auto;">
                <div class="timeline-container ps-3 pt-2">
                    @forelse($mutasis as $m)
                    <div class="timeline-item">
                        <div class="timeline-dot {{ $m->jenis_mutasi == 'Pindah Ruangan' ? 'info' : 'danger' }}"></div>
                        <div class="bg-light p-3 rounded-3 border">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <strong class="text-dark" style="font-size: 0.9rem;">{{ $m->barang->nama_barang ?? 'Aset Dihapus' }}</strong>
                                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.7rem;">
                                    {{ $m->created_at->diffForHumans() }}
                                </span>
                            </div>
                            
                            <div class="mb-2">
                                @if($m->jenis_mutasi == 'Pindah Ruangan')
                                    <span class="badge bg-info-subtle text-info border px-2 py-0.5" style="font-size: 0.75rem;">
                                        <i class="fa-solid fa-right-left me-1"></i>Dipindah {{ $m->jumlah }} unit
                                    </span>
                                    <div class="mt-1 small text-secondary">
                                        <span class="fw-semibold">{{ $m->ruanganAsal->nama_ruangan ?? '-' }}</span>
                                        <i class="fa-solid fa-arrow-right mx-1 text-muted" style="font-size: 0.7rem;"></i>
                                        <span class="fw-semibold text-primary">{{ $m->ruanganTujuan->nama_ruangan ?? '-' }}</span>
                                    </div>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border px-2 py-0.5" style="font-size: 0.75rem;">
                                        <i class="fa-solid fa-circle-minus me-1"></i>Berkurang {{ $m->jumlah }} unit
                                    </span>
                                @endif
                            </div>

                            @if($m->keterangan)
                                <div class="bg-white p-2 rounded border small text-muted fst-italic" style="font-size: 0.78rem;">
                                    "{{ $m->keterangan }}"
                                </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">
                        <i class="fa-solid fa-clipboard-check fa-2x mb-2 opacity-50"></i>
                        <p class="small mb-0">Belum ada riwayat mutasi atau pengurangan aset.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection