<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPARTA-PW - Portal Peminjaman Aset & Ruangan STIKES Panti Waluya Malang</title>
    
    <!-- Theme Script (Instant execution to prevent flash) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('sparta_theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>

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

    <!-- Custom Portal Publik CSS -->
    <link rel="stylesheet" href="{{ asset('css/portal-publik.css') }}">
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
                <!-- Theme Switch Toggle Button -->
                <button type="button" class="theme-toggle-btn-public" id="btnThemeTogglePublic" onclick="togglePublicTheme()" title="Ganti Mode Gelap / Terang" aria-label="Toggle theme">
                    <i class="fa-solid fa-moon" id="themeIconPublic"></i>
                </button>

                <!-- Tombol Buka Daftar Pinjam di Navbar -->
                <button type="button" class="btn btn-sm btn-outline-primary fw-bold rounded-pill px-3 py-1.5" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart">
                    <i class="fa-solid fa-clipboard-list me-1"></i> Daftar Pinjam
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
        <i class="fa-solid fa-clipboard-list fs-5"></i>
        <span>Daftar Pinjam</span>
        <span class="cart-badge" id="floatingCartCount">0</span>
    </button>

    <!-- OFFCANVAS DAFTAR PINJAM PEMINJAMAN ASET -->
    <div class="offcanvas offcanvas-end offcanvas-cart shadow-lg border-0" tabindex="-1" id="offcanvasCart">
        <div class="offcanvas-header border-bottom py-3">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                    <i class="fa-solid fa-clipboard-list"></i>
                </div>
                <div>
                    <h6 class="offcanvas-title fw-bold text-dark mb-0">Daftar Pinjam Aset</h6>
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
                            <span>Buka Ruangan / Serah Terima Aset</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 small">
                            <span class="badge bg-success text-white fw-bold rounded-circle" style="width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center;">4</span>
                            <span>Selesai & Ruangan Dikunci Petugas</span>
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

            @if(isset($errors) && $errors->any())
                <div class="alert alert-danger rounded-4 p-3 mb-4 shadow-sm" role="alert">
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
                            <i class="fa-solid fa-clipboard-list me-1"></i> Daftar Pinjam (<span id="formCartCountBadge">0</span>)
                        </button>
                    </div>

                    <form action="{{ route('publik.store') }}" method="POST" id="formPeminjamanMandiri">
                        @csrf

                        <!-- Hidden Input untuk Menyimpan Data Keranjang/Daftar Pinjam -->
                        <input type="hidden" name="cart_data" id="hiddenCartData" value="">

                        <!-- TABEL PREVIEW BARANG DI DAFTAR PINJAM -->
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
                                    <strong>Daftar pinjam Anda masih kosong!</strong> Gunakan kolom cari barang di atas atau pilih dari <strong>Katalog Aset di bawah</strong> untuk melanjutkan pengajuan.
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
                        <div class="card-pernyataan p-3.5 mb-4 rounded-3 shadow-sm" onclick="document.getElementById('checkPernyataanAset').click();">
                            <div class="form-check d-flex align-items-start gap-3 m-0" onclick="event.stopPropagation();">
                                <input class="form-check-input check-pernyataan flex-shrink-0 mt-1" type="checkbox" id="checkPernyataanAset" required>
                                <label class="form-check-label text-pernyataan user-select-none" for="checkPernyataanAset">
                                    <strong class="d-block text-dark mb-1 fs-6">
                                        Pernyataan Tanggung Jawab & Persetujuan Pemohon:
                                    </strong>
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
                                <h6 class="fw-bold text-primary small text-uppercase mb-2">
                                    1. Pilihan Ruangan & Waktu Pemakaian
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-secondary">Pilih Ruangan <span class="text-danger">*</span></label>
                                
                                <!-- Hidden input untuk nilai ruangan_id yang terpilih -->
                                <input type="hidden" name="ruangan_id" id="selectRuanganPublik" value="{{ old('ruangan_id') }}" required>

                                <!-- BOX RUANGAN TERPILIH -->
                                <div id="selectedRuanganCard" class="p-2.5 bg-light rounded-3 border d-flex justify-content-between align-items-center {{ old('ruangan_id') ? '' : 'd-none' }}">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="p-2 rounded-2 bg-success-subtle text-success fw-bold" style="font-size: 0.9rem;">
                                            <i class="fa-solid fa-door-open"></i>
                                        </span>
                                        <div>
                                            <strong class="d-block text-dark small" id="selectedRuanganNamaText">-</strong>
                                            <span class="badge-code py-0" id="selectedRuanganKodeText" style="font-size: 0.7rem;">-</span>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1" onclick="resetSelectedRuangan()">
                                        <i class="fa-solid fa-rotate-left me-1"></i> Ganti
                                    </button>
                                </div>

                                <!-- INPUT PENCARIAN RUANGAN LIVE -->
                                <div id="searchRuanganWrapper" class="position-relative {{ old('ruangan_id') ? 'd-none' : '' }}">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                                        <input type="text" id="inputSearchRuangan" class="form-control" placeholder="Ketik nama ruangan (contoh: Aula, Lab Komputer, Teori)..." autocomplete="off" oninput="handleSearchRuanganLive(this.value)" onfocus="handleSearchRuanganLive(this.value)">
                                        <button class="btn btn-outline-secondary" type="button" onclick="clearSearchRuanganInput()" title="Bersihkan">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>

                                    <!-- DROPDOWN HASIL PENCARIAN RUANGAN -->
                                    <div id="dropdownRuanganResults" class="position-absolute w-100 bg-white shadow-lg rounded-3 border mt-1 p-2 d-none" style="z-index: 1050; max-height: 260px; overflow-y: auto;">
                                        <!-- Diisi otomatis via JavaScript -->
                                    </div>
                                </div>
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
                                <h6 class="fw-bold text-primary small text-uppercase mb-2">
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
                                <input type="text" name="kontak_peminjam" class="form-control" placeholder="Misal: 081234567890 (Untuk konfirmasi buka ruangan & approval)" value="{{ old('kontak_peminjam') }}" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold small text-secondary">Keperluan / Acara Kegiatan <span class="text-danger">*</span></label>
                                <textarea name="keperluan" class="form-control" rows="3" placeholder="Contoh: Acara Seminar IKM/HIMA, Kegiatan IKM/Prodi" required>{{ old('keperluan') }}</textarea>
                            </div>
                        </div>

                        <!-- PERNYATAAN & SUBMIT RUANGAN -->
                        <div class="card-pernyataan p-3.5 mb-4 rounded-3 shadow-sm" onclick="document.getElementById('checkPernyataanRuangan').click();">
                            <div class="form-check d-flex align-items-start gap-3 m-0" onclick="event.stopPropagation();">
                                <input class="form-check-input check-pernyataan flex-shrink-0 mt-1" type="checkbox" id="checkPernyataanRuangan" required>
                                <label class="form-check-label text-pernyataan user-select-none" for="checkPernyataanRuangan">
                                    <strong class="d-block text-dark mb-1 fs-6">
                                        Pernyataan Tanggung Jawab & Persetujuan Pemohon:
                                    </strong>
                                    Saya bertanggung jawab penuh atas penggunaan ruangan, menjaga kebersihan, mematikan AC/lampu/perangkat proyektor setelah selesai, serta mengonfirmasi kepada petugas Sarpras saat kegiatan telah selesai agar ruangan dapat dikunci kembali.
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
                <p class="text-muted small mb-0">Klik tombol <strong>"+ Daftar Pinjam"</strong> pada aset yang dibutuhkan untuk memasukkannya ke permohonan.</p>
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
                            + Daftar Pinjam
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

    <!-- KATALOG RUANGAN SIAP PINJAM (Hanya tampil saat Tab Ruangan Aktif) -->
    <div class="container mb-5 pb-5" id="sectionKatalogRuangan" style="display: none;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">
                    Daftar Ruangan Kampus yang Dapat Dipinjam
                </h4>
                <p class="text-muted small mb-0">Pilih ruangan yang dibutuhkan untuk kegiatan akademik, praktikum, ormawa, atau perkuliahan.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span id="catalogRuanganCountBadge" class="badge bg-light text-secondary border px-3 py-2 rounded-pill font-monospace">
                    {{ $ruangans->count() }} Ruangan Siap Dipinjam
                </span>
            </div>
        </div>

        <!-- SEARCH INPUT UNTUK FILTER KATALOG RUANGAN -->
        <div class="card p-3 mb-4 bg-light border-0 shadow-sm">
            <div class="row g-2 align-items-center">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" id="catalogRuanganSearchInput" class="form-control" placeholder="Cari ruangan di katalog (ketik nama ruangan atau kode)..." oninput="filterRuanganCards(this.value)">
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <button type="button" class="btn btn-sm btn-outline-secondary w-100 w-md-auto" onclick="document.getElementById('catalogRuanganSearchInput').value=''; filterRuanganCards('');">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset Filter
                    </button>
                </div>
            </div>
        </div>

        <div class="row g-3" id="catalogRuanganCardsRow">
            @forelse($ruangans as $ruang)
            <div class="col-md-6 col-lg-4 catalog-ruangan-card" 
                 data-name="{{ strtolower($ruang->nama_ruangan) }}" 
                 data-code="{{ strtolower($ruang->kode_ruangan) }}">
                <div class="item-card p-3 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge-code">{{ $ruang->kode_ruangan }}</span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill small">
                                <i class="fa-solid fa-circle-check me-1"></i>Tersedia
                            </span>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">{{ $ruang->nama_ruangan }}</h6>
                        <p class="text-muted small mb-0" style="font-size: 0.8rem;">
                            <i class="fa-solid fa-building me-1 text-secondary"></i> STIKES Panti Waluya Malang
                        </p>
                    </div>

                    <div class="mt-3 pt-2.5 border-top d-flex justify-content-between align-items-center">
                        <span class="small text-muted font-monospace">{{ $ruang->kode_ruangan }}</span>
                        <button type="button" class="btn btn-sm btn-outline-success fw-bold rounded-pill px-3 shadow-sm" onclick="chooseRuangan('{{ $ruang->id }}', '{{ addslashes($ruang->nama_ruangan) }}', '{{ $ruang->kode_ruangan }}'); scrollToFormRuangan();">
                            <i class="fa-solid fa-check me-1"></i> Pilih Ruangan
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Belum ada ruangan yang diatur untuk dapat dipinjam.</p>
            </div>
            @endforelse
            <div id="catalogRuanganNoMatchMessage" class="col-12 text-center py-5 d-none">
                <p class="text-muted mb-1"><i class="fa-solid fa-magnifying-glass fa-2x opacity-50 mb-2"></i></p>
                <h6 class="fw-bold text-secondary">Ruangan Tidak Ditemukan</h6>
                <p class="small text-muted mb-0">Tidak ada ruangan yang sesuai dengan kata kunci pencarian Anda.</p>
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

    <!-- Script Konfigurasi & Inisialisasi Data Portal Publik -->
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

        $ruanganSearchList = $ruangans->map(function($r) {
            return [
                'id' => (string) $r->id,
                'nama' => $r->nama_ruangan,
                'kode' => $r->kode_ruangan ?? '-'
            ];
        });
    @endphp
    <script>
        window.SPARTA_CONFIG = {
            jadwalUrl: "{{ route('publik.ruangan.jadwal') }}",
            oldRuanganId: "{{ old('ruangan_id') }}",
            items: {!! json_encode($itemSearchList) !!},
            ruangans: {!! json_encode($ruanganSearchList) !!}
        };
    </script>
    <script src="{{ asset('js/portal-publik.js') }}"></script>
</body>
</html>
