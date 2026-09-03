<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPARTA-PW - Portal Peminjaman Aset & Ruangan STIKES Panti Waluya Malang</title>
    
    <!-- Favicon -->
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
        :root {
            --color-navy: #0D47A1;
            --color-vibrant: #2196F3;
            --color-sky: #90CAF9;
            --color-ice: #E3F2FD;
            
            --primary: #2196F3;
            --primary-dark: #0D47A1;
            --primary-light: #E3F2FD;
            --emerald: #0D47A1;
            --accent: #2196F3;
            --dark: #0a192f;
            --light-bg: #f4f8fb;
            --border-color: #dbeafe;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--light-bg);
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Navbar */
        .public-nav {
            background: #ffffff;
            border-bottom: 2px solid var(--color-ice);
            padding: 0.85rem 0;
            position: sticky;
            top: 0;
            z-index: 1020;
            box-shadow: 0 2px 8px rgba(13, 71, 161, 0.05);
        }

        .hero-banner {
            background: linear-gradient(135deg, #0D47A1 0%, #1565C0 50%, #2196F3 100%);
            color: #ffffff;
            padding: 3.5rem 0 3.2rem;
            position: relative;
            overflow: hidden;
        }

        .hero-banner::after {
            content: '';
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            background: radial-gradient(circle at 80% 20%, rgba(144, 202, 249, 0.3) 0%, transparent 60%);
            pointer-events: none;
        }

        .form-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(13, 71, 161, 0.08);
            border: 1px solid var(--color-sky);
            margin-top: -2.5rem;
            position: relative;
            z-index: 10;
        }

        .step-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--color-ice);
            color: var(--color-navy);
            padding: 0.4rem 0.9rem;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 700;
            border: 1px solid var(--color-sky);
        }

        .step-pill-emerald {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--color-ice);
            color: var(--color-navy);
            padding: 0.4rem 0.9rem;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 700;
            border: 1px solid var(--color-sky);
        }

        .item-card {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            background: #ffffff;
        }

        .item-card:hover {
            border-color: var(--color-vibrant);
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(33, 150, 243, 0.15);
        }

        .room-card:hover {
            border-color: var(--color-vibrant) !important;
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(13, 71, 161, 0.15) !important;
        }

        .badge-code {
            background-color: var(--color-ice);
            color: var(--color-navy);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.78rem;
            padding: 0.25rem 0.55rem;
            border-radius: 6px;
            font-weight: 700;
            border: 1px solid var(--color-sky);
            display: inline-block;
        }

        .btn-submit-pinjam {
            background: linear-gradient(135deg, #0D47A1 0%, #2196F3 100%);
            border: none;
            color: #ffffff;
            font-weight: 700;
            padding: 0.9rem 2.2rem;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(33, 150, 243, 0.35);
            transition: all 0.2s ease;
        }

        .btn-submit-pinjam:hover {
            background: linear-gradient(135deg, #0a3880 0%, #1976D2 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(33, 150, 243, 0.45);
            color: #ffffff;
        }

        .btn-submit-ruangan {
            background: linear-gradient(135deg, #0D47A1 0%, #2196F3 100%);
            border: none;
            color: #ffffff;
            font-weight: 700;
            padding: 0.9rem 2.2rem;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(13, 71, 161, 0.35);
            transition: all 0.2s ease;
        }

        .btn-submit-ruangan:hover {
            background: linear-gradient(135deg, #0a3880 0%, #1976D2 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(13, 71, 161, 0.45);
            color: #ffffff;
        }

        /* Floating Cart Button */
        .floating-cart-btn {
            position: fixed;
            bottom: 25px;
            right: 25px;
            z-index: 1040;
            background: linear-gradient(135deg, #0D47A1, #2196F3);
            color: #ffffff;
            border: none;
            border-radius: 50px;
            padding: 0.85rem 1.6rem;
            font-weight: 700;
            box-shadow: 0 8px 25px rgba(13, 71, 161, 0.4);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.25s ease;
            cursor: pointer;
        }

        .floating-cart-btn:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 12px 30px rgba(33, 150, 243, 0.55);
            color: #ffffff;
        }

        .cart-badge {
            background: #ffffff;
            color: var(--color-navy);
            font-size: 0.8rem;
            font-weight: 800;
            padding: 0.2rem 0.55rem;
            border-radius: 50px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        /* Cart Offcanvas */
        .offcanvas-cart {
            width: 440px !important;
            border-top-left-radius: 20px;
            border-bottom-left-radius: 20px;
        }

        .cart-item-row {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.85rem;
            margin-bottom: 0.75rem;
        }

        .qty-stepper-btn {
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            border: 1px solid var(--color-sky);
            background: #ffffff;
            font-weight: bold;
            font-size: 0.9rem;
            color: var(--color-navy);
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .qty-stepper-btn:hover {
            background: var(--color-ice);
            border-color: var(--color-vibrant);
            color: var(--color-vibrant);
        }

        /* Custom Tabs Styling */
        .nav-pills-portal .nav-link {
            color: #64748b;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .nav-pills-portal .nav-link.active {
            background: linear-gradient(135deg, #0D47A1 0%, #2196F3 100%) !important;
            color: #ffffff !important;
            border-color: transparent !important;
            box-shadow: 0 4px 14px rgba(13, 71, 161, 0.25) !important;
        }

        .nav-pills-portal .nav-link-ruangan.active {
            background: linear-gradient(135deg, #0D47A1 0%, #2196F3 100%) !important;
            color: #ffffff !important;
            border-color: transparent !important;
            box-shadow: 0 4px 14px rgba(13, 71, 161, 0.25) !important;
        }
    </style>
</head>
<body>

    <!-- NAVBAR ATAS -->
    <nav class="public-nav">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ url('/') }}" class="d-flex align-items-center text-decoration-none gap-2">
                <img src="{{ asset('images/logo-stikes.png') }}" alt="Logo STIKES Panti Waluya" style="height: 44px; width: auto; object-fit: contain;">
                <div>
                    <h5 class="fw-extrabold mb-0 text-dark" style="letter-spacing: -0.03em;">SPARTA-PW</h5>
                    <small class="text-muted" style="font-size: 0.68rem; font-weight: 600; letter-spacing: 0.04em;">PORTAL PEMINJAMAN ASET & RUANGAN STIKES PANTI WALUYA</small>
                </div>
            </a>

            <div class="d-flex align-items-center gap-2">
                <!-- Tombol Buka Keranjang di Navbar -->
                <button type="button" class="btn btn-sm btn-outline-primary fw-bold rounded-pill px-3 py-1.5" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart">
                    <i class="fa-solid fa-cart-shopping me-1"></i> Keranjang Aset
                    <span class="badge bg-primary text-white ms-1 rounded-pill" id="navCartCount">0</span>
                </button>

                <!-- Tombol Lacak Status -->
                <a href="{{ route('publik.lacak') }}" class="btn btn-sm btn-outline-secondary fw-semibold rounded-pill px-3 py-1.5">
                    <i class="fa-solid fa-magnifying-glass me-1"></i> Lacak Pengajuan
                </a>

                @auth
                    <!-- Jika Sudah Login -->
                    <a href="{{ route('barang.index') }}" class="btn btn-sm btn-primary fw-bold rounded-pill px-3 py-1.5 shadow-sm">
                        <i class="fa-solid fa-gauge me-1"></i> Dashboard Petugas
                    </a>
                @else
                    <!-- Jika Belum Login -->
                    <a href="{{ route('login') }}" class="btn btn-sm btn-dark fw-bold rounded-pill px-3.5 py-1.5 shadow-sm">
                        <i class="fa-solid fa-lock me-1 text-warning"></i> Login Petugas / Sarpras
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- FLOATING CART BUTTON -->
    <button type="button" class="floating-cart-btn" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart" id="btnFloatingCart">
        <i class="fa-solid fa-cart-shopping fs-5"></i>
        <span>Keranjang Pinjam</span>
        <span class="cart-badge" id="floatingCartCount">0</span>
    </button>

    <!-- OFFCANVAS KERANJANG BELANJA PEMINJAMAN ASET -->
    <div class="offcanvas offcanvas-end offcanvas-cart shadow-lg border-0" tabindex="-1" id="offcanvasCart">
        <div class="offcanvas-header border-bottom py-3">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
                <div>
                    <h6 class="offcanvas-title fw-bold text-dark mb-0">Keranjang Peminjaman Aset</h6>
                    <small class="text-muted" id="cartSummaryText">0 jenis aset dipilih</small>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-4 d-flex flex-column justify-content-between">
            <div id="cartItemsContainer">
                <!-- Diisi via JavaScript -->
            </div>

            <div class="border-top pt-3 mt-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-bold text-secondary">Total Unit yang Dipinjam:</span>
                    <span class="fw-bold fs-5 text-primary" id="cartTotalQty">0 Unit</span>
                </div>
                <button type="button" class="btn btn-primary w-100 fw-bold py-2.5 rounded-3 shadow-sm" data-bs-dismiss="offcanvas" onclick="scrollToFormAset()">
                    <i class="fa-solid fa-file-signature me-1"></i> Lanjut Isi Data Peminjam
                </button>
            </div>
        </div>
    </div>

    <!-- HERO SECTION -->
    <section class="hero-banner">
        <div class="container text-center text-lg-start">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="badge bg-white bg-opacity-10 border border-white border-opacity-25 px-3 py-1.5 rounded-pill mb-3 text-light small fw-semibold">
                        SPARTA-PW &bull; Layanan Mandiri Civitas Akademika
                    </div>
                    <h1 class="fw-extrabold display-6 mb-2" style="letter-spacing: -0.03em;">
                        Sistem Peminjaman Aset & Ruangan Terpadu
                    </h1>
                    <p class="lead text-light text-opacity-75 mb-0" style="font-size: 1.05rem;">
                        Layanan mandiri resmi bagi <strong>Dosen, Mahasiswa, dan Ormawa</strong> STIKES Panti Waluya Malang untuk peminjaman aset sarana prasarana serta peminjaman ruangan kegiatan kampus.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <div class="d-inline-flex flex-column gap-2 bg-white bg-opacity-10 p-3 rounded-4 border border-white border-opacity-15 text-start">
                        <div class="d-flex align-items-center gap-2 small">
                            <span class="badge bg-warning text-dark fw-bold rounded-circle" style="width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center;">1</span>
                            <span>Pilih Aset / Ruangan</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 small">
                            <span class="badge bg-info text-dark fw-bold rounded-circle" style="width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center;">2</span>
                            <span>Approval Kepala Sarpras</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 small">
                            <span class="badge bg-primary text-white fw-bold rounded-circle" style="width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center;">3</span>
                            <span>Serah Terima Fisik / Kunci</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 small">
                            <span class="badge bg-success text-white fw-bold rounded-circle" style="width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center;">4</span>
                            <span>Pengembalian Tepat Waktu</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FORM CONTAINER DENGAN TAB NAVIGASI -->
    <div class="container mb-5" id="sectionFormPortal">
        <div class="form-card p-4 p-md-5">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 py-2.5 small mb-4" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 py-2.5 small mb-4" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger rounded-3 py-2.5 small mb-4">
                    <i class="fa-solid fa-circle-exclamation me-2"></i> {{ $errors->first() }}
                </div>
            @endif

            <!-- PILIHAN TAB LAYANAN -->
            <ul class="nav nav-pills nav-fill nav-pills-portal gap-2 mb-4 p-1.5 bg-light rounded-4 border" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold py-3 rounded-3 d-flex align-items-center justify-content-center gap-2" id="pills-aset-tab" data-bs-toggle="pill" data-bs-target="#pills-aset" type="button" role="tab">
                        <i class="fa-solid fa-boxes-stacked fs-5"></i>
                        <span>Peminjaman Aset Sarpras</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link nav-link-ruangan fw-bold py-3 rounded-3 d-flex align-items-center justify-content-center gap-2" id="pills-ruangan-tab" data-bs-toggle="pill" data-bs-target="#pills-ruangan" type="button" role="tab">
                        <i class="fa-solid fa-door-open fs-5"></i>
                        <span>Peminjaman Ruangan Kegiatan</span>
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">
                
                <!-- ================= TAB 1: PEMINJAMAN ASET ================= -->
                <div class="tab-pane fade show active" id="pills-aset" role="tabpanel">
                    <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <div class="step-pill">
                                Form Aset
                            </div>
                            <h4 class="fw-bold text-dark mb-0">Formulir Pengajuan Peminjaman Aset</h4>
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-primary fw-semibold rounded-pill px-3" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart">
                            <i class="fa-solid fa-cart-shopping me-1"></i> Keranjang (<span id="formCartCountBadge">0</span>)
                        </button>
                    </div>

                    <form action="{{ route('publik.store') }}" method="POST" id="formPeminjamanMandiri">
                        @csrf

                        <!-- Hidden Input untuk Menyimpan Data Keranjang -->
                        <input type="hidden" name="cart_data" id="hiddenCartData" value="">

                        <!-- TABEL PREVIEW BARANG DI KERANJANG -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold text-primary small text-uppercase mb-0">
                                    1. Daftar Aset yang Dipinjam
                                </h6>
                                <a href="#sectionKatalogAset" class="btn btn-link btn-sm p-0 text-decoration-none fw-semibold" style="font-size: 0.8rem;">
                                    Lihat Semua di Katalog &darr;
                                </a>
                            </div>

                            <!-- LIVE SEARCH ASET & QUICK ADD LANGSUNG DARI FORM -->
                            <div class="mb-3 p-3 bg-light rounded-3 border">
                                <label class="form-label small fw-bold text-secondary mb-1">
                                    <i class="fa-solid fa-magnifying-glass text-primary me-1"></i> Cari & Tambah Barang Aset:
                                </label>
                                <div class="position-relative">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                                        <input type="text" id="liveSearchInput" class="form-control" placeholder="Ketik nama aset atau kode barang (contoh: Proyektor, Mic, Kabel)..." autocomplete="off" oninput="handleLiveItemSearch(this.value)" onfocus="handleLiveItemSearch(this.value)">
                                        <button class="btn btn-outline-secondary" type="button" onclick="clearLiveSearch()" title="Bersihkan">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- DROPDOWN HASIL LIVE SEARCH -->
                                    <div id="liveSearchResults" class="position-absolute w-100 bg-white shadow-lg rounded-3 border mt-1 p-2 d-none" style="z-index: 1050; max-height: 290px; overflow-y: auto;">
                                        <!-- Diisi otomatis via JavaScript -->
                                    </div>
                                </div>
                                <small class="text-muted d-block mt-1" style="font-size: 0.74rem;">
                                    Ketik nama atau kode barang untuk memilih langsung tanpa perlu scroll ke bawah.
                                </small>
                            </div>

                            <div class="table-responsive rounded-3 border">
                                <table class="table table-hover align-middle mb-0" id="tablePreviewCart">
                                    <thead class="bg-light small text-muted">
                                        <tr>
                                            <th class="ps-3" style="width: 40px;">No</th>
                                            <th>Nama Aset & Ruangan</th>
                                            <th class="text-center" style="width: 130px;">Jumlah Unit</th>
                                            <th class="text-center pe-3" style="width: 80px;">Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyPreviewCart">
                                        <!-- Diisi via JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                            <div id="emptyCartAlert" class="alert alert-warning py-3 px-3.5 small rounded-3 mt-2 mb-0 d-flex align-items-center gap-2">
                                <i class="fa-solid fa-circle-exclamation fs-4"></i>
                                <div>
                                    <strong>Keranjang Anda masih kosong!</strong> Gunakan kolom cari barang di atas atau pilih dari <strong>Katalog Aset di bawah</strong> untuk melanjutkan pengajuan.
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 border-light-subtle">

                        <!-- IDENTITAS PEMINJAM ASET -->
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-primary small text-uppercase mb-2">
                                    2. Identitas Pemohon
                                </h6>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-secondary">Status Pemohon <span class="text-danger">*</span></label>
                                <select name="kategori_peminjam" class="form-select" required>
                                    <option value="Mahasiswa" {{ old('kategori_peminjam') == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                    <option value="Dosen" {{ old('kategori_peminjam') == 'Dosen' ? 'selected' : '' }}>Dosen / Pengajar</option>
                                    <option value="Staf / Tendik" {{ old('kategori_peminjam') == 'Staf / Tendik' ? 'selected' : '' }}>Staf / Tenaga Kependidikan</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-secondary">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama_peminjam" class="form-control" placeholder="Nama lengkap " value="{{ old('nama_peminjam') }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-secondary">Nomor Identitas (NIM / NIP) <span class="text-danger">*</span></label>
                                <input type="text" name="nomor_identitas" class="form-control font-monospace" placeholder="Misal: 202301045 / NIP. 198..." value="{{ old('nomor_identitas') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-secondary">Program Studi / Unit Kerja <span class="text-danger">*</span></label>
                                <input type="text" name="prodi_unit" class="form-control" placeholder="Contoh: S1 Keperawatan / D4 MIK/ S1 Farmasi / IKM" value="{{ old('prodi_unit') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-secondary">Nomor WhatsApp Aktif <span class="text-danger">*</span></label>
                                <input type="text" name="kontak_peminjam" class="form-control" placeholder="Misal: 081234567890 (Untuk konfirmasi persetujuan)" value="{{ old('kontak_peminjam') }}" required>
                            </div>
                        </div>

                        <hr class="my-4 border-light-subtle">

                        <!-- JADWAL & KEPERLUAN ASET -->
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-primary small text-uppercase mb-2">
                                    3. Jadwal & Keperluan Peminjaman
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-secondary">Tanggal Rencana Peminjaman / Diambil <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_pinjam" class="form-control" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-secondary">Rencana Tanggal Pengembalian <span class="text-danger">*</span></label>
                                <input type="date" name="tenggat_kembali" class="form-control" value="{{ old('tenggat_kembali', date('Y-m-d', strtotime('+3 days'))) }}" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold small text-secondary">Keperluan / Alasan Peminjaman <span class="text-danger">*</span></label>
                                <textarea name="keperluan" class="form-control" rows="3" placeholder="Contoh: Acara Seminar IKM/HIMA, Kegiatan IKM/Prodi" required>{{ old('keperluan') }}</textarea>
                            </div>
                        </div>

                        <!-- PERNYATAAN & SUBMIT ASET -->
                        <div class="p-3.5 bg-light rounded-3 border mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checkPernyataanAset" required>
                                <label class="form-check-label small text-secondary" for="checkPernyataanAset">
                                    Saya menyatakan bahwa data yang diisi adalah benar, dan saya bersedia mematuhi tata tertib peminjaman, menjaga keutuhan seluruh aset STIKES Panti Waluya Malang, serta mengembalikannya tepat waktu.
                                </label>
                            </div>
                        </div>

                        <div class="text-center text-md-end">
                            <button type="submit" class="btn-submit-pinjam" id="btnSubmitPengajuan">
                                <span>Kirim Permohonan Peminjaman Aset</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- ================= TAB 2: PEMINJAMAN RUANGAN ================= -->
                <div class="tab-pane fade" id="pills-ruangan" role="tabpanel">
                    <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <div class="step-pill-emerald">
                                Form Ruangan
                            </div>
                            <h4 class="fw-bold text-dark mb-0">Formulir Peminjaman Ruangan Kegiatan</h4>
                        </div>
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill font-monospace small">
                            {{ $ruangans->count() }} Ruangan Siap Dipinjam
                        </span>
                    </div>

                    <form action="{{ route('publik.ruangan.store') }}" method="POST" id="formBookingRuangan">
                        @csrf

                        <!-- PILIH RUANGAN & JADWAL -->
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-success small text-uppercase mb-2">
                                    1. Pilihan Ruangan & Waktu Pemakaian
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-secondary">Pilih Ruangan <span class="text-danger">*</span></label>
                                <select name="ruangan_id" id="selectRuanganPublik" class="form-select" required onchange="cekJadwalRuangan(this.value)">
                                    <option value="">-- Pilih Ruangan (Hanya Ruangan yang Diizinkan Dipinjam) --</option>
                                    @foreach($ruangans as $r)
                                        <option value="{{ $r->id }}" {{ old('ruangan_id') == $r->id ? 'selected' : '' }}>
                                            {{ $r->nama_ruangan }} [{{ $r->kode_ruangan }}]
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-secondary">Tanggal Pemakaian <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_pemakaian" id="inputTanggalRuangan" class="form-control" value="{{ old('tanggal_pemakaian', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required onchange="triggerCekJadwal()">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-secondary">Jam Mulai Kegiatan <span class="text-danger">*</span></label>
                                <input type="time" name="jam_mulai" class="form-control" value="{{ old('jam_mulai', '08:00') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-secondary">Jam Selesai Kegiatan <span class="text-danger">*</span></label>
                                <input type="time" name="jam_selesai" class="form-control" value="{{ old('jam_selesai', '12:00') }}" required>
                            </div>

                            <!-- Box Info Jadwal Terisi -->
                            <div class="col-12" id="boxJadwalTerisi" style="display: none;">
                                <div class="p-3 bg-light rounded-3 border">
                                    <div class="fw-bold small text-secondary mb-1">
                                        Jadwal Pemakaian Ruangan Ini pada Tanggal Tersebut:
                                    </div>
                                    <div id="listJadwalRuangan" class="small text-muted">
                                        <!-- Diisi via AJAX -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 border-light-subtle">

                        <!-- IDENTITAS PEMOHON RUANGAN -->
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-success small text-uppercase mb-2">
                                    2. Identitas Pemohon / Penanggung Jawab
                                </h6>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-secondary">Status Pemohon <span class="text-danger">*</span></label>
                                <select name="kategori_peminjam" class="form-select" required>
                                    <option value="Mahasiswa / Ormawa" {{ old('kategori_peminjam') == 'Mahasiswa / Ormawa' ? 'selected' : '' }}>Mahasiswa / Organisasi (IKM/HIMA)</option>
                                    <option value="Dosen" {{ old('kategori_peminjam') == 'Dosen' ? 'selected' : '' }}>Dosen / Pengajar</option>
                                    <option value="Staf / Tendik" {{ old('kategori_peminjam') == 'Staf / Tendik' ? 'selected' : '' }}>Staf / Tenaga Kependidikan</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-secondary">Nama Pemohon <span class="text-danger">*</span></label>
                                <input type="text" name="nama_peminjam" class="form-control" placeholder="Nama lengkap" value="{{ old('nama_peminjam') }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-secondary">Nomor Identitas (NIM / NIP) <span class="text-danger">*</span></label>
                                <input type="text" name="nomor_identitas" class="form-control font-monospace" placeholder="Misal: 202301045" value="{{ old('nomor_identitas') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-secondary">Program Studi / Nama Organisasi <span class="text-danger">*</span></label>
                                <input type="text" name="prodi_unit" class="form-control" placeholder="Contoh: IKM STIKES / HIMA/ Prodi" value="{{ old('prodi_unit') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-secondary">Nomor WhatsApp Aktif <span class="text-danger">*</span></label>
                                <input type="text" name="kontak_peminjam" class="form-control" placeholder="Misal: 081234567890 (Untuk konfirmasi kunci & approval)" value="{{ old('kontak_peminjam') }}" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold small text-secondary">Keperluan / Acara Kegiatan <span class="text-danger">*</span></label>
                                <textarea name="keperluan" class="form-control" rows="3" placeholder="Contoh: Acara Seminar IKM/HIMA, Kegiatan IKM/Prodi" required>{{ old('keperluan') }}</textarea>
                            </div>
                        </div>

                        <!-- PERNYATAAN & SUBMIT RUANGAN -->
                        <div class="p-3.5 bg-light rounded-3 border mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checkPernyataanRuangan" required>
                                <label class="form-check-label small text-secondary" for="checkPernyataanRuangan">
                                    Saya bertanggung jawab penuh atas penggunaan ruangan, menjaga kebersihan, mematikan AC/lampu/perangkat proyektor setelah selesai, serta mengembalikan kunci ke Sarpras tepat waktu.
                                </label>
                            </div>
                        </div>

                        <div class="text-center text-md-end">
                            <button type="submit" class="btn-submit-ruangan">
                                <span>Kirim Permohonan Booking Ruangan</span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- KATALOG BARANG SIAP PINJAM -->
    <div class="container mb-5 pb-5" id="sectionKatalogAset">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">
                    Katalog Aset Sarpras yang Dapat Dipinjam
                </h4>
                <p class="text-muted small mb-0">Klik tombol <strong>"+ Keranjang"</strong> pada aset yang dibutuhkan untuk memasukkannya ke permohonan.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span id="catalogCountBadge" class="badge bg-light text-secondary border px-3 py-2 rounded-pill font-monospace">
                    {{ $barangs->count() }} Jenis Aset Siap Pinjam
                </span>
            </div>
        </div>

        <!-- SEARCH INPUT UNTUK FILTER KATALOG -->
        <div class="card p-3 mb-4 bg-light border-0 shadow-sm">
            <div class="row g-2 align-items-center">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" id="catalogSearchInput" class="form-control" placeholder="Cari di katalog (ketik nama aset, kode barang, atau nama ruangan)..." oninput="filterCatalogCards(this.value)">
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <button type="button" class="btn btn-sm btn-outline-secondary w-100 w-md-auto" onclick="document.getElementById('catalogSearchInput').value=''; filterCatalogCards('');">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset Filter
                    </button>
                </div>
            </div>
        </div>

        <div class="row g-3" id="catalogCardsRow">
            @forelse($barangs as $item)
            <div class="col-md-6 col-lg-4 catalog-item-card" 
                 data-name="{{ strtolower($item->nama_barang) }}" 
                 data-code="{{ strtolower($item->kode_barang) }}" 
                 data-room="{{ strtolower($item->ruangan->nama_ruangan ?? '') }}"
                 data-cat="{{ strtolower($item->kategori->nama_kategori ?? '') }}">
                <div class="item-card p-3 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge-code">{{ $item->kode_barang }}</span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill small">
                                Sisa {{ $item->jumlah }} Unit
                            </span>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">{{ $item->nama_barang }}</h6>
                        <p class="text-muted small mb-2" style="font-size: 0.8rem;">
                            Lokasi: {{ $item->ruangan->nama_ruangan ?? '-' }}
                        </p>
                        @if($item->keterangan)
                            <p class="text-secondary small mb-0 text-truncate" style="font-size: 0.75rem;" title="{{ $item->keterangan }}">
                                {{ $item->keterangan }}
                            </p>
                        @endif
                    </div>

                    <div class="mt-3 pt-2.5 border-top d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-1">
                            <button type="button" class="qty-stepper-btn" onclick="stepItemQty('{{ $item->id }}', -1)">-</button>
                            <input type="number" id="catalogQty{{ $item->id }}" class="form-control form-control-sm text-center font-monospace px-1" value="1" min="1" max="{{ $item->jumlah }}" style="width: 44px; height: 28px;">
                            <button type="button" class="qty-stepper-btn" onclick="stepItemQty('{{ $item->id }}', 1)">+</button>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary fw-bold rounded-pill px-3 shadow-sm" onclick="addToCart('{{ $item->id }}', '{{ addslashes($item->nama_barang) }}', '{{ $item->kode_barang }}', '{{ addslashes($item->ruangan->nama_ruangan ?? '-') }}', {{ $item->jumlah }})">
                            + Keranjang
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Belum ada aset yang diatur untuk dapat dipinjam.</p>
            </div>
            @endforelse
            <div id="catalogNoMatchMessage" class="col-12 text-center py-5 d-none">
                <p class="text-muted mb-1"><i class="fa-solid fa-magnifying-glass fa-2x opacity-50 mb-2"></i></p>
                <h6 class="fw-bold text-secondary">Aset Tidak Ditemukan</h6>
                <p class="small text-muted mb-0">Tidak ada item yang sesuai dengan kata kunci pencarian Anda.</p>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="mt-auto bg-white border-top py-3.5 text-center small text-muted">
        <div class="container">
            <strong>SPARTA-PW</strong> &copy; {{ date('Y') }} &bull; STIKES Panti Waluya Malang &bull; Sistem Peminjaman Aset & Ruangan Terpadu Akademik
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SCRIPT KERANJANG ASET & JADWAL RUANGAN -->
    <script>
        @php
            $itemSearchList = $barangs->map(function($b) {
                return [
                    'id' => (string) $b->id,
                    'nama' => $b->nama_barang,
                    'kode' => $b->kode_barang,
                    'ruangan' => $b->ruangan->nama_ruangan ?? '-',
                    'stok' => (int) $b->jumlah,
                    'kategori' => $b->kategori->nama_kategori ?? '-'
                ];
            });
        @endphp
        // Data Aset Tersedia untuk Pencarian Cepat
        const allAvailableItems = {!! json_encode($itemSearchList) !!};

        function handleLiveItemSearch(keyword) {
            let kw = (keyword || '').trim().toLowerCase();
            let resultsBox = document.getElementById('liveSearchResults');
            if (!resultsBox) return;

            if (kw.length === 0) {
                renderLiveSearchResults(allAvailableItems.slice(0, 6), '');
                resultsBox.classList.remove('d-none');
                return;
            }

            let filtered = allAvailableItems.filter(item => {
                return item.nama.toLowerCase().includes(kw) || 
                       item.kode.toLowerCase().includes(kw) || 
                       item.ruangan.toLowerCase().includes(kw) ||
                       item.kategori.toLowerCase().includes(kw);
            });

            renderLiveSearchResults(filtered, kw);
            resultsBox.classList.remove('d-none');
        }

        function renderLiveSearchResults(items, kw) {
            let resultsBox = document.getElementById('liveSearchResults');
            if (!resultsBox) return;

            if (items.length === 0) {
                resultsBox.innerHTML = `
                    <div class="p-3 text-center text-muted small">
                        <i class="fa-solid fa-circle-question me-1"></i> Tidak ditemukan aset dengan kata kunci "<strong>${kw}</strong>".
                    </div>
                `;
                return;
            }

            let html = '<div class="list-group list-group-flush small">';
            items.forEach(item => {
                let isAlreadyInCart = loanCart.some(c => c.id == item.id);
                let safeNama = item.nama.replace(/'/g, "\\'");
                let safeRuangan = item.ruangan.replace(/'/g, "\\'");
                html += `
                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-2.5 rounded-2 mb-1">
                        <div>
                            <strong class="text-dark d-block">${item.nama}</strong>
                            <div class="d-flex align-items-center gap-1.5 mt-0.5">
                                <span class="badge-code py-0" style="font-size: 0.68rem;">${item.kode}</span>
                                <small class="text-muted" style="font-size: 0.72rem;">${item.ruangan}</small>
                                <span class="badge bg-success-subtle text-success py-0" style="font-size: 0.68rem;">Sisa ${item.stok} unit</span>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm ${isAlreadyInCart ? 'btn-outline-primary' : 'btn-primary'} fw-bold rounded-pill px-2.5 py-1" onclick="quickAddFromSearch('${item.id}', '${safeNama}', '${item.kode}', '${safeRuangan}', ${item.stok})">
                                ${isAlreadyInCart ? '+ Tambah' : '+ Pilih'}
                            </button>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            resultsBox.innerHTML = html;
        }

        function quickAddFromSearch(id, nama, kode, ruangan, maxStok) {
            addToCart(id, nama, kode, ruangan, maxStok);
            clearLiveSearch();
        }

        function clearLiveSearch() {
            let input = document.getElementById('liveSearchInput');
            if (input) input.value = '';
            let resultsBox = document.getElementById('liveSearchResults');
            if (resultsBox) resultsBox.classList.add('d-none');
        }

        // Filter Live Kartu Katalog di Bawah
        function filterCatalogCards(keyword) {
            let kw = (keyword || '').trim().toLowerCase();
            let cards = document.querySelectorAll('.catalog-item-card');
            let matchedCount = 0;

            cards.forEach(card => {
                let name = card.getAttribute('data-name') || '';
                let code = card.getAttribute('data-code') || '';
                let room = card.getAttribute('data-room') || '';
                let cat = card.getAttribute('data-cat') || '';

                if (!kw || name.includes(kw) || code.includes(kw) || room.includes(kw) || cat.includes(kw)) {
                    card.style.display = 'block';
                    matchedCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            let noMatch = document.getElementById('catalogNoMatchMessage');
            if (noMatch) {
                noMatch.classList.toggle('d-none', matchedCount > 0);
            }

            let countBadge = document.getElementById('catalogCountBadge');
            if (countBadge) {
                countBadge.innerText = `${matchedCount} Jenis Aset Ditemukan`;
            }
        }

        // Tutup dropdown search saat user klik di luar area
        document.addEventListener('click', function(e) {
            let searchBox = document.getElementById('liveSearchResults');
            let searchInput = document.getElementById('liveSearchInput');
            if (searchBox && !searchBox.contains(e.target) && e.target !== searchInput) {
                searchBox.classList.add('d-none');
            }
        });

        // State keranjang tersimpan di LocalStorage
        let loanCart = [];

        try {
            let saved = localStorage.getItem('stikes_loan_cart');
            if (saved) {
                loanCart = JSON.parse(saved);
            }
        } catch (e) {
            loanCart = [];
        }

        function saveCart() {
            try {
                localStorage.setItem('stikes_loan_cart', JSON.stringify(loanCart));
            } catch (e) {}
            renderCart();
        }

        function stepItemQty(itemId, step) {
            let input = document.getElementById('catalogQty' + itemId);
            if (!input) return;
            let current = parseInt(input.value) || 1;
            let min = parseInt(input.min) || 1;
            let max = parseInt(input.max) || 999;
            let nextVal = current + step;
            if (nextVal >= min && nextVal <= max) {
                input.value = nextVal;
            }
        }

        function addToCart(id, nama, kode, ruangan, maxStok) {
            let qtyInput = document.getElementById('catalogQty' + id);
            let qty = parseInt(qtyInput ? qtyInput.value : 1) || 1;

            if (qty < 1) qty = 1;
            if (qty > maxStok) {
                alert('Jumlah unit melebihi stok yang tersedia (' + maxStok + ' unit)!');
                return;
            }

            let existing = loanCart.find(item => item.id == id);
            if (existing) {
                let newQty = existing.qty + qty;
                if (newQty > maxStok) {
                    alert('Total di keranjang (' + newQty + ') melebihi batas stok yang ada (' + maxStok + ' unit)!');
                    existing.qty = maxStok;
                } else {
                    existing.qty = newQty;
                }
            } else {
                loanCart.push({
                    id: id,
                    nama: nama,
                    kode: kode,
                    ruangan: ruangan,
                    max: maxStok,
                    qty: qty
                });
            }

            saveCart();
            showToastSuccess(nama + ' (' + qty + ' unit) ditambahkan ke keranjang!');
        }

        function updateCartItemQty(id, delta) {
            let item = loanCart.find(i => i.id == id);
            if (!item) return;
            let next = item.qty + delta;
            if (next <= 0) {
                removeFromCart(id);
                return;
            }
            if (next > item.max) {
                alert('Maksimal unit tersedia: ' + item.max);
                return;
            }
            item.qty = next;
            saveCart();
        }

        function removeFromCart(id) {
            loanCart = loanCart.filter(item => item.id != id);
            saveCart();
        }

        function renderCart() {
            let totalJenis = loanCart.length;
            let totalUnit = loanCart.reduce((sum, item) => sum + item.qty, 0);

            // Update badge counts
            document.getElementById('navCartCount').innerText = totalJenis;
            document.getElementById('floatingCartCount').innerText = totalJenis;
            document.getElementById('formCartCountBadge').innerText = totalJenis;
            document.getElementById('cartSummaryText').innerText = totalJenis + ' jenis aset dipilih';
            document.getElementById('cartTotalQty').innerText = totalUnit + ' Unit';

            // Floating button visibility
            let floatingBtn = document.getElementById('btnFloatingCart');
            if (totalJenis > 0) {
                floatingBtn.style.display = 'flex';
            } else {
                floatingBtn.style.display = 'none';
            }

            // Render Offcanvas Drawer
            let offcanvasContainer = document.getElementById('cartItemsContainer');
            if (loanCart.length === 0) {
                offcanvasContainer.innerHTML = `
                    <div class="text-center py-5 text-muted">
                        <i class="fa-solid fa-cart-shopping fa-3x opacity-25 mb-3"></i>
                        <h6 class="fw-bold">Keranjang Masih Kosong</h6>
                        <p class="small">Pilih aset praktikum dari katalog di halaman utama untuk ditambahkan ke keranjang.</p>
                    </div>
                `;
            } else {
                let html = '';
                loanCart.forEach(item => {
                    html += `
                        <div class="cart-item-row">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div>
                                    <strong class="d-block text-dark small">${item.nama}</strong>
                                    <span class="badge-code" style="font-size: 0.72rem;">${item.kode}</span>
                                    <small class="text-muted d-block" style="font-size: 0.72rem;">${item.ruangan}</small>
                                </div>
                                <button type="button" class="btn btn-link text-danger p-0" onclick="removeFromCart('${item.id}')" title="Hapus dari keranjang">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2 pt-1 border-top">
                                <small class="text-muted">Jumlah: (Maks: ${item.max})</small>
                                <div class="d-flex align-items-center gap-1.5">
                                    <button type="button" class="qty-stepper-btn" onclick="updateCartItemQty('${item.id}', -1)">-</button>
                                    <span class="fw-bold font-monospace px-2">${item.qty}</span>
                                    <button type="button" class="qty-stepper-btn" onclick="updateCartItemQty('${item.id}', 1)">+</button>
                                </div>
                            </div>
                        </div>
                    `;
                });
                offcanvasContainer.innerHTML = html;
            }

            // Render Preview Table di Form Pengajuan
            let tbodyPreview = document.getElementById('tbodyPreviewCart');
            let emptyAlert = document.getElementById('emptyCartAlert');
            let tablePreview = document.getElementById('tablePreviewCart');
            let btnSubmit = document.getElementById('btnSubmitPengajuan');
            let hiddenCartData = document.getElementById('hiddenCartData');

            hiddenCartData.value = JSON.stringify(loanCart.map(i => ({ barang_id: i.id, jumlah: i.qty })));

            if (loanCart.length === 0) {
                tbodyPreview.innerHTML = '';
                tablePreview.style.display = 'none';
                emptyAlert.style.display = 'flex';
                btnSubmit.disabled = true;
            } else {
                emptyAlert.style.display = 'none';
                tablePreview.style.display = 'table';
                btnSubmit.disabled = false;

                let tableHtml = '';
                loanCart.forEach((item, index) => {
                    tableHtml += `
                        <tr>
                            <td class="ps-3 fw-bold text-muted">${index + 1}</td>
                            <td>
                                <div class="fw-bold text-dark">${item.nama}</div>
                                <div class="d-flex align-items-center gap-1.5 mt-0.5">
                                    <span class="badge-code py-0">${item.kode}</span>
                                    <small class="text-muted">${item.ruangan}</small>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <button type="button" class="qty-stepper-btn" onclick="updateCartItemQty('${item.id}', -1)">-</button>
                                    <span class="fw-bold font-monospace px-2">${item.qty}</span>
                                    <button type="button" class="qty-stepper-btn" onclick="updateCartItemQty('${item.id}', 1)">+</button>
                                </div>
                            </td>
                            <td class="text-center pe-3">
                                <button type="button" class="btn btn-sm btn-outline-danger border-0 p-1" onclick="removeFromCart('${item.id}')" title="Hapus Aset">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
                tbodyPreview.innerHTML = tableHtml;
            }
        }

        function scrollToFormAset() {
            let tabBtn = document.getElementById('pills-aset-tab');
            if (tabBtn) tabBtn.click();
            let el = document.getElementById('sectionFormPortal');
            if (el) el.scrollIntoView({ behavior: 'smooth' });
        }

        function showToastSuccess(msg) {
            let floatingBtn = document.getElementById('btnFloatingCart');
            floatingBtn.classList.add('animate__animated', 'animate__bounce');
            setTimeout(() => {
                floatingBtn.classList.remove('animate__animated', 'animate__bounce');
            }, 1000);
        }

        // Hapus cart saat submit sukses dilakukan (di form aset)
        document.getElementById('formPeminjamanMandiri').addEventListener('submit', function(e) {
            if (loanCart.length === 0) {
                e.preventDefault();
                alert('Silakan pilih minimal 1 aset ke keranjang terlebih dahulu!');
                return;
            }
            setTimeout(() => {
                localStorage.removeItem('stikes_loan_cart');
            }, 500);
        });

        // AJAX Cek Jadwal Ruangan
        function triggerCekJadwal() {
            let sel = document.getElementById('selectRuanganPublik');
            if (sel && sel.value) {
                cekJadwalRuangan(sel.value);
            }
        }

        function cekJadwalRuangan(ruanganId) {
            let tgl = document.getElementById('inputTanggalRuangan').value;
            let box = document.getElementById('boxJadwalTerisi');
            let list = document.getElementById('listJadwalRuangan');

            if (!ruanganId || !tgl) {
                box.style.display = 'none';
                return;
            }

            fetch(`{{ route('publik.ruangan.jadwal') }}?ruangan_id=${ruanganId}&tanggal=${tgl}`)
                .then(res => res.json())
                .then(data => {
                    if (data && data.length > 0) {
                        let html = '<ul class="mb-0 ps-3">';
                        data.forEach(item => {
                            html += `<li><strong>${item.jam_mulai.substring(0,5)} - ${item.jam_selesai.substring(0,5)} WIB</strong>: ${item.nama_peminjam} (${item.keperluan}) - <span class="badge bg-secondary py-0">${item.status}</span></li>`;
                        });
                        html += '</ul>';
                        list.innerHTML = html;
                        box.style.display = 'block';
                    } else {
                        list.innerHTML = '<span class="text-success"><i class="fa-solid fa-circle-check me-1"></i>Belum ada jadwal pemakaian di tanggal ini (Ruangan Masih Kosong).</span>';
                        box.style.display = 'block';
                    }
                })
                .catch(err => {
                    box.style.display = 'none';
                });
        }

        // Inisialisasi saat load
        document.addEventListener('DOMContentLoaded', function() {
            renderCart();

            // Hash navigation tab support (misal: /#tab-ruangan)
            if (window.location.hash === '#tab-ruangan') {
                let tabRuangan = document.getElementById('pills-ruangan-tab');
                if (tabRuangan) tabRuangan.click();
            }
        });
    </script>
</body>
</html>
