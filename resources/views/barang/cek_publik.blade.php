<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $barang->nama_barang }} ({{ $barang->kode_barang }}) - Verifikasi Aset STIKES Panti Waluya</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-stikes.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & FontAwesome 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            --card-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.15);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0b0f19;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 1.5rem 1rem;
            position: relative;
        }

        /* Ambient Glow Background */
        body::before {
            content: '';
            position: fixed;
            top: -15%;
            left: 50%;
            transform: translateX(-50%);
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(79, 70, 229, 0.22) 0%, rgba(59, 130, 246, 0.08) 50%, transparent 70%);
            z-index: 0;
            pointer-events: none;
        }

        .scan-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: var(--card-shadow);
            width: 100%;
            max-width: 580px;
            overflow: hidden;
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .scan-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            padding: 1.75rem 1.5rem 1.25rem;
            text-align: center;
            color: #ffffff;
            position: relative;
        }

        .scan-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        .brand-logo-scan {
            height: 52px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.3));
            margin-bottom: 0.75rem;
        }

        .verified-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.4);
            color: #34d399;
            font-size: 0.76rem;
            font-weight: 700;
            padding: 0.3rem 0.85rem;
            border-radius: 9999px;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .scan-body {
            padding: 1.75rem 1.5rem;
        }

        .asset-code-pill {
            font-family: 'JetBrains Mono', monospace;
            background: #f8fafc;
            color: #1e293b;
            font-size: 0.95rem;
            font-weight: 800;
            padding: 0.45rem 0.9rem;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            letter-spacing: 0.02em;
        }

        .info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 1rem;
            height: 100%;
        }

        .info-label {
            font-size: 0.72rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
        }

        .badge-status-baik {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            font-weight: 700;
            padding: 0.4rem 0.85rem;
            border-radius: 9999px;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .badge-status-ringan {
            background-color: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
            font-weight: 700;
            padding: 0.4rem 0.85rem;
            border-radius: 9999px;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .badge-status-berat {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
            font-weight: 700;
            padding: 0.4rem 0.85rem;
            border-radius: 9999px;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .timeline-item-scan {
            position: relative;
            padding-left: 24px;
            padding-bottom: 1rem;
            border-left: 2px solid #e2e8f0;
        }

        .timeline-item-scan:last-child {
            border-left-color: transparent;
            padding-bottom: 0;
        }

        .timeline-dot-scan {
            position: absolute;
            left: -6px;
            top: 2px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--primary);
        }

        .btn-action-scan {
            background: var(--primary-gradient);
            color: #ffffff;
            font-weight: 700;
            padding: 0.75rem 1.25rem;
            border-radius: 12px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .btn-action-scan:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(79, 70, 229, 0.4);
            color: #ffffff;
        }

        .scan-footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 1rem 1.5rem;
            text-align: center;
            font-size: 0.78rem;
            color: #64748b;
        }
    </style>
</head>
<body>

    <div class="scan-card">
        <!-- Card Header -->
        <div class="scan-header">
            <img src="{{ asset('images/logo-stikes.png') }}" alt="Logo STIKES Panti Waluya" class="brand-logo-scan">
            <h5 class="fw-bold mb-1" style="letter-spacing: -0.02em;">STIKES PANTI WALUYA</h5>
            <p class="text-white-50 small mb-2">Sistem Informasi Inventaris & Aset Laboratorium</p>
            <div>
                <span class="verified-badge">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Aset Resmi Terverifikasi</span>
                </span>
            </div>
        </div>

        <!-- Card Body -->
        <div class="scan-body">
            <!-- Asset Title & Code -->
            <div class="text-center mb-4">
                <div class="asset-code-pill mb-2">
                    <i class="fa-solid fa-barcode text-primary"></i>
                    <span>{{ $barang->kode_barang }}</span>
                </div>
                <h4 class="fw-bold text-dark mb-2" style="line-height: 1.3;">
                    {{ $barang->nama_barang }}
                </h4>
                <div>
                    @if($barang->kondisi == 'Baik')
                        <span class="badge-status-baik">
                            <i class="fa-solid fa-circle-check"></i> Kondisi: Baik (Siap Digunakan)
                        </span>
                    @elseif($barang->kondisi == 'Rusak Ringan')
                        <span class="badge-status-ringan">
                            <i class="fa-solid fa-triangle-exclamation"></i> Kondisi: Rusak Ringan (Perlu Servis)
                        </span>
                    @else
                        <span class="badge-status-berat">
                            <i class="fa-solid fa-circle-xmark"></i> Kondisi: Rusak Berat (Tidak Aktif)
                        </span>
                    @endif
                </div>
            </div>

            <!-- Key Specs Grid -->
            <div class="row g-3 mb-4">
                <div class="col-6">
                    <div class="info-card">
                        <div class="info-label"><i class="fa-solid fa-door-open me-1 text-primary"></i> Lokasi Ruangan</div>
                        <div class="info-value text-truncate" title="{{ $barang->ruangan->nama_ruangan ?? '-' }}">
                            {{ $barang->ruangan->nama_ruangan ?? 'Belum Ditentukan' }}
                        </div>
                        <small class="text-muted font-monospace" style="font-size: 0.72rem;">{{ $barang->ruangan->kode_ruangan ?? '' }}</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="info-card">
                        <div class="info-label"><i class="fa-solid fa-tags me-1 text-primary"></i> Kategori Aset</div>
                        <div class="info-value text-truncate" title="{{ $barang->kategori->nama_kategori ?? '-' }}">
                            {{ $barang->kategori->nama_kategori ?? 'Umum' }}
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="info-card">
                        <div class="info-label"><i class="fa-solid fa-cubes me-1 text-primary"></i> Jumlah / Stok</div>
                        <div class="info-value">{{ $barang->jumlah }} Unit</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="info-card">
                        <div class="info-label"><i class="fa-solid fa-calendar-check me-1 text-primary"></i> Tahun Pengadaan</div>
                        <div class="info-value">{{ $barang->tahun_pengadaan ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <!-- Keterangan & Catatan Teknis -->
            @if($barang->keterangan)
            <div class="p-3 rounded-4 mb-4 border bg-light">
                <div class="small fw-bold text-secondary mb-1">
                    <i class="fa-solid fa-circle-info me-1 text-primary"></i> Catatan & Spesifikasi Teknis:
                </div>
                <div class="small text-dark" style="line-height: 1.5;">
                    {{ $barang->keterangan }}
                </div>
            </div>
            @endif

            <!-- Riwayat Mutasi Terakhir -->
            @if($barang->mutasis && $barang->mutasis->count() > 0)
            <div class="mb-4">
                <h6 class="fw-bold small text-secondary text-uppercase mb-3 d-flex align-items-center gap-1.5">
                    <i class="fa-solid fa-clock-rotate-left text-primary"></i>
                    <span>Riwayat Mutasi & Pergerakan Aset</span>
                </h6>
                <div class="bg-white p-3 rounded-4 border">
                    @foreach($barang->mutasis->take(3) as $m)
                    <div class="timeline-item-scan">
                        <div class="timeline-dot-scan"></div>
                        <div class="d-flex justify-content-between align-items-center mb-0.5">
                            <span class="badge bg-primary-subtle text-primary fw-bold" style="font-size: 0.7rem;">
                                {{ $m->jenis_mutasi }} ({{ $m->jumlah }} Unit)
                            </span>
                            <span class="text-muted" style="font-size: 0.72rem;">{{ $m->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="small text-dark fw-semibold">
                            @if($m->jenis_mutasi == 'Pindah Ruangan')
                                {{ $m->ruanganAsal->nama_ruangan ?? '-' }} &rarr; {{ $m->ruanganTujuan->nama_ruangan ?? '-' }}
                            @else
                                {{ $m->ruanganAsal->nama_ruangan ?? '-' }}
                            @endif
                        </div>
                        @if($m->keterangan)
                            <div class="text-muted" style="font-size: 0.75rem;">{{ $m->keterangan }}</div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Action Button -->
            @auth
                <a href="{{ route('barang.index', ['search' => $barang->kode_barang]) }}" class="btn-action-scan">
                    <i class="fa-solid fa-sliders"></i>
                    <span>Buka & Kelola di Dashboard Admin</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-dark w-100 py-2.5 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    <span>Login Sebagai Pengelola Aset</span>
                </a>
            @endauth
        </div>

        <!-- Card Footer -->
        <div class="scan-footer">
            <div>Terverifikasi otomatis oleh SIM Inventaris &copy; {{ date('Y') }} STIKES Panti Waluya</div>
            <div style="font-size: 0.7rem; color: #94a3b8; margin-top: 2px;">Waktu Cek: {{ date('d F Y, H:i') }} WIB</div>
        </div>
    </div>

</body>
</html>
