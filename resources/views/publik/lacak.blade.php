<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Status Pengajuan - SPARTA-PW STIKES Panti Waluya</title>
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

        .search-box-card {
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }

        .badge-code {
            background-color: #f1f5f9;
            color: #1e293b;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
            padding: 0.25rem 0.6rem;
            border-radius: 6px;
            font-weight: 600;
            border: 1px solid #cbd5e1;
            display: inline-block;
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar bg-white border-bottom py-2">
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
                    <i class="fa-solid fa-house me-1"></i> Halaman Utama
                </a>
                <a href="{{ route('login') }}" class="btn btn-sm btn-dark">
                    <i class="fa-solid fa-lock me-1 text-warning"></i> Login Petugas
                </a>
            </div>
        </div>
    </nav>

    <div class="container my-5" style="max-width: 860px;">
        <!-- SEARCH SECTION -->
        <div class="search-box-card p-4 p-md-5 mb-4 text-center">
            <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                <i class="fa-solid fa-magnifying-glass fa-xl"></i>
            </div>
            <h4 class="fw-bold text-dark mb-1">Lacak Status Peminjaman Aset & Ruangan</h4>
            <p class="text-muted small mb-4">Masukkan <strong>NIM Mahasiswa</strong>, <strong>NIP Dosen</strong>, atau <strong>Kode Transaksi</strong> (Aset / Ruangan) Anda untuk memantau status secara real-time.</p>

            <form action="{{ route('publik.lacak') }}" method="GET" class="row justify-content-center g-2">
                <div class="col-md-8">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-light"><i class="fa-solid fa-id-card"></i></span>
                        <input type="text" name="keyword" class="form-control" placeholder="Misal: 202301045, PJM-202609-0001, atau BKG-202609-0001" value="{{ $keyword ?? '' }}" required autofocus>
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Cari Data
                    </button>
                </div>
            </form>
        </div>

        <!-- HASIL PENCARIAN -->
        @if($results !== null || $resultsRuangan !== null)
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-bold text-dark mb-0">
                    Hasil Pencarian untuk: <span class="text-primary font-monospace">"{{ $keyword }}"</span>
                </h6>
                <span class="badge bg-light text-secondary border font-monospace">
                    {{ ($results ? $results->count() : 0) + ($resultsRuangan ? $resultsRuangan->count() : 0) }} Data Ditemukan
                </span>
            </div>

            <!-- 1. HASIL BOOKING RUANGAN -->
            @if($resultsRuangan && $resultsRuangan->count() > 0)
                <h6 class="fw-bold text-success small text-uppercase mb-2">
                    <i class="fa-solid fa-door-open me-1"></i> Pengajuan Booking Ruangan ({{ $resultsRuangan->count() }})
                </h6>
                @foreach($resultsRuangan as $bkg)
                    <div class="card border-0 shadow-sm rounded-4 mb-3 border-start border-success border-4">
                        <div class="card-body p-4">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-2 mb-3">
                                <div>
                                    <span class="badge-code mb-1 d-inline-block">{{ $bkg->kode_booking }}</span>
                                    <h5 class="fw-bold text-dark mb-0">{{ $bkg->ruangan->nama_ruangan ?? '-' }}</h5>
                                    <div class="text-muted small">
                                        <i class="fa-solid fa-user me-1"></i>{{ $bkg->nama_peminjam }} ({{ $bkg->nomor_identitas }}) &bull; {{ $bkg->prodi_unit ?? '-' }}
                                    </div>
                                </div>
                                <div>
                                    @if($bkg->status === 'Menunggu')
                                        <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold">
                                            <i class="fa-solid fa-clock me-1"></i> Menunggu Persetujuan
                                        </span>
                                    @elseif($bkg->status === 'Disetujui')
                                        <span class="badge bg-success text-white px-3 py-1.5 rounded-pill fw-bold">
                                            <i class="fa-solid fa-circle-check me-1"></i> Disetujui (Terkunci)
                                        </span>
                                    @elseif($bkg->status === 'Digunakan')
                                        <span class="badge bg-info text-dark px-3 py-1.5 rounded-pill fw-bold">
                                            <i class="fa-solid fa-key me-1"></i> Sedang Digunakan
                                        </span>
                                    @elseif($bkg->status === 'Selesai')
                                        <span class="badge bg-success text-white px-3 py-1.5 rounded-pill fw-bold">
                                            <i class="fa-solid fa-check-double me-1"></i> Pemakaian Selesai
                                        </span>
                                    @elseif($bkg->status === 'Ditolak')
                                        <span class="badge bg-danger text-white px-3 py-1.5 rounded-pill fw-bold">
                                            <i class="fa-solid fa-ban me-1"></i> Ditolak
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row g-2 small text-secondary bg-light p-3 rounded-3 border mb-3">
                                <div class="col-sm-6">
                                    <span class="text-muted d-block">Tanggal Pemakaian:</span>
                                    <strong class="text-dark">{{ \Carbon\Carbon::parse($bkg->tanggal_pemakaian)->isoFormat('dddd, D MMMM Y') }}</strong>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted d-block">Waktu / Jam:</span>
                                    <strong class="text-primary font-monospace">{{ date('H:i', strtotime($bkg->jam_mulai)) }} - {{ date('H:i', strtotime($bkg->jam_selesai)) }} WIB</strong>
                                </div>
                            </div>

                            <div class="text-end mt-3">
                                <a href="{{ route('publik.ruangan.sukses', $bkg->kode_booking) }}" class="btn btn-sm btn-outline-success fw-semibold rounded-pill px-3">
                                    <i class="fa-solid fa-receipt me-1"></i> Buka Tiket Booking
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            <!-- 2. HASIL PEMINJAMAN ASET -->
            @if($results && $results->count() > 0)
                <h6 class="fw-bold text-primary small text-uppercase mb-2 mt-4">
                    <i class="fa-solid fa-boxes-stacked me-1"></i> Pengajuan Peminjaman Aset ({{ $results->count() }})
                </h6>
                @foreach($results as $res)
                    <div class="card border-0 shadow-sm rounded-4 mb-3 border-start border-primary border-4">
                        <div class="card-body p-4">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-2 mb-3">
                                <div>
                                    <span class="badge-code mb-1 d-inline-block">{{ $res->kode_peminjaman }}</span>
                                    @if($res->details && $res->details->count() > 1)
                                        <h5 class="fw-bold text-dark mb-0">
                                            {{ $res->details->first()->barang->nama_barang ?? '-' }}
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill py-0.5 px-2 small fs-6">
                                                + {{ $res->details->count() - 1 }} Aset Lainnya (Total {{ $res->jumlah }} Unit)
                                            </span>
                                        </h5>
                                    @else
                                        <h5 class="fw-bold text-dark mb-0">{{ $res->details->first()->barang->nama_barang ?? ($res->barang->nama_barang ?? '-') }}</h5>
                                    @endif
                                    <div class="text-muted small">
                                        <i class="fa-solid fa-user me-1"></i>{{ $res->nama_peminjam }} ({{ $res->nomor_identitas }}) &bull; {{ $res->prodi_unit ?? '-' }}
                                    </div>
                                </div>
                                <div>
                                    @if($res->status === 'Menunggu')
                                        <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold">
                                            <i class="fa-solid fa-clock me-1"></i> Menunggu Persetujuan
                                        </span>
                                    @elseif($res->status === 'Disetujui')
                                        <span class="badge bg-primary text-white px-3 py-1.5 rounded-pill fw-bold">
                                            <i class="fa-solid fa-circle-check me-1"></i> Disetujui (Siap Diambil)
                                        </span>
                                    @elseif($res->status === 'Diambil')
                                        <span class="badge bg-info text-dark px-3 py-1.5 rounded-pill fw-bold">
                                            <i class="fa-solid fa-box me-1"></i> Barang Sudah Diambil
                                        </span>
                                    @elseif($res->status === 'Kembali')
                                        <span class="badge bg-success text-white px-3 py-1.5 rounded-pill fw-bold">
                                            <i class="fa-solid fa-check-double me-1"></i> Sudah Dikembalikan
                                        </span>
                                    @elseif($res->status === 'Ditolak')
                                        <span class="badge bg-danger text-white px-3 py-1.5 rounded-pill fw-bold">
                                            <i class="fa-solid fa-ban me-1"></i> Ditolak
                                        </span>
                                    @else
                                        <span class="badge bg-danger text-white px-3 py-1.5 rounded-pill fw-bold">
                                            <i class="fa-solid fa-triangle-exclamation me-1"></i> Terlambat
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row g-2 small text-secondary bg-light p-3 rounded-3 border mb-3">
                                <div class="col-sm-4">
                                    <span class="text-muted d-block">Jumlah Dipinjam:</span>
                                    <strong class="text-dark">{{ $res->jumlah }} Unit</strong>
                                </div>
                                <div class="col-sm-4">
                                    <span class="text-muted d-block">Tgl Rencana Pinjam:</span>
                                    <strong class="text-dark">{{ \Carbon\Carbon::parse($res->tanggal_pinjam)->isoFormat('D MMM Y') }}</strong>
                                </div>
                                <div class="col-sm-4">
                                    <span class="text-muted d-block">Tenggat Pengembalian:</span>
                                    <strong class="text-danger">{{ \Carbon\Carbon::parse($res->tenggat_kembali)->isoFormat('D MMM Y') }}</strong>
                                </div>
                            </div>

                            <div class="text-end mt-3">
                                <a href="{{ route('publik.sukses', $res->kode_peminjaman) }}" class="btn btn-sm btn-outline-primary fw-semibold rounded-pill px-3">
                                    <i class="fa-solid fa-receipt me-1"></i> Buka Tiket Lengkap
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            <!-- KONDISI JIKA DUA-DUANYA KOSONG -->
            @if((!$results || $results->count() === 0) && (!$resultsRuangan || $resultsRuangan->count() === 0))
                <div class="card border-0 shadow-sm rounded-4 text-center py-5">
                    <div class="text-muted mb-2"><i class="fa-solid fa-folder-open fa-3x opacity-50"></i></div>
                    <h6 class="fw-bold text-secondary mb-1">Tidak Ada Data Ditemukan</h6>
                    <p class="text-muted small mb-0">Pastikan NIM / NIP atau Kode Peminjaman / Booking yang Anda masukkan sudah benar.</p>
                </div>
            @endif
        @endif
    </div>

    <!-- FOOTER -->
    <footer class="mt-auto bg-white border-top py-3 text-center small text-muted">
        <div class="container">
            <strong>SPARTA-PW</strong> &copy; {{ date('Y') }} &bull; STIKES Panti Waluya Malang &bull; Sistem Peminjaman Aset & Ruangan Terpadu Akademik
        </div>
    </footer>

</body>
</html>
