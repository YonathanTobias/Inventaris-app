<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Peminjaman Aset [{{ $peminjaman->kode_peminjaman }}] - SPARTA-PW STIKES Panti Waluya</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-stikes.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #334155;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .ticket-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 12px 32px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .ticket-header {
            background: linear-gradient(135deg, #0D47A1 0%, #2196F3 100%);
            color: #ffffff;
            padding: 2rem;
            position: relative;
        }

        .badge-code {
            background-color: #E3F2FD;
            color: #0D47A1;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.85rem;
            padding: 0.35rem 0.75rem;
            border-radius: 8px;
            font-weight: 700;
            border: 1px solid #90CAF9;
            display: inline-block;
        }

        .timeline-step {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            margin-bottom: 0.5rem;
        }

        .timeline-step.active {
            background: #E3F2FD;
            border-color: #90CAF9;
        }

        .timeline-step.done {
            background: #ecfdf5;
            border-color: #a7f3d0;
        }

        .timeline-step.rejected {
            background: #fef2f2;
            border-color: #fecaca;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: #ffffff !important;
            }
            .ticket-card {
                box-shadow: none !important;
                border: 1px solid #000000 !important;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar bg-white border-bottom py-2 no-print">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ url('/') }}" class="d-flex align-items-center text-decoration-none gap-2">
                <img src="{{ asset('images/logo-stikes.png') }}" alt="Logo STIKES" style="height: 38px;">
                <div>
                    <span class="fw-extrabold text-dark small d-block" style="line-height: 1.1;">SPARTA-PW</span>
                    <small class="text-muted" style="font-size: 0.65rem;">STIKES PANTI WALUYA MALANG</small>
                </div>
            </a>
            <div class="d-flex gap-2">
                <a href="{{ url('/') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i> Form Baru
                </a>
                <a href="{{ route('publik.lacak') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fa-solid fa-magnifying-glass me-1"></i> Lacak Pengajuan
                </a>
            </div>
        </div>
    </nav>

    <div class="container my-4 my-md-5" style="max-width: 820px;">
        <div class="ticket-card">
            <!-- HEADER TIKET -->
            <div class="ticket-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold fs-3 shadow-sm" style="width: 56px; height: 56px;">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <div>
                        <span class="badge bg-white text-dark px-3 py-1 rounded-pill fw-bold mb-1 shadow-sm" style="font-size: 0.75rem; letter-spacing: 0.03em;">
                            BUKTI PEMINJAMAN ASET SARPRAS
                        </span>
                        <h4 class="fw-bold mb-0 text-white font-monospace">{{ $peminjaman->kode_peminjaman }}</h4>
                    </div>
                </div>

                <div class="text-md-end">
                    <span class="small text-white fw-semibold d-block mb-1">Status Permohonan:</span>
                    @if($peminjaman->status === 'Menunggu')
                        <span class="badge bg-warning text-dark fs-6 px-3 py-1.5 rounded-pill shadow-sm">
                            <i class="fa-solid fa-clock me-1"></i> Menunggu Persetujuan
                        </span>
                    @elseif($peminjaman->status === 'Disetujui')
                        <span class="badge bg-primary text-white fs-6 px-3 py-1.5 rounded-pill shadow-sm">
                            <i class="fa-solid fa-circle-check me-1"></i> Disetujui (Siap Diambil)
                        </span>
                    @elseif($peminjaman->status === 'Diambil')
                        <span class="badge bg-info text-dark fs-6 px-3 py-1.5 rounded-pill shadow-sm">
                            <i class="fa-solid fa-box me-1"></i> Barang Sudah Diambil
                        </span>
                    @elseif($peminjaman->status === 'Kembali')
                        <span class="badge bg-success text-white fs-6 px-3 py-1.5 rounded-pill shadow-sm">
                            <i class="fa-solid fa-check-double me-1"></i> Selesai (Dikembalikan)
                        </span>
                    @elseif($peminjaman->status === 'Ditolak')
                        <span class="badge bg-danger text-white fs-6 px-3 py-1.5 rounded-pill shadow-sm">
                            <i class="fa-solid fa-ban me-1"></i> Pengajuan Ditolak
                        </span>
                    @else
                        <span class="badge bg-danger text-white fs-6 px-3 py-1.5 rounded-pill shadow-sm">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i> Terlambat
                        </span>
                    @endif
                </div>
            </div>

            <!-- STATUS LIFECYCLE TRACKER -->
            <div class="p-4 bg-light border-bottom">
                <h6 class="fw-bold text-dark small text-uppercase mb-3">
                    Alur Tahapan Pengajuan:
                </h6>
                <div class="row g-2">
                    <div class="col-md-3">
                        <div class="timeline-step done">
                            <i class="fa-solid fa-circle-check text-success fs-5"></i>
                            <div>
                                <strong class="d-block small text-dark">1. Pengajuan</strong>
                                <small class="text-muted" style="font-size: 0.72rem;">Form Terkirim</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="timeline-step {{ in_array($peminjaman->status, ['Disetujui', 'Diambil', 'Kembali']) ? 'done' : ($peminjaman->status === 'Ditolak' ? 'rejected' : 'active') }}">
                            @if(in_array($peminjaman->status, ['Disetujui', 'Diambil', 'Kembali']))
                                <i class="fa-solid fa-circle-check text-success fs-5"></i>
                            @elseif($peminjaman->status === 'Ditolak')
                                <i class="fa-solid fa-circle-xmark text-danger fs-5"></i>
                            @else
                                <i class="fa-solid fa-hourglass-half text-warning fs-5"></i>
                            @endif
                            <div>
                                <strong class="d-block small text-dark">2. Persetujuan</strong>
                                <small class="text-muted" style="font-size: 0.72rem;">Kepala Sarpras</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="timeline-step {{ in_array($peminjaman->status, ['Diambil', 'Kembali']) ? 'done' : ($peminjaman->status === 'Disetujui' ? 'active' : '') }}">
                            @if(in_array($peminjaman->status, ['Diambil', 'Kembali']))
                                <i class="fa-solid fa-circle-check text-success fs-5"></i>
                            @elseif($peminjaman->status === 'Disetujui')
                                <i class="fa-solid fa-box-open text-primary fs-5"></i>
                            @else
                                <i class="fa-regular fa-circle text-muted fs-5"></i>
                            @endif
                            <div>
                                <strong class="d-block small text-dark">3. Pengambilan</strong>
                                <small class="text-muted" style="font-size: 0.72rem;">Di Ruang Sarpras</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="timeline-step {{ $peminjaman->status === 'Kembali' ? 'done' : ($peminjaman->status === 'Diambil' ? 'active' : '') }}">
                            @if($peminjaman->status === 'Kembali')
                                <i class="fa-solid fa-circle-check text-success fs-5"></i>
                            @elseif($peminjaman->status === 'Diambil')
                                <i class="fa-solid fa-clock-rotate-left text-warning fs-5"></i>
                            @else
                                <i class="fa-regular fa-circle text-muted fs-5"></i>
                            @endif
                            <div>
                                <strong class="d-block small text-dark">4. Pengembalian</strong>
                                <small class="text-muted" style="font-size: 0.72rem;">Cek Fisik & Selesai</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PESAN STATUS KHUSUS -->
                <div class="mt-3">
                    @if($peminjaman->status === 'Menunggu')
                        <div class="alert alert-warning mb-0 small rounded-3 border-warning d-flex align-items-center gap-2">
                            <i class="fa-solid fa-circle-info fs-5"></i>
                            <div>
                                <strong>Menunggu Persetujuan:</strong> Pengajuan Anda saat ini sedang dalam antrean review oleh Kepala Biro Sarana & Prasarana. Simpan Kode Peminjaman Anda (<strong>{{ $peminjaman->kode_peminjaman }}</strong>) untuk mengecek status permohonan.
                            </div>
                        </div>
                    @elseif($peminjaman->status === 'Disetujui')
                        <div class="alert alert-success mb-0 small rounded-3 border-success d-flex align-items-center gap-2">
                            <i class="fa-solid fa-circle-check fs-5"></i>
                            <div>
                                <strong>Pengajuan Disetujui!</strong> Silakan datang ke Ruang Sarpras untuk mengambil barang sesuai tanggal rencana pengambilan.
                            </div>
                        </div>
                    @elseif($peminjaman->status === 'Diambil')
                        <div class="alert alert-info mb-0 small rounded-3 border-info d-flex align-items-center gap-2">
                            <i class="fa-solid fa-box-open fs-5"></i>
                            <div>
                                <strong>Barang Sudah Diambil:</strong> Barang telah diserahkan pada {{ $peminjaman->tanggal_diambil ? $peminjaman->tanggal_diambil->locale('id')->translatedFormat('l, d F Y, H:i') : '-' }}. Harap menjaga keutuhan aset dan mengembalikannya sebelum <strong>{{ \Carbon\Carbon::parse($peminjaman->tenggat_kembali)->locale('id')->translatedFormat('l, d F Y') }}</strong>.
                            </div>
                        </div>
                    @elseif($peminjaman->status === 'Ditolak')
                        <div class="alert alert-danger mb-0 small rounded-3 border-danger">
                            <strong><i class="fa-solid fa-ban me-1"></i> Pengajuan Ditolak:</strong> 
                            {{ $peminjaman->alasan_penolakan ?? 'Mohon maaf, permohonan belum dapat disetujui untuk saat ini.' }}
                        </div>
                    @elseif($peminjaman->status === 'Kembali')
                        <div class="alert alert-success mb-0 small rounded-3 border-success">
                            <i class="fa-solid fa-check-double me-1"></i> Transaksi peminjaman telah selesai. Barang telah dikembalikan dalam kondisi <strong>{{ $peminjaman->kondisi_kembali }}</strong> pada {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->locale('id')->translatedFormat('l, d F Y') }}. Terima kasih!
                        </div>
                    @endif
                </div>
            </div>

            <!-- RINCIAN PERMOHONAN -->
            <div class="p-4 p-md-5">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-secondary small text-uppercase mb-2">Data Pemohon</h6>
                        <table class="table table-sm table-borderless small mb-0">
                            <tr>
                                <td class="text-muted ps-0" style="width: 130px;">Kategori</td>
                                <td class="fw-semibold">: <span class="badge bg-light text-dark border">{{ $peminjaman->kategori_peminjam }}</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">Nama Lengkap</td>
                                <td class="fw-bold">: {{ $peminjaman->nama_peminjam }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">NIM / NIP / NIDN</td>
                                <td class="fw-bold font-monospace">: {{ $peminjaman->nomor_identitas }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">Prodi / Unit</td>
                                <td>: {{ $peminjaman->prodi_unit ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">No. WhatsApp</td>
                                <td>: <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $peminjaman->kontak_peminjam) }}" target="_blank" class="text-success text-decoration-none fw-semibold">{{ $peminjaman->kontak_peminjam }}</a></td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h6 class="fw-bold text-secondary small text-uppercase mb-2">Jadwal Peminjaman</h6>
                        <table class="table table-sm table-borderless small mb-0">
                            <tr>
                                <td class="text-muted ps-0" style="width: 140px;">Tanggal Pinjam</td>
                                <td class="fw-bold text-dark">: {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->locale('id')->translatedFormat('l, d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">Tenggat Kembali</td>
                                <td class="fw-bold text-danger">: {{ \Carbon\Carbon::parse($peminjaman->tenggat_kembali)->locale('id')->translatedFormat('l, d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">Total Keseluruhan</td>
                                <td class="fw-bold text-primary">: {{ $peminjaman->jumlah }} Unit Aset</td>
                            </tr>
                            @if($peminjaman->tanggal_diambil)
                            <tr>
                                <td class="text-muted ps-0">Waktu Diambil</td>
                                <td class="text-info fw-semibold">: {{ $peminjaman->tanggal_diambil->locale('id')->translatedFormat('l, d F Y, H:i') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>

                    <!-- TABEL MULTI-ITEM BARANG YANG DIPINJAM -->
                    <div class="col-12">
                        <h6 class="fw-bold text-primary small text-uppercase mb-2">
                            Rincian Aset yang Dipinjam:
                        </h6>
                        <div class="table-responsive rounded-3 border">
                            <table class="table table-hover align-middle mb-0 small">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-3" style="width: 40px;">No</th>
                                        <th>Nama Aset / Barang</th>
                                        <th>Kode Aset</th>
                                        <th>Lokasi Ruangan</th>
                                        <th class="text-center pe-3" style="width: 110px;">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($peminjaman->details && $peminjaman->details->count() > 0)
                                        @foreach($peminjaman->details as $idx => $det)
                                        <tr>
                                            <td class="ps-3 fw-bold text-muted">{{ $idx + 1 }}</td>
                                            <td class="fw-bold text-dark">{{ $det->barang->nama_barang ?? '-' }}</td>
                                            <td><span class="badge-code py-0">{{ $det->barang->kode_barang ?? '-' }}</span></td>
                                            <td>{{ $det->barang->ruangan->nama_ruangan ?? '-' }}</td>
                                            <td class="text-center pe-3 fw-bold text-primary">{{ $det->jumlah }} Unit</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="ps-3 fw-bold text-muted">1</td>
                                            <td class="fw-bold text-dark">{{ $peminjaman->barang->nama_barang ?? '-' }}</td>
                                            <td><span class="badge-code py-0">{{ $peminjaman->barang->kode_barang ?? '-' }}</span></td>
                                            <td>{{ $peminjaman->barang->ruangan->nama_ruangan ?? '-' }}</td>
                                            <td class="text-center pe-3 fw-bold text-primary">{{ $peminjaman->jumlah }} Unit</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="p-3 bg-light rounded-3 border">
                            <h6 class="fw-bold text-secondary small text-uppercase mb-1">Keperluan / Keterangan:</h6>
                            <p class="small text-dark mb-0">{{ $peminjaman->keperluan }}</p>
                        </div>
                    </div>
                </div>

                <!-- ACTION BUTTONS -->
                <div class="mt-4 pt-3 border-top d-flex flex-column flex-sm-row justify-content-between gap-2 no-print">
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                        <i class="fa-solid fa-house me-1"></i> Kembali ke Halaman Utama
                    </a>
                    <div class="d-flex gap-2">
                        <a href="{{ route('publik.lacak', ['keyword' => $peminjaman->kode_peminjaman]) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                            <i class="fa-solid fa-rotate-right me-1"></i> Refresh Status
                        </a>
                        <button type="button" class="btn btn-dark btn-sm rounded-pill px-3 fw-bold" onclick="window.print()">
                            <i class="fa-solid fa-print me-1"></i> Cetak / Simpan Tiket PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
