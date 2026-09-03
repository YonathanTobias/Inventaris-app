<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Booking Ruangan [{{ $booking->kode_booking }}] - SPARTA-PW STIKES Panti Waluya</title>
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
            background: linear-gradient(135deg, #065f46 0%, #047857 50%, #0d9488 100%);
            color: #ffffff;
            padding: 2rem;
            position: relative;
        }

        .badge-code {
            background-color: #f1f5f9;
            color: #1e293b;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.85rem;
            padding: 0.35rem 0.75rem;
            border-radius: 8px;
            font-weight: 700;
            border: 1px solid #cbd5e1;
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
            background: #ecfdf5;
            border-color: #a7f3d0;
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
                    <div class="rounded-circle bg-white text-success d-flex align-items-center justify-content-center fw-bold fs-3 shadow-sm" style="width: 56px; height: 56px;">
                        <i class="fa-solid fa-door-open"></i>
                    </div>
                    <div>
                        <span class="badge bg-white bg-opacity-20 text-light border border-white border-opacity-25 px-2.5 py-1 rounded-pill small mb-1">
                            Bukti Booking Ruangan Digital
                        </span>
                        <h4 class="fw-bold mb-0 text-white font-monospace">{{ $booking->kode_booking }}</h4>
                    </div>
                </div>

                <div class="text-md-end">
                    <span class="small text-light text-opacity-75 d-block">Status Permohonan:</span>
                    @if($booking->status === 'Menunggu')
                        <span class="badge bg-warning text-dark fs-6 px-3 py-1.5 rounded-pill shadow-sm">
                            <i class="fa-solid fa-clock me-1"></i> Menunggu Persetujuan
                        </span>
                    @elseif($booking->status === 'Disetujui')
                        <span class="badge bg-light text-success fw-bold fs-6 px-3 py-1.5 rounded-pill shadow-sm">
                            <i class="fa-solid fa-circle-check me-1"></i> Disetujui (Jadwal Terkunci)
                        </span>
                    @elseif($booking->status === 'Digunakan')
                        <span class="badge bg-info text-dark fs-6 px-3 py-1.5 rounded-pill shadow-sm">
                            <i class="fa-solid fa-key me-1"></i> Sedang Digunakan
                        </span>
                    @elseif($booking->status === 'Selesai')
                        <span class="badge bg-success text-white fs-6 px-3 py-1.5 rounded-pill shadow-sm">
                            <i class="fa-solid fa-check-double me-1"></i> Pemakaian Selesai
                        </span>
                    @elseif($booking->status === 'Ditolak')
                        <span class="badge bg-danger text-white fs-6 px-3 py-1.5 rounded-pill shadow-sm">
                            <i class="fa-solid fa-ban me-1"></i> Booking Ditolak
                        </span>
                    @endif
                </div>
            </div>

            <!-- TAHAPAN LIFECYCLE TRACKER -->
            <div class="p-4 bg-light border-bottom">
                <h6 class="fw-bold text-dark small text-uppercase mb-3">
                    <i class="fa-solid fa-timeline text-success me-1"></i> Alur Tahapan Booking Ruangan:
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
                        <div class="timeline-step {{ in_array($booking->status, ['Disetujui', 'Digunakan', 'Selesai']) ? 'done' : ($booking->status === 'Ditolak' ? 'rejected' : 'active') }}">
                            @if(in_array($booking->status, ['Disetujui', 'Digunakan', 'Selesai']))
                                <i class="fa-solid fa-circle-check text-success fs-5"></i>
                            @elseif($booking->status === 'Ditolak')
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
                        <div class="timeline-step {{ in_array($booking->status, ['Digunakan', 'Selesai']) ? 'done' : ($booking->status === 'Disetujui' ? 'active' : '') }}">
                            @if(in_array($booking->status, ['Digunakan', 'Selesai']))
                                <i class="fa-solid fa-circle-check text-success fs-5"></i>
                            @elseif($booking->status === 'Disetujui')
                                <i class="fa-solid fa-key text-success fs-5"></i>
                            @else
                                <i class="fa-regular fa-circle text-muted fs-5"></i>
                            @endif
                            <div>
                                <strong class="d-block small text-dark">3. Penggunaan</strong>
                                <small class="text-muted" style="font-size: 0.72rem;">Serah Kunci</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="timeline-step {{ $booking->status === 'Selesai' ? 'done' : '' }}">
                            @if($booking->status === 'Selesai')
                                <i class="fa-solid fa-circle-check text-success fs-5"></i>
                            @else
                                <i class="fa-regular fa-circle text-muted fs-5"></i>
                            @endif
                            <div>
                                <strong class="d-block small text-dark">4. Selesai</strong>
                                <small class="text-muted" style="font-size: 0.72rem;">Kunci Kembali</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PESAN STATUS KHUSUS -->
                <div class="mt-3">
                    @if($booking->status === 'Menunggu')
                        <div class="alert alert-warning mb-0 small rounded-3 border-warning d-flex align-items-center gap-2">
                            <i class="fa-solid fa-circle-info fs-5"></i>
                            <div>
                                <strong>Menunggu Persetujuan:</strong> Pengajuan booking Anda saat ini sedang dalam proses review oleh Kepala Biro Sarana & Prasarana. Simpan Kode Booking Anda (<strong>{{ $booking->kode_booking }}</strong>) untuk mengecek status sewaktu-waktu.
                            </div>
                        </div>
                    @elseif($booking->status === 'Disetujui')
                        <div class="alert alert-success mb-0 small rounded-3 border-success d-flex align-items-center gap-2">
                            <i class="fa-solid fa-circle-check fs-5"></i>
                            <div>
                                <strong>Booking Disetujui!</strong> Jadwal pemakaian ruangan telah dikunci untuk Anda. Silakan datang ke Ruang Sarpras/Laboran sebelum jam kegiatan dimulai untuk mengambil kunci ruangan.
                            </div>
                        </div>
                    @elseif($booking->status === 'Digunakan')
                        <div class="alert alert-info mb-0 small rounded-3 border-info d-flex align-items-center gap-2">
                            <i class="fa-solid fa-key fs-5"></i>
                            <div>
                                <strong>Ruangan Sedang Digunakan:</strong> Kunci telah diserahkan pada {{ $booking->waktu_masuk ? $booking->waktu_masuk->isoFormat('D MMM Y, HH:mm') : '-' }}. Harap menjaga kebersihan dan mematikan AC/lampu serta mengembalikan kunci setelah selesai.
                            </div>
                        </div>
                    @elseif($booking->status === 'Ditolak')
                        <div class="alert alert-danger mb-0 small rounded-3 border-danger">
                            <strong><i class="fa-solid fa-ban me-1"></i> Booking Ditolak:</strong> 
                            {{ $booking->alasan_penolakan ?? 'Mohon maaf, ruangan belum dapat dipinjam pada jadwal tersebut.' }}
                        </div>
                    @elseif($booking->status === 'Selesai')
                        <div class="alert alert-success mb-0 small rounded-3 border-success">
                            <i class="fa-solid fa-check-double me-1"></i> Pemakaian ruangan telah selesai pada {{ $booking->waktu_selesai ? $booking->waktu_selesai->isoFormat('D MMMM Y, HH:mm') : '-' }}. Kunci telah diterima kembali oleh petugas. Terima kasih!
                        </div>
                    @endif
                </div>
            </div>

            <!-- RINCIAN BOOKING -->
            <div class="p-4 p-md-5">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-secondary small text-uppercase mb-2">Data Pemohon</h6>
                        <table class="table table-sm table-borderless small mb-0">
                            <tr>
                                <td class="text-muted ps-0" style="width: 140px;">Kategori</td>
                                <td class="fw-semibold">: <span class="badge bg-light text-dark border">{{ $booking->kategori_peminjam }}</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">Nama Lengkap</td>
                                <td class="fw-bold">: {{ $booking->nama_peminjam }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">NIM / NIP</td>
                                <td class="fw-bold font-monospace">: {{ $booking->nomor_identitas }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">Prodi / Unit</td>
                                <td>: {{ $booking->prodi_unit ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">No. WhatsApp</td>
                                <td>: <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->kontak_peminjam) }}" target="_blank" class="text-success text-decoration-none fw-semibold"><i class="fa-brands fa-whatsapp me-1"></i>{{ $booking->kontak_peminjam }}</a></td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h6 class="fw-bold text-secondary small text-uppercase mb-2">Detail Ruangan & Waktu</h6>
                        <table class="table table-sm table-borderless small mb-0">
                            <tr>
                                <td class="text-muted ps-0" style="width: 140px;">Nama Ruangan</td>
                                <td class="fw-bold text-dark">: {{ $booking->ruangan->nama_ruangan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">Kode Ruangan</td>
                                <td>: <span class="badge-code py-0">{{ $booking->ruangan->kode_ruangan ?? '-' }}</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">Tanggal Pakai</td>
                                <td class="fw-bold text-dark">: {{ \Carbon\Carbon::parse($booking->tanggal_pemakaian)->isoFormat('dddd, D MMMM Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">Waktu / Jam</td>
                                <td class="fw-bold text-primary">: {{ date('H:i', strtotime($booking->jam_mulai)) }} - {{ date('H:i', strtotime($booking->jam_selesai)) }} WIB</td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-12">
                        <div class="p-3 bg-light rounded-3 border">
                            <h6 class="fw-bold text-secondary small text-uppercase mb-1">Keperluan / Acara Kegiatan:</h6>
                            <p class="small text-dark mb-0">{{ $booking->keperluan }}</p>
                        </div>
                    </div>
                </div>

                <!-- ACTION BUTTONS -->
                <div class="mt-4 pt-3 border-top d-flex flex-column flex-sm-row justify-content-between gap-2 no-print">
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                        <i class="fa-solid fa-house me-1"></i> Kembali ke Halaman Utama
                    </a>
                    <div class="d-flex gap-2">
                        <a href="{{ route('publik.lacak', ['keyword' => $booking->kode_booking]) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
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
