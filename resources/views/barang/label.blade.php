<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Cetak Label Stiker Aset' }} - STIKES Panti Waluya</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-stikes.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=JetBrains+Mono:wght@700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & FontAwesome 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- JsBarcode & QRCode.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
        :root {
            --primary: #4f46e5;
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #0f172a;
            color: #1e293b;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        /* Top Toolbar Screen Only */
        .toolbar-container {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 1rem 0;
        }

        .btn-modern-print {
            background: var(--primary-gradient);
            border: none;
            color: #ffffff;
            font-weight: 700;
            padding: 0.55rem 1.3rem;
            border-radius: 10px;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.4);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-modern-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(79, 70, 229, 0.5);
            color: #ffffff;
        }

        .preview-paper {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            padding: 24px;
            margin: 24px auto;
            max-width: 900px;
        }

        /* STICKER CONTAINER GRID */
        .labels-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 4mm;
            align-items: flex-start;
            justify-content: flex-start;
        }

        /* EXACT 5cm x 2.5cm (50mm x 25mm) STICKER SPECIFICATION */
        .label-sticker {
            width: 50mm;
            height: 25mm;
            max-width: 50mm;
            max-height: 25mm;
            box-sizing: border-box;
            background: #ffffff;
            border: 1px solid #334155;
            border-radius: 2mm;
            overflow: hidden;
            page-break-inside: avoid;
            break-inside: avoid;
            position: relative;
        }

        /* ----------------------------------------------------
           LAYOUT 1: QR CODE SIDE-BY-SIDE (MODERN)
           ---------------------------------------------------- */
        .sticker-qr-layout {
            display: flex;
            align-items: center;
            height: 100%;
            padding: 1.5mm 2mm;
            gap: 2mm;
            box-sizing: border-box;
        }

        .sticker-qr-box {
            width: 19mm;
            height: 19mm;
            min-width: 19mm;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
        }

        .sticker-qr-box img, .sticker-qr-box canvas {
            width: 18.5mm !important;
            height: 18.5mm !important;
            display: block;
        }

        .sticker-qr-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 21mm;
            line-height: 1.1;
            overflow: hidden;
        }

        .qr-header {
            display: flex;
            align-items: center;
            gap: 1.2mm;
            border-bottom: 0.5px solid #000000;
            padding-bottom: 0.4mm;
        }

        .qr-header img {
            height: 3.4mm;
            width: auto;
            object-fit: contain;
        }

        .qr-header-title {
            font-size: 4.2pt;
            font-weight: 800;
            color: #000000;
            letter-spacing: -0.01em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .qr-code-text {
            font-family: 'JetBrains Mono', 'SFMono-Regular', Consolas, monospace;
            font-size: 6.2pt;
            font-weight: 800;
            color: #000000;
            letter-spacing: 0.2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin: 0.4mm 0;
            background: #f1f5f9;
            padding: 0.2mm 0.8mm;
            border-radius: 1mm;
            border: 0.4px solid #cbd5e1;
            display: inline-block;
            max-width: 25mm;
        }

        .qr-asset-name {
            font-size: 4.8pt;
            font-weight: 700;
            color: #000000;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 26mm;
            line-height: 1.1;
        }

        .qr-asset-sub {
            font-size: 4pt;
            font-weight: 600;
            color: #334155;
            display: flex;
            justify-content: space-between;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            border-top: 0.4px solid #cbd5e1;
            padding-top: 0.3mm;
        }

        /* ----------------------------------------------------
           LAYOUT 2: BARCODE 1D TOP-TO-BOTTOM (CLASSIC)
           ---------------------------------------------------- */
        .sticker-barcode-layout {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            padding: 1.2mm 2mm;
            box-sizing: border-box;
        }

        .barcode-header {
            display: flex;
            align-items: center;
            gap: 1.5mm;
            border-bottom: 0.5px solid #000000;
            padding-bottom: 0.4mm;
            line-height: 1;
        }

        .barcode-logo {
            height: 3.5mm;
            width: auto;
            object-fit: contain;
        }

        .barcode-org-title {
            font-size: 4.6pt;
            font-weight: 800;
            color: #000000;
            letter-spacing: -0.01em;
            text-transform: uppercase;
            line-height: 1;
            white-space: nowrap;
        }

        .barcode-body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0.4mm 0;
            line-height: 1;
        }

        .barcode-svg {
            width: 44mm;
            height: 8mm;
            display: block;
        }

        .barcode-code-text {
            font-family: 'JetBrains Mono', 'SFMono-Regular', Consolas, monospace;
            font-size: 6.8pt;
            font-weight: 800;
            color: #000000;
            letter-spacing: 0.4px;
            margin-top: 0.3mm;
            line-height: 1;
        }

        .barcode-footer {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
            border-top: 0.5px solid #000000;
            padding-top: 0.4mm;
        }

        .barcode-asset-name {
            font-size: 4.8pt;
            font-weight: 700;
            color: #000000;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 46mm;
        }

        .barcode-asset-sub {
            font-size: 4.2pt;
            font-weight: 600;
            color: #334155;
            display: flex;
            justify-content: space-between;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* PRINT STYLING */
        @page {
            size: auto;
            margin: 3mm;
        }

        @media print {
            body {
                background: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .no-print {
                display: none !important;
            }

            .preview-paper {
                box-shadow: none !important;
                border-radius: 0 !important;
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
            }

            .labels-grid {
                gap: 2mm;
            }

            .label-sticker {
                border: 0.8px solid #000000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .qr-code-text {
                background: transparent !important;
                border: none !important;
                padding: 0 !important;
            }
        }
    </style>
</head>
<body>

    <!-- Top Action Toolbar (Screen Only) -->
    <div class="toolbar-container no-print">
        <div class="container d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
            <div class="text-white">
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset('images/logo-stikes.png') }}" alt="Logo" style="height: 32px; width: auto;">
                    <div>
                        <h5 class="fw-bold mb-0">Cetak Label Stiker Aset (5cm × 2.5cm)</h5>
                        <div class="small text-secondary">
                            @if(isset($ruangan) && $ruangan)
                                <span class="badge bg-primary-subtle text-primary me-1"><i class="fa-solid fa-door-open me-1"></i>{{ $ruangan->nama_ruangan }}</span>
                            @else
                                <span class="badge bg-secondary me-1">Semua Ruangan (Global)</span>
                            @endif
                            &bull; Format: <strong>{{ ($type ?? 'qr') === 'qr' ? 'QR Code 2D' : 'Barcode 1D' }}</strong>
                            &bull; Total <strong>{{ $barangs->count() }} Label</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter & Format Controls in Print View -->
            <div class="d-flex flex-wrap align-items-center gap-2">
                <!-- Toggle Pilihan Format: QR Code vs Barcode 1D -->
                <div class="btn-group btn-group-sm bg-dark p-1 rounded-3 border border-secondary">
                    <a href="{{ request()->fullUrlWithQuery(['type' => 'qr']) }}" class="btn btn-sm {{ ($type ?? 'qr') === 'qr' ? 'btn-primary fw-bold' : 'btn-dark text-light border-0' }}">
                        <i class="fa-solid fa-qrcode me-1"></i> QR Code 2D
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['type' => 'barcode']) }}" class="btn btn-sm {{ ($type ?? 'qr') === 'barcode' ? 'btn-primary fw-bold' : 'btn-dark text-light border-0' }}">
                        <i class="fa-solid fa-barcode me-1"></i> Barcode 1D
                    </a>
                </div>

                <!-- Mode Kuantitas (Per Jenis vs Per Unit Fisik) -->
                <div class="btn-group btn-group-sm bg-dark p-1 rounded-3 border border-secondary">
                    <a href="{{ request()->fullUrlWithQuery(['mode' => 'jenis']) }}" class="btn btn-sm {{ ($mode ?? 'jenis') === 'jenis' ? 'btn-light text-dark fw-bold' : 'btn-dark text-light border-0' }}" title="1 Stiker per Jenis Aset">
                        1 Label/Jenis
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['mode' => 'unit']) }}" class="btn btn-sm {{ ($mode ?? 'jenis') === 'unit' ? 'btn-light text-dark fw-bold' : 'btn-dark text-light border-0' }}" title="Cetak sebanyak kuantitas fisik (semua unit)">
                        Sesuai Total Unit
                    </a>
                </div>

                <!-- Dropdown Pilih Ruangan -->
                @if(isset($allRuangans))
                <select class="form-select form-select-sm bg-dark text-white border-secondary" style="width: auto;" onchange="changeRoom(this.value)">
                    <option value="">-- Semua Ruangan --</option>
                    @foreach($allRuangans as $r)
                        <option value="{{ $r->id }}" {{ (isset($ruangan) && $ruangan && $ruangan->id == $r->id) ? 'selected' : '' }}>
                            {{ $r->nama_ruangan }}
                        </option>
                    @endforeach
                </select>
                @endif

                <!-- Tombol Print -->
                <button type="button" onclick="window.print()" class="btn btn-modern-print">
                    <i class="fa-solid fa-print"></i>
                    <span>Cetak Sekarang</span>
                </button>
                <a href="{{ route('barang.index') }}" class="btn btn-outline-light btn-sm py-2">
                    <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Sticker Labels Grid Paper -->
    <div class="preview-paper">
        <div class="alert alert-info py-2 small mb-3 no-print d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
            <div>
                <i class="fa-solid fa-circle-info me-1"></i>
                Format Aktif: <strong>{{ ($type ?? 'qr') === 'qr' ? 'QR Code (Bisa discan kamera HP / 2D Scanner)' : 'Barcode 1D Code-128' }}</strong>. Ukuran stiker: <strong>50mm × 25mm (5 × 2.5 cm)</strong>.
            </div>
            <div>
                <span class="badge bg-dark">{{ $barangs->count() }} Lembar Stiker</span>
            </div>
        </div>

        <div class="labels-grid">
            @forelse($barangs as $index => $b)
            @php
                $uniqueId = $b->unique_print_id ?? ($b->id . '-' . $index);
            @endphp
            <div class="label-sticker">
                
                @if(($type ?? 'qr') === 'qr')
                <!-- ================== LAYOUT QR CODE (SIDE-BY-SIDE) ================== -->
                <div class="sticker-qr-layout">
                    <!-- Left: QR Code Box -->
                    <div class="sticker-qr-box" id="qrcode-{{ $uniqueId }}" data-code="{{ $b->kode_barang }}"></div>

                    <!-- Right: Info Details -->
                    <div class="sticker-qr-info">
                        <div class="qr-header">
                            <img src="{{ asset('images/logo-stikes.png') }}" alt="Logo">
                            <span class="qr-header-title">STIKES PANTI WALUYA</span>
                        </div>

                        <div>
                            <div class="qr-code-text">{{ $b->kode_barang }}</div>
                            <div class="qr-asset-name" title="{{ $b->nama_barang }}">{{ $b->nama_barang }}</div>
                        </div>

                        <div class="qr-asset-sub">
                            <span>{{ $b->ruangan->kode_ruangan ?? $b->ruangan->nama_ruangan }}</span>
                            @if(isset($b->unit_seq))
                                <span class="fw-bold">Unit {{ $b->unit_seq }}/{{ $b->total_seq }}</span>
                            @else
                                <span>{{ $b->tahun_pengadaan ?? date('Y') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                @else
                <!-- ================== LAYOUT BARCODE 1D (TOP-TO-BOTTOM) ================== -->
                <div class="sticker-barcode-layout">
                    <!-- Header -->
                    <div class="barcode-header">
                        <img src="{{ asset('images/logo-stikes.png') }}" class="barcode-logo" alt="Logo">
                        <span class="barcode-org-title">STIKES PANTI WALUYA</span>
                    </div>

                    <!-- Barcode & Asset Code -->
                    <div class="barcode-body">
                        <svg class="barcode-svg" id="barcode-{{ $uniqueId }}" data-code="{{ $b->kode_barang }}"></svg>
                        <div class="barcode-code-text">{{ $b->kode_barang }}</div>
                    </div>

                    <!-- Footer Details -->
                    <div class="barcode-footer">
                        <div class="barcode-asset-name" title="{{ $b->nama_barang }}">{{ $b->nama_barang }}</div>
                        <div class="barcode-asset-sub">
                            <span>{{ $b->ruangan->kode_ruangan ?? $b->ruangan->nama_ruangan }}</span>
                            @if(isset($b->unit_seq))
                                <span class="fw-bold">Unit {{ $b->unit_seq }}/{{ $b->total_seq }}</span>
                            @else
                                <span>{{ $b->tahun_pengadaan ?? date('Y') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

            </div>
            @empty
            <div class="text-center py-5 w-100 text-muted">
                <i class="fa-solid fa-qrcode fa-3x mb-2 opacity-50"></i>
                <p class="mb-0">Tidak ada aset yang dipilih untuk dicetak.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Scripts: Generate QR Code or Barcode -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if(($type ?? 'qr') === 'qr')
                // Render High-Resolution QR Codes
                @foreach($barangs as $index => $b)
                    @php
                        $uniqueId = $b->unique_print_id ?? ($b->id . '-' . $index);
                    @endphp
                    try {
                        let qrContainer = document.getElementById("qrcode-{{ $uniqueId }}");
                        if (qrContainer) {
                            new QRCode(qrContainer, {
                                text: "{{ route('aset.cek', $b->kode_barang) }}",
                                width: 72,
                                height: 72,
                                colorDark : "#000000",
                                colorLight : "#ffffff",
                                correctLevel : QRCode.CorrectLevel.M
                            });
                        }
                    } catch(e) {
                        console.error("Gagal generate QR Code untuk: {{ $b->kode_barang }}", e);
                    }
                @endforeach
            @else
                // Render Vector Barcode 128
                @foreach($barangs as $index => $b)
                    @php
                        $uniqueId = $b->unique_print_id ?? ($b->id . '-' . $index);
                    @endphp
                    try {
                        JsBarcode("#barcode-{{ $uniqueId }}", "{{ $b->kode_barang }}", {
                            format: "CODE128",
                            width: 1.1,
                            height: 24,
                            displayValue: false,
                            margin: 0,
                            lineColor: "#000000"
                        });
                    } catch(e) {
                        console.error("Gagal generate barcode untuk: {{ $b->kode_barang }}", e);
                    }
                @endforeach
            @endif
        });

        function changeRoom(ruanganId) {
            let currentUrl = new URL(window.location.href);
            if (ruanganId) {
                window.location.href = "{{ url('ruangan') }}/" + ruanganId + "/label?" + currentUrl.searchParams.toString();
            } else {
                window.location.href = "{{ route('barang.label.massal') }}?" + currentUrl.searchParams.toString();
            }
        }
    </script>
</body>
</html>
