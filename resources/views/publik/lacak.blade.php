<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Status Pengajuan Peminjaman - STIKES Panti Waluya</title>
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
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar bg-white border-bottom py-2">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ url('/') }}" class="d-flex align-items-center text-decoration-none gap-2">
                <img src="{{ asset('images/logo-stikes.png') }}" alt="Logo STIKES" style="height: 38px;">
                <span class="fw-bold text-dark small">STIKES PANTI WALUYA MALANG</span>
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
            <h4 class="fw-bold text-dark mb-1">Lacak Status Peminjaman Aset</h4>
            <p class="text-muted small mb-4">Masukkan <strong>NIM Mahasiswa</strong>, <strong>NIP Dosen</strong>, atau <strong>Kode Peminjaman</strong> Anda untuk melihat perkembangan persetujuan dan pengambilan barang.</p>

            <form action="{{ route('publik.lacak') }}" method="GET" class="row justify-content-center g-2">
                <div class="col-md-8">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-light"><i class="fa-solid fa-id-card"></i></span>
                        <input type="text" name="keyword" class="form-control" placeholder="Misal: 202301045 atau PJM-202609-0001" value="{{ $keyword ?? '' }}" required autofocus>
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
        @if($results !== null)
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-dark mb-0">
                    Hasil Pencarian untuk: <span class="text-primary font-monospace">"{{ $keyword }}"</span>
                </h6>
                <span class="badge bg-light text-secondary border font-monospace">{{ $results->count() }} Data Ditemukan</span>
            </div>

            @forelse($results as $res)
                <div class="card border-0 shadow-sm rounded-4 mb-3">
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

                        <!-- Status Message -->
                        @if($res->status === 'Menunggu')
                            <p class="small text-muted mb-0">
                                <i class="fa-solid fa-circle-info text-warning me-1"></i>
                                Permohonan Anda saat ini sedang menunggu persetujuan dari Kepala Biro Sarana & Prasarana.
                            </p>
                        @elseif($res->status === 'Disetujui')
                            <p class="small text-success mb-0">
                                <i class="fa-solid fa-circle-check me-1"></i>
                                <strong>Disetujui!</strong> Silakan datang ke Ruangan Sarpras/Lab untuk mengambil barang fisik.
                            </p>
                        @elseif($res->status === 'Diambil')
                            <p class="small text-primary mb-0">
                                <i class="fa-solid fa-box me-1"></i>
                                Barang telah diserahkan pada {{ $res->tanggal_diambil ? $res->tanggal_diambil->isoFormat('D MMM Y, HH:mm') : '-' }}. Harap dikembalikan sebelum tenggat waktu.
                            </p>
                        @elseif($res->status === 'Ditolak')
                            <p class="small text-danger mb-0">
                                <i class="fa-solid fa-circle-xmark me-1"></i>
                                Pengajuan ditolak. Alasan: <strong>{{ $res->alasan_penolakan ?? 'Tidak memenuhi syarat' }}</strong>
                            </p>
                        @endif

                        <div class="text-end mt-3">
                            <a href="{{ route('publik.sukses', $res->kode_peminjaman) }}" class="btn btn-sm btn-outline-primary fw-semibold rounded-pill px-3">
                                <i class="fa-solid fa-receipt me-1"></i> Buka Tiket Lengkap
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card border-0 shadow-sm rounded-4 text-center py-5">
                    <div class="text-muted mb-2"><i class="fa-solid fa-folder-open fa-3x opacity-50"></i></div>
                    <h6 class="fw-bold text-secondary mb-1">Tidak Ada Data Peminjaman</h6>
                    <p class="text-muted small mb-0">Pastikan NIM / NIP atau Kode Peminjaman yang Anda masukkan sudah benar.</p>
                </div>
            @endforelse
        @endif
    </div>

    <!-- FOOTER -->
    <footer class="mt-auto bg-white border-top py-3 text-center small text-muted">
        <div class="container">
            <strong>STIKES Panti Waluya Malang</strong> &copy; {{ date('Y') }} &bull; Sistem Informasi Inventaris & Peminjaman Aset
        </div>
    </footer>

</body>
</html>
