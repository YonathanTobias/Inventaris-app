<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Peminjaman Aset & Alat Praktikum - STIKES Panti Waluya Malang</title>
    
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
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --primary-light: #eef2ff;
            --accent: #06b6d4;
            --dark: #0f172a;
            --light-bg: #f8fafc;
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--light-bg);
            color: #334155;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Navbar */
        .public-nav {
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            padding: 0.85rem 0;
            position: sticky;
            top: 0;
            z-index: 1020;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }

        .hero-banner {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
            color: #ffffff;
            padding: 3.5rem 0 3.2rem;
            position: relative;
            overflow: hidden;
        }

        .hero-banner::after {
            content: '';
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            background: radial-gradient(circle at 80% 20%, rgba(6, 182, 212, 0.22) 0%, transparent 60%);
            pointer-events: none;
        }

        .form-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.06);
            border: 1px solid rgba(226, 232, 240, 0.85);
            margin-top: -2.5rem;
            position: relative;
            z-index: 10;
        }

        .step-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #eef2ff;
            color: var(--primary);
            padding: 0.4rem 0.9rem;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .item-card {
            border: 1px solid var(--border-color);
            border-radius: 16px;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            background: #ffffff;
        }

        .item-card:hover {
            border-color: var(--primary);
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(79, 70, 229, 0.1);
        }

        .badge-code {
            background-color: #f1f5f9;
            color: #1e293b;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.78rem;
            padding: 0.25rem 0.55rem;
            border-radius: 6px;
            font-weight: 600;
            border: 1px solid #cbd5e1;
        }

        .btn-submit-pinjam {
            background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
            border: none;
            color: #ffffff;
            font-weight: 700;
            padding: 0.9rem 2.2rem;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.35);
            transition: all 0.2s ease;
        }

        .btn-submit-pinjam:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.45);
            color: #ffffff;
        }

        /* Floating Cart Button */
        .floating-cart-btn {
            position: fixed;
            bottom: 25px;
            right: 25px;
            z-index: 1040;
            background: linear-gradient(135deg, #4f46e5, #3730a3);
            color: #ffffff;
            border: none;
            border-radius: 50px;
            padding: 0.85rem 1.6rem;
            font-weight: 700;
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.45);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.25s ease;
            cursor: pointer;
        }

        .floating-cart-btn:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 12px 30px rgba(79, 70, 229, 0.55);
            color: #ffffff;
        }

        .cart-badge {
            background: #f59e0b;
            color: #0f172a;
            font-size: 0.8rem;
            font-weight: 800;
            padding: 0.2rem 0.55rem;
            border-radius: 50px;
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
            border: 1px solid #cbd5e1;
            background: #ffffff;
            font-weight: bold;
            font-size: 0.9rem;
            color: #334155;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .qty-stepper-btn:hover {
            background: #eef2ff;
            border-color: var(--primary);
            color: var(--primary);
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
                    <h6 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.02em;">STIKES PANTI WALUYA MALANG</h6>
                    <small class="text-muted" style="font-size: 0.7rem; font-weight: 600; letter-spacing: 0.04em;">PORTAL LAYANAN PEMINJAMAN ASET LAB</small>
                </div>
            </a>

            <div class="d-flex align-items-center gap-2">
                <!-- Tombol Buka Keranjang di Navbar -->
                <button type="button" class="btn btn-sm btn-outline-primary fw-bold rounded-pill px-3 py-1.5" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart">
                    <i class="fa-solid fa-cart-shopping me-1"></i> Keranjang
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

    <!-- OFFCANVAS KERANJANG BELANJA PEMINJAMAN -->
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
                <button type="button" class="btn btn-primary w-100 fw-bold py-2.5 rounded-3 shadow-sm" data-bs-dismiss="offcanvas" onclick="scrollToForm()">
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
                        <i class="fa-solid fa-shield-halved text-warning me-1.5"></i>Layanan Mandiri Resmi Civitas Akademika
                    </div>
                    <h1 class="fw-extrabold display-6 mb-2" style="letter-spacing: -0.03em;">
                        Peminjaman Aset & Alat Praktikum Laboratorium
                    </h1>
                    <p class="lead text-light text-opacity-75 mb-0" style="font-size: 1.05rem;">
                        Pilih beberapa alat sekaligus ke dalam <strong>Keranjang Peminjaman</strong>, lengkapi data peminjam, dan ajukan permohonan Anda. Pengajuan langsung ditinjau oleh Kepala Biro Sarana & Prasarana.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <div class="d-inline-flex flex-column gap-2 bg-white bg-opacity-10 p-3 rounded-4 border border-white border-opacity-15 text-start">
                        <div class="d-flex align-items-center gap-2 small">
                            <span class="badge bg-warning text-dark fw-bold rounded-circle" style="width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center;">1</span>
                            <span>Pilih Aset ke Keranjang</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 small">
                            <span class="badge bg-info text-dark fw-bold rounded-circle" style="width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center;">2</span>
                            <span>Approval Kepala Sarpras</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 small">
                            <span class="badge bg-primary text-white fw-bold rounded-circle" style="width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center;">3</span>
                            <span>Ambil Barang di Sarpras/Lab</span>
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

    <!-- FORM CONTAINER -->
    <div class="container mb-5" id="sectionFormPeminjaman">
        <div class="form-card p-4 p-md-5">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 py-2.5 small mb-4" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger rounded-3 py-2.5 small mb-4">
                    <i class="fa-solid fa-circle-exclamation me-2"></i> {{ $errors->first() }}
                </div>
            @endif

            <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <div class="step-pill">
                        <i class="fa-solid fa-file-pen"></i> Formulir Pengajuan
                    </div>
                    <h4 class="fw-bold text-dark mb-0">Isi Data Permohonan Peminjaman</h4>
                </div>

                <button type="button" class="btn btn-sm btn-outline-primary fw-semibold rounded-pill px-3" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart">
                    <i class="fa-solid fa-cart-shopping me-1"></i> Buka Keranjang (<span id="formCartCountBadge">0</span>)
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
                            <i class="fa-solid fa-cart-flatbed me-1"></i> 1. Daftar Aset yang Dipinjam (Keranjang Anda)
                        </h6>
                        <a href="#sectionKatalog" class="btn btn-link btn-sm p-0 text-decoration-none fw-semibold" style="font-size: 0.8rem;">
                            <i class="fa-solid fa-plus-circle me-1"></i> Tambah Aset Lain dari Katalog
                        </a>
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
                            <strong>Keranjang Anda masih kosong!</strong> Silakan pilih dan masukkan minimal 1 aset dari <strong>Katalog Aset di bawah</strong> untuk melanjutkan pengajuan.
                        </div>
                    </div>
                </div>

                <hr class="my-4 border-light-subtle">

                <!-- BAGIAN 2: IDENTITAS PEMINJAM -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h6 class="fw-bold text-primary small text-uppercase mb-2">
                            <i class="fa-solid fa-user-check me-1"></i> 2. Identitas Pemohon
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
                        <input type="text" name="nama_peminjam" class="form-control" placeholder="Nama lengkap beserta gelar jika ada" value="{{ old('nama_peminjam') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-secondary">Nomor Identitas (NIM / NIP / NIDN) <span class="text-danger">*</span></label>
                        <input type="text" name="nomor_identitas" class="form-control font-monospace" placeholder="Misal: 202301045 / NIP. 198..." value="{{ old('nomor_identitas') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-secondary">Program Studi / Unit Kerja <span class="text-danger">*</span></label>
                        <input type="text" name="prodi_unit" class="form-control" placeholder="Contoh: S1 Keperawatan / D3 Kebidanan / Biro Akademik" value="{{ old('prodi_unit') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-secondary">Nomor WhatsApp Aktif <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-success"><i class="fa-brands fa-whatsapp fa-lg"></i></span>
                            <input type="text" name="kontak_peminjam" class="form-control" placeholder="Misal: 081234567890 (Untuk konfirmasi status approval)" value="{{ old('kontak_peminjam') }}" required>
                        </div>
                    </div>
                </div>

                <hr class="my-4 border-light-subtle">

                <!-- BAGIAN 3: JADWAL & KEPERLUAN -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h6 class="fw-bold text-primary small text-uppercase mb-2">
                            <i class="fa-solid fa-calendar-days me-1"></i> 3. Jadwal & Keperluan Peminjaman
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
                        <textarea name="keperluan" class="form-control" rows="3" placeholder="Contoh: Praktikum Simulasi GADAR di Lapangan / Ujian OSCE Keperawatan Medikal Bedah / Acara Seminar BEM..." required>{{ old('keperluan') }}</textarea>
                    </div>
                </div>

                <!-- PERNYATAAN & SUBMIT -->
                <div class="p-3.5 bg-light rounded-3 border mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="checkPernyataan" required>
                        <label class="form-check-label small text-secondary" for="checkPernyataan">
                            Saya menyatakan bahwa data yang diisi adalah benar, dan saya bersedia mematuhi tata tertib peminjaman, menjaga keutuhan seluruh aset STIKES Panti Waluya Malang, serta mengembalikannya tepat waktu.
                        </label>
                    </div>
                </div>

                <div class="text-center text-md-end">
                    <button type="submit" class="btn-submit-pinjam" id="btnSubmitPengajuan">
                        <i class="fa-solid fa-paper-plane me-2"></i>
                        <span>Kirim Permohonan Peminjaman</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- KATALOG BARANG SIAP PINJAM -->
    <div class="container mb-5 pb-5" id="sectionKatalog">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">
                    <i class="fa-solid fa-boxes-stacked text-primary me-2"></i>Katalog Aset yang Diizinkan Dipinjam
                </h4>
                <p class="text-muted small mb-0">Klik tombol <strong>"+ Keranjang"</strong> pada aset yang dibutuhkan untuk memasukkannya ke permohonan.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill font-monospace">
                    {{ $barangs->count() }} Jenis Aset Siap Pinjam
                </span>
            </div>
        </div>

        <div class="row g-3">
            @forelse($barangs as $item)
            <div class="col-md-6 col-lg-4">
                <div class="item-card p-3 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge-code">{{ $item->kode_barang }}</span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill small">
                                <i class="fa-solid fa-circle-check me-1"></i>Sisa {{ $item->jumlah }} Unit
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
                            <i class="fa-solid fa-cart-plus me-1"></i> + Keranjang
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Belum ada aset yang diatur untuk dapat dipinjam.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="mt-auto bg-white border-top py-3.5 text-center small text-muted">
        <div class="container">
            <strong>STIKES Panti Waluya Malang</strong> &copy; {{ date('Y') }} &bull; Sistem Informasi Inventaris & Peminjaman Aset Laboratorium
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SCRIPT SISTEM KERANJANG PEMINJAMAN (LOAN CART) -->
    <script>
        // State keranjang tersimpan di LocalStorage agar tidak hilang saat refresh
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

            // Tampilkan notifikasi singkat
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

            // Format data untuk POST
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

        function scrollToForm() {
            let el = document.getElementById('sectionFormPeminjaman');
            if (el) {
                el.scrollIntoView({ behavior: 'smooth' });
            }
        }

        function showToastSuccess(msg) {
            // Animasi feedback visual sederhana
            let floatingBtn = document.getElementById('btnFloatingCart');
            floatingBtn.classList.add('animate__animated', 'animate__bounce');
            setTimeout(() => {
                floatingBtn.classList.remove('animate__animated', 'animate__bounce');
            }, 1000);
        }

        // Hapus cart saat submit sukses dilakukan (di form event)
        document.getElementById('formPeminjamanMandiri').addEventListener('submit', function(e) {
            if (loanCart.length === 0) {
                e.preventDefault();
                alert('Silakan pilih minimal 1 aset ke keranjang terlebih dahulu!');
                return;
            }
            // Kosongkan keranjang di localStorage untuk pengajuan berikutnya
            setTimeout(() => {
                localStorage.removeItem('stikes_loan_cart');
            }, 500);
        });

        // Inisialisasi saat pertama load
        document.addEventListener('DOMContentLoaded', function() {
            renderCart();
        });
    </script>
</body>
</html>
