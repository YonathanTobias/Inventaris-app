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
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-square.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

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
                    <i class="fa-solid fa-clipboard-list me-1"></i> Keranjang
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
                        <i class="fa-solid fa-lock me-1 text-warning"></i> Login Petugas
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- HERO BANNER DENGAN HORIZONTAL STEPPER -->
    <section class="hero-banner">
        <div class="container">
            <div class="row align-items-center gy-4">
                <div class="col-lg-7 text-center text-lg-start">
                    <div class="badge bg-white bg-opacity-10 border border-white border-opacity-25 px-3 py-1.5 rounded-pill mb-3 text-light small fw-semibold">
                        SPARTA-PW &bull; Layanan Mandiri Civitas Akademika
                    </div>
                    <h1 class="fw-extrabold display-6 mb-2" style="letter-spacing: -0.03em;">
                        Peminjaman Aset & Ruangan Kampus
                    </h1>
                    <p class="lead text-light text-opacity-75 mb-0" style="font-size: 1.05rem;">
                        Pilih aset atau ruangan yang dibutuhkan, lengkapi formulir pengajuan, dan pantau status persetujuan secara mandiri dan transparan.
                    </p>
                </div>
                <div class="col-lg-5">
                    <!-- Stepper Visual Interaktif -->
                    <div class="stepper-container">
                        <div class="stepper-progress">
                            <div class="step-node active" id="stepperNode1">
                                <div class="step-node-bubble"><i class="fa-solid fa-box-open"></i></div>
                                <div class="step-node-title">1. Pilih Item</div>
                                <div class="step-node-desc">Katalog Aset/Ruangan</div>
                            </div>
                            <div class="step-node" id="stepperNode2">
                                <div class="step-node-bubble"><i class="fa-solid fa-file-lines"></i></div>
                                <div class="step-node-title">2. Isi Form</div>
                                <div class="step-node-desc">Identitas & Jadwal</div>
                            </div>
                            <div class="step-node" id="stepperNode3">
                                <div class="step-node-bubble"><i class="fa-solid fa-user-check"></i></div>
                                <div class="step-node-title">3. Approval</div>
                                <div class="step-node-desc">Verifikasi Sarpras</div>
                            </div>
                            <div class="step-node" id="stepperNode4">
                                <div class="step-node-bubble"><i class="fa-solid fa-circle-check"></i></div>
                                <div class="step-node-title">4. Selesai</div>
                                <div class="step-node-desc">Serah Terima/Kunci</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CONTAINER UTAMA -->
    <div class="container my-4 mb-5 pb-4">
        
        <!-- ALERT FLASH MESSAGES -->
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 py-3 px-4 shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation fs-5"></i>
                    <div>{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 py-3 px-4 shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-check fs-5"></i>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="alert alert-danger rounded-4 py-3 px-4 mb-4 shadow-sm" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation fs-5"></i>
                    <div>{{ $errors->first() }}</div>
                </div>
            </div>
        @endif

        <!-- NAVIGASI TAB UTAMA: ASET vs RUANGAN -->
        <ul class="nav nav-pills nav-fill nav-pills-portal gap-2 mb-4 p-1.5 bg-light rounded-4 border" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold py-3 rounded-3 d-flex align-items-center justify-content-center gap-2" id="pills-aset-tab" data-bs-toggle="pill" data-bs-target="#pills-aset" type="button" role="tab" onclick="handleTabSwitch('aset')">
                    <i class="fa-solid fa-boxes-stacked fs-5"></i>
                    <span>Peminjaman Aset Sarpras ({{ $barangs->count() }} Item)</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link nav-link-ruangan fw-bold py-3 rounded-3 d-flex align-items-center justify-content-center gap-2" id="pills-ruangan-tab" data-bs-toggle="pill" data-bs-target="#pills-ruangan" type="button" role="tab" onclick="handleTabSwitch('ruangan')">
                    <i class="fa-solid fa-door-open fs-5"></i>
                    <span>Peminjaman Ruangan Kegiatan ({{ $ruangans->count() }} Ruangan)</span>
                </button>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">

            <!-- ======================================================== -->
            <!-- TAB 1: PEMINJAMAN ASET SARPRAS (ALUR 2 LANGKAH) -->
            <!-- ======================================================== -->
            <div class="tab-pane fade show active" id="pills-aset" role="tabpanel">

                <!-- STEP 1 VIEW: KATALOG EKSPLORASI ASET -->
                <div id="viewStep1Aset">
                    <!-- SINGLE INTEGRATED SEARCH & CATEGORY FILTER -->
                    <div class="search-filter-card">
                        <div class="row g-3 align-items-center">
                            <div class="col-lg-8 search-input-wrapper">
                                <div class="input-group">
                                    <span class="input-group-text border-end-0">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </span>
                                    <input type="text" id="unifiedAssetSearchInput" class="form-control border-start-0 ps-0" placeholder="Ketik nama aset, kode barang, atau lokasi ruangan..." oninput="handleUnifiedAssetFilter()">
                                    <button class="btn btn-outline-secondary border-start-0" type="button" onclick="clearUnifiedAssetFilter()" title="Bersihkan">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-4 text-lg-end">
                                <button type="button" class="btn-cart-proceed" onclick="proceedToStep2Form()">
                                    <span><i class="fa-solid fa-cart-arrow-down me-1.5"></i> Lanjut Pengajuan</span>
                                    <span class="badge bg-white text-primary rounded-pill fw-bold px-2 py-1" id="btnCartCountBadge">0 Item</span>
                                </button>
                            </div>
                        </div>

                        <!-- CATEGORY CHIPS SCROLLABLE TRACK -->
                        <div class="category-chips-track">
                            <button type="button" class="category-chip-btn active" data-category="all" onclick="filterByCategory('all', this)">
                                <i class="fa-solid fa-layer-group"></i> Semua Kategori ({{ $barangs->count() }})
                            </button>
                            @if(isset($kategoris))
                                @foreach($kategoris as $cat)
                                    <button type="button" class="category-chip-btn" data-category="{{ strtolower($cat->nama_kategori) }}" onclick="filterByCategory('{{ strtolower($cat->nama_kategori) }}', this)">
                                        <span>{{ $cat->nama_kategori }}</span>
                                    </button>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- KATALOG GRID ASET -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0">Daftar Aset yang Dapat Dipinjam</h5>
                        <span id="unifiedAssetCountBadge" class="badge bg-light text-secondary border px-3 py-2 rounded-pill font-monospace small">
                            {{ $barangs->count() }} Jenis Aset Tersedia
                        </span>
                    </div>

                    @php
                        function getCategoryIconAndTheme($catName, $itemName) {
                            $combined = strtolower($catName . ' ' . $itemName);
                            if (str_contains($combined, 'laptop') || str_contains($combined, 'komputer') || str_contains($combined, 'pc') || str_contains($combined, 'monitor')) {
                                return ['icon' => 'fa-laptop', 'theme' => 'theme-it'];
                            }
                            if (str_contains($combined, 'proyektor') || str_contains($combined, 'projector') || str_contains($combined, 'lcd')) {
                                return ['icon' => 'fa-video', 'theme' => 'theme-av'];
                            }
                            if (str_contains($combined, 'mic') || str_contains($combined, 'audio') || str_contains($combined, 'sound') || str_contains($combined, 'speaker')) {
                                return ['icon' => 'fa-microphone-lines', 'theme' => 'theme-av'];
                            }
                            if (str_contains($combined, 'kamera') || str_contains($combined, 'camera') || str_contains($combined, 'dslr')) {
                                return ['icon' => 'fa-camera', 'theme' => 'theme-av'];
                            }
                            if (str_contains($combined, 'kabel') || str_contains($combined, 'terminal') || str_contains($combined, 'roll') || str_contains($combined, 'hdmi')) {
                                return ['icon' => 'fa-plug', 'theme' => 'theme-it'];
                            }
                            if (str_contains($combined, 'medis') || str_contains($combined, 'tensi') || str_contains($combined, 'stetoskop') || str_contains($combined, 'spuit') || str_contains($combined, 'perawat') || str_contains($combined, 'farmasi') || str_contains($combined, 'klinik')) {
                                return ['icon' => 'fa-heart-pulse', 'theme' => 'theme-medis'];
                            }
                            if (str_contains($combined, 'kursi') || str_contains($combined, 'meja') || str_contains($combined, 'lemari') || str_contains($combined, 'furniture')) {
                                return ['icon' => 'fa-chair', 'theme' => 'theme-general'];
                            }
                            return ['icon' => 'fa-box-open', 'theme' => 'theme-general'];
                        }
                    @endphp

                    <div class="row g-3 g-lg-4" id="catalogCardsRow">
                        @forelse($barangs as $item)
                        @php
                            $catName = $item->kategori->nama_kategori ?? 'Umum';
                            $info = getCategoryIconAndTheme($catName, $item->nama_barang);
                            $isAvailable = $item->stok_tersedia > 0;
                        @endphp
                        <div class="col-md-6 col-lg-4 catalog-item-card" 
                             data-name="{{ strtolower($item->nama_barang) }}" 
                             data-code="{{ strtolower($item->kode_barang) }}" 
                             data-room="{{ strtolower($item->ruangan->nama_ruangan ?? '') }}"
                             data-cat="{{ strtolower($catName) }}">
                            <div class="asset-card">
                                
                                <!-- Top Metadata Row -->
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="asset-cat-pill" title="{{ $catName }}">
                                            <i class="fa-solid {{ $info['icon'] }} me-1.5 text-primary opacity-75"></i> {{ $catName }}
                                        </span>
                                        @if($isAvailable)
                                            <span class="badge-status-available">
                                                <i class="fa-solid fa-circle-check"></i> Sisa {{ $item->stok_tersedia }} Unit
                                            </span>
                                        @else
                                            <span class="badge-status-empty">
                                                <i class="fa-solid fa-circle-xmark"></i> Sedang Dipinjam Penuh
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Main Visual & Title Block -->
                                    <div class="d-flex align-items-start gap-3 mb-2.5">
                                        <div class="asset-icon-box {{ $info['theme'] }}">
                                            <i class="fa-solid {{ $info['icon'] }}"></i>
                                        </div>
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="asset-code-text mb-1">{{ $item->kode_barang }}</div>
                                            <h5 class="asset-title" title="{{ $item->nama_barang }}">
                                                {{ $item->nama_barang }}
                                            </h5>
                                        </div>
                                    </div>

                                    <!-- Location & Note -->
                                    <div class="asset-meta-details mb-1">
                                        <div class="asset-location">
                                            <i class="fa-solid fa-location-dot me-1.5 text-secondary opacity-75"></i>
                                            <span>{{ $item->ruangan->nama_ruangan ?? 'Ruang Sarpras' }}</span>
                                        </div>
                                        @if($item->keterangan)
                                            <div class="asset-notes-clean mt-1.5" title="{{ $item->keterangan }}">
                                                <i class="fa-solid fa-circle-info me-1 text-muted opacity-75"></i>
                                                <span>{{ $item->keterangan }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Card Footer -->
                                <div class="asset-card-footer pt-3 mt-3 border-top d-flex align-items-center justify-content-between gap-2">
                                    @if($isAvailable)
                                        <div class="qty-control-wrapper">
                                            <button type="button" class="btn-qty-step" onclick="stepItemQty('{{ $item->id }}', -1)" aria-label="Kurangi unit">
                                                <i class="fa-solid fa-minus"></i>
                                            </button>
                                            <input type="number" id="catalogQty{{ $item->id }}" class="input-qty-val" value="1" min="1" max="{{ $item->stok_tersedia }}">
                                            <button type="button" class="btn-qty-step" onclick="stepItemQty('{{ $item->id }}', 1)" aria-label="Tambah unit">
                                                <i class="fa-solid fa-plus"></i>
                                            </button>
                                        </div>
                                        <button type="button" class="btn-add-to-cart" onclick="addToCart('{{ $item->id }}', '{{ addslashes($item->nama_barang) }}', '{{ $item->kode_barang }}', '{{ addslashes($item->ruangan->nama_ruangan ?? '-') }}', {{ $item->stok_tersedia }})">
                                            <i class="fa-solid fa-cart-plus me-1"></i> Pinjam
                                        </button>
                                    @else
                                        <span class="text-muted small">Stok di tempat habis</span>
                                        <button type="button" class="btn-add-to-cart btn-disabled" disabled>
                                            Habis
                                        </button>
                                    @endif
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
                            <p class="small text-muted mb-0">Tidak ada item yang sesuai dengan kata kunci pencarian atau filter kategori yang dipilih.</p>
                        </div>
                    </div>
                </div>

                <!-- STEP 2 VIEW: FORMULIR PENGAJUAN MANDIRI -->
                <div id="viewStep2Aset" class="d-none">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 mb-2" onclick="backToStep1Catalog()">
                                <i class="fa-solid fa-arrow-left me-1"></i> Kembali Tambah Aset di Katalog
                            </button>
                            <h3 class="fw-extrabold text-dark mb-0">Formulir Pengajuan Peminjaman Aset</h3>
                            <p class="text-muted small mb-0">Lengkapi data identitas dan tanggal rencana peminjaman untuk permohonan Anda.</p>
                        </div>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill font-monospace small">
                            Langkah 2 dari 2: Isi Formulir
                        </span>
                    </div>

                    <form action="{{ route('publik.store') }}" method="POST" id="formPeminjamanMandiri">
                        @csrf

                        <!-- Hidden Input untuk Menyimpan Data Keranjang -->
                        <input type="hidden" name="cart_data" id="hiddenCartData" value="">

                        <!-- SEGMENT CARD 1: DAFTAR ASET YANG DIPILIH -->
                        <div class="form-segment-card">
                            <div class="form-segment-header justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="segment-step-number">1</div>
                                    <div>
                                        <h5 class="fw-bold text-dark mb-0">Daftar Aset yang Dipinjam</h5>
                                        <small class="text-muted">Periksa kuantitas unit yang Anda butuhkan</small>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="backToStep1Catalog()">
                                    <i class="fa-solid fa-plus me-1"></i> Tambah Aset Lain
                                </button>
                            </div>

                            <div class="table-responsive rounded-3 border">
                                <table class="table table-hover align-middle mb-0" id="tablePreviewCart">
                                    <thead class="bg-light small text-muted">
                                        <tr>
                                            <th class="ps-3" style="width: 40px;">No</th>
                                            <th>Nama Aset & Lokasi Ruangan</th>
                                            <th class="text-center" style="width: 140px;">Jumlah Unit</th>
                                            <th class="text-center pe-3" style="width: 80px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyPreviewCart">
                                        <!-- Diisi otomatis via JavaScript -->
                                    </tbody>
                                </table>
                            </div>

                            <div id="emptyCartAlert" class="alert alert-warning py-3 px-3.5 small rounded-3 mt-3 mb-0 d-flex align-items-center gap-2">
                                <i class="fa-solid fa-circle-exclamation fs-4"></i>
                                <div>
                                    <strong>Keranjang masih kosong!</strong> Silakan kembali ke katalog untuk memilih minimal 1 aset.
                                </div>
                            </div>
                        </div>

                        <!-- SEGMENT CARD 2: IDENTITAS PEMOHON (DENGAN AUTO-FILL) -->
                        <div class="form-segment-card">
                            <div class="form-segment-header justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="segment-step-number">2</div>
                                    <div>
                                        <h5 class="fw-bold text-dark mb-0">Identitas Pemohon</h5>
                                        <small class="text-muted">Data otomatis tersimpan untuk kemudahan pengajuan berikutnya</small>
                                    </div>
                                </div>
                                @auth
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill small">
                                        <i class="fa-solid fa-user-check me-1"></i> Login: {{ auth()->user()->name }}
                                    </span>
                                @endauth
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-secondary">Status Pemohon <span class="text-danger">*</span></label>
                                    <select name="kategori_peminjam" id="inputKategoriPeminjam" class="form-select" required onchange="saveProfileToStorage()">
                                        <option value="Mahasiswa" {{ old('kategori_peminjam') == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                        <option value="Dosen" {{ old('kategori_peminjam') == 'Dosen' ? 'selected' : '' }}>Dosen / Pengajar</option>
                                        <option value="Staf / Tendik" {{ old('kategori_peminjam') == 'Staf / Tendik' ? 'selected' : '' }}>Staf / Tenaga Kependidikan</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-secondary">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_peminjam" id="inputNamaPeminjam" class="form-control" placeholder="Nama lengkap Anda" value="{{ old('nama_peminjam', auth()->user()->name ?? '') }}" required oninput="saveProfileToStorage()">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-secondary">Nomor Identitas (NIM / NIP) <span class="text-danger">*</span></label>
                                    <input type="text" name="nomor_identitas" id="inputNomorIdentitas" class="form-control font-monospace" placeholder="Misal: 202301045 / NIP. 198..." value="{{ old('nomor_identitas') }}" required oninput="saveProfileToStorage()">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-secondary">Program Studi / Unit Kerja <span class="text-danger">*</span></label>
                                    <input type="text" name="prodi_unit" id="inputProdiUnit" class="form-control" placeholder="Contoh: S1 Keperawatan / D4 MIK / S1 Farmasi / IKM" value="{{ old('prodi_unit') }}" required oninput="saveProfileToStorage()">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-secondary">Nomor WhatsApp Aktif <span class="text-danger">*</span></label>
                                    <input type="text" name="kontak_peminjam" id="inputKontakPeminjam" class="form-control" placeholder="Misal: 081234567890 (Untuk konfirmasi status pengajuan)" value="{{ old('kontak_peminjam') }}" required oninput="saveProfileToStorage()">
                                </div>
                            </div>
                        </div>

                        <!-- SEGMENT CARD 3: JADWAL & KEPERLUAN -->
                        <div class="form-segment-card">
                            <div class="form-segment-header">
                                <div class="segment-step-number">3</div>
                                <div>
                                    <h5 class="fw-bold text-dark mb-0">Jadwal & Keperluan Peminjaman</h5>
                                    <small class="text-muted">Tentukan rentang tanggal penggunaan aset</small>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-secondary">Tanggal Rencana Peminjaman / Diambil <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_pinjam" class="form-control" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-secondary">Rencana Tanggal Pengembalian <span class="text-danger">*</span></label>
                                    <input type="date" name="tenggat_kembali" class="form-control" value="{{ old('tenggat_kembali', date('Y-m-d', strtotime('+3 days'))) }}" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-bold small text-secondary">Keperluan / Acara Penggunaan <span class="text-danger">*</span></label>
                                    <textarea name="keperluan" class="form-control" rows="3" placeholder="Contoh: Praktikum Klinik Keperawatan, Seminar HIMA, Kegiatan Ormawa..." required>{{ old('keperluan') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- SEGMENT CARD 4: PERNYATAAN & SUBMIT -->
                        <div class="form-segment-card">
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

                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                                <button type="button" class="btn btn-outline-secondary rounded-pill px-4 py-2.5" onclick="backToStep1Catalog()">
                                    <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Katalog
                                </button>
                                <button type="submit" class="btn-submit-pinjam w-100 w-md-auto" id="btnSubmitPengajuan">
                                    <i class="fa-solid fa-paper-plane me-1"></i> Kirim Permohonan Peminjaman Aset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>

            <!-- ======================================================== -->
            <!-- TAB 2: PEMINJAMAN RUANGAN KEGIATAN (ALUR 2 LANGKAH) -->
            <!-- ======================================================== -->
            <div class="tab-pane fade" id="pills-ruangan" role="tabpanel">

                @php
                    function getRuanganMeta($namaRuangan, $kodeRuangan) {
                        $n = strtolower($namaRuangan . ' ' . $kodeRuangan);
                        if (str_contains($n, 'aula') || str_contains($n, 'auditorium') || str_contains($n, 'hall')) {
                            return [
                                'kategori' => 'Aula & Auditorium',
                                'cat_key' => 'aula',
                                'icon' => 'fa-building-columns',
                                'theme' => 'theme-it',
                                'kapasitas' => '150 - 300 Orang',
                                'fasilitas' => ['AC', 'Proyektor LCD', 'Sound System', 'Wi-Fi']
                            ];
                        }
                        if (str_contains($n, 'lab') || str_contains($n, 'laboratorium') || str_contains($n, 'komputer') || str_contains($n, 'farmasi') || str_contains($n, 'keperawatan') || str_contains($n, 'bahasa') || str_contains($n, 'anatomi') || str_contains($n, 'mikro')) {
                            $fas = ['AC', 'Alat Praktikum', 'Proyektor LCD', 'Wi-Fi'];
                            if (str_contains($n, 'komputer')) {
                                $fas = ['AC', '40 PC Komputer', 'Proyektor LCD', 'Wi-Fi'];
                            }
                            return [
                                'kategori' => 'Laboratorium',
                                'cat_key' => 'laboratorium',
                                'icon' => 'fa-flask-vial',
                                'theme' => 'theme-medis',
                                'kapasitas' => '30 - 40 Kursi',
                                'fasilitas' => $fas
                            ];
                        }
                        if (str_contains($n, 'rapat') || str_contains($n, 'meeting') || str_contains($n, 'seminar') || str_contains($n, 'sidang') || str_contains($n, 'senat')) {
                            return [
                                'kategori' => 'Ruang Rapat & Seminar',
                                'cat_key' => 'rapat',
                                'icon' => 'fa-handshake',
                                'theme' => 'theme-av',
                                'kapasitas' => '15 - 30 Kursi',
                                'fasilitas' => ['AC', 'Smart TV / LCD', 'Mic Wireless', 'Wi-Fi']
                            ];
                        }
                        return [
                            'kategori' => 'Ruang Kelas Teori',
                            'cat_key' => 'teori',
                            'icon' => 'fa-chalkboard-user',
                            'theme' => 'theme-general',
                            'kapasitas' => '40 - 50 Kursi',
                            'fasilitas' => ['AC', 'Proyektor LCD', 'Whiteboard', 'Wi-Fi']
                        ];
                    }
                @endphp

                <!-- STEP 1 VIEW: KATALOG RUANGAN & CEK JADWAL -->
                <div id="viewStep1Ruangan" class="{{ old('ruangan_id') ? 'd-none' : '' }}">
                    
                    <!-- SEARCH, DATE & CATEGORY TOOLBAR -->
                    <div class="search-filter-card">
                        <div class="row g-3 align-items-center">
                            <div class="col-lg-6 search-input-wrapper">
                                <label class="form-label small fw-bold text-secondary mb-1">
                                    <i class="fa-solid fa-magnifying-glass text-primary me-1"></i> Cari Ruangan Kampus:
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </span>
                                    <input type="text" id="catalogRuanganSearchInput" class="form-control border-start-0 ps-0" placeholder="Ketik nama ruangan (contoh: Aula, Lab Komputer, Teori)..." oninput="filterRuanganCards(this.value)">
                                    <button class="btn btn-outline-secondary border-start-0" type="button" onclick="document.getElementById('catalogRuanganSearchInput').value=''; filterRuanganCards('');" title="Bersihkan">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="col-lg-4">
                                <label class="form-label small fw-bold text-secondary mb-1">
                                    <i class="fa-solid fa-calendar-day text-primary me-1"></i> Cek Jadwal Tanggal Kegiatan:
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-calendar text-muted"></i></span>
                                    <input type="date" id="catalogRuanganDateFilter" class="form-control border-start-0" value="{{ old('tanggal_pemakaian', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" onchange="handleRoomCatalogDateChange(this.value)">
                                </div>
                            </div>

                            <div class="col-lg-2 text-lg-end d-flex flex-column justify-content-end">
                                <span id="catalogRuanganCountBadge" class="badge bg-light text-secondary border px-3 py-2.5 rounded-pill font-monospace small">
                                    {{ $ruangans->count() }} Ruangan
                                </span>
                            </div>
                        </div>

                        <!-- CATEGORY CHIPS SCROLLABLE TRACK -->
                        <div class="category-chips-track mt-2">
                            <button type="button" class="category-chip-btn active" data-room-cat="all" onclick="filterRuanganByCategory('all', this)">
                                <i class="fa-solid fa-layer-group"></i> Semua Ruangan ({{ $ruangans->count() }})
                            </button>
                            <button type="button" class="category-chip-btn" data-room-cat="laboratorium" onclick="filterRuanganByCategory('laboratorium', this)">
                                <i class="fa-solid fa-flask-vial"></i> Laboratorium
                            </button>
                            <button type="button" class="category-chip-btn" data-room-cat="aula" onclick="filterRuanganByCategory('aula', this)">
                                <i class="fa-solid fa-building-columns"></i> Aula & Auditorium
                            </button>
                            <button type="button" class="category-chip-btn" data-room-cat="teori" onclick="filterRuanganByCategory('teori', this)">
                                <i class="fa-solid fa-chalkboard-user"></i> Ruang Kelas Teori
                            </button>
                            <button type="button" class="category-chip-btn" data-room-cat="rapat" onclick="filterRuanganByCategory('rapat', this)">
                                <i class="fa-solid fa-handshake"></i> Ruang Rapat & Seminar
                            </button>
                        </div>
                    </div>

                    <!-- GRID KARTU RUANGAN -->
                    <div class="row g-3 g-lg-4 mb-5" id="catalogRuanganCardsRow">
                        @forelse($ruangans as $ruang)
                        @php
                            $rMeta = getRuanganMeta($ruang->nama_ruangan, $ruang->kode_ruangan);
                        @endphp
                        <div class="col-md-6 col-lg-4 catalog-ruangan-card" 
                             data-name="{{ strtolower($ruang->nama_ruangan) }}" 
                             data-code="{{ strtolower($ruang->kode_ruangan) }}"
                             data-cat="{{ $rMeta['cat_key'] }}"
                             data-room-id="{{ $ruang->id }}">
                            <div class="asset-card">
                                
                                <!-- Top Metadata Row -->
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="asset-cat-pill">
                                            <i class="fa-solid {{ $rMeta['icon'] }} me-1.5 text-success opacity-75"></i> {{ $rMeta['kategori'] }}
                                        </span>
                                        <span class="room-capacity-pill">
                                            <i class="fa-solid fa-users"></i> {{ $rMeta['kapasitas'] }}
                                        </span>
                                    </div>

                                    <!-- Main Visual & Title Block -->
                                    <div class="d-flex align-items-start gap-3 mb-2.5">
                                        <div class="asset-icon-box {{ $rMeta['theme'] }}">
                                            <i class="fa-solid {{ $rMeta['icon'] }}"></i>
                                        </div>
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="asset-code-text mb-1">{{ $ruang->kode_ruangan }}</div>
                                            <h5 class="asset-title" title="{{ $ruang->nama_ruangan }}">
                                                {{ $ruang->nama_ruangan }}
                                            </h5>
                                        </div>
                                    </div>

                                    <!-- Facilities Tags -->
                                    <div class="facilities-wrapper mb-2">
                                        @foreach($rMeta['fasilitas'] as $fas)
                                            <span class="facility-tag">
                                                <i class="fa-solid fa-check text-success"></i> {{ $fas }}
                                            </span>
                                        @endforeach
                                    </div>

                                    <!-- Real-time Schedule Status Preview -->
                                    <div class="room-schedule-preview" id="roomScheduleWidget{{ $ruang->id }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="slot-status-free" id="roomStatusText{{ $ruang->id }}">
                                                <i class="fa-solid fa-circle-check"></i> Kosong (Siap Booking)
                                            </span>
                                            <span class="text-muted small font-monospace" id="roomDateLabel{{ $ruang->id }}">
                                                {{ date('d M') }}
                                            </span>
                                        </div>
                                        <div class="mt-1 d-none" id="roomSlotChips{{ $ruang->id }}"></div>
                                    </div>
                                </div>

                                <!-- Card Footer -->
                                <div class="asset-card-footer pt-3 mt-3 border-top d-flex align-items-center justify-content-between gap-2">
                                    <span class="small text-muted font-monospace"><i class="fa-solid fa-hashtag me-1"></i>{{ $ruang->kode_ruangan }}</span>
                                    <button type="button" class="btn-add-to-cart" style="width: auto; padding: 0.45rem 1.1rem;" onclick="chooseRuangan('{{ $ruang->id }}', '{{ addslashes($ruang->nama_ruangan) }}', '{{ $ruang->kode_ruangan }}'); proceedToStep2RuanganForm();">
                                        <i class="fa-solid fa-calendar-check me-1"></i> Pilih Ruangan
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

                <!-- STEP 2 VIEW: FORMULIR BOOKING RUANGAN MANDIRI -->
                <div id="viewStep2RuanganForm" class="{{ old('ruangan_id') ? '' : 'd-none' }} pt-2">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 pb-2 border-bottom">
                        <div>
                            <h3 class="fw-extrabold text-dark mb-0">Formulir Booking Ruangan Kegiatan</h3>
                            <p class="text-muted small mb-0">Lengkapi jadwal jam penggunaan dan identitas penanggung jawab kegiatan.</p>
                        </div>
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 small fw-semibold" onclick="backToStep1Ruangan()">
                            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Katalog Ruangan
                        </button>
                    </div>

                    <form action="{{ route('publik.ruangan.store') }}" method="POST" id="formBookingRuangan">
                        @csrf

                        <!-- SEGMENT CARD 1: PILIHAN RUANGAN & JADWAL -->
                        <div class="form-segment-card">
                            <div class="form-segment-header">
                                <div class="segment-step-number">1</div>
                                <div>
                                    <h5 class="fw-bold text-dark mb-0">Pilihan Ruangan & Waktu Pemakaian</h5>
                                    <small class="text-muted">Jam operasional kampus: 07:00 - 18:00 WIB (Interval 30 Menit)</small>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-secondary">Ruangan Terpilih <span class="text-danger">*</span></label>
                                    <input type="hidden" name="ruangan_id" id="selectRuanganPublik" value="{{ old('ruangan_id') }}" required>

                                    <!-- Box Ruangan Terpilih -->
                                    <div id="selectedRuanganCard" class="p-3 bg-light rounded-3 border d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center gap-2.5">
                                            <span class="p-2.5 rounded-3 bg-success-subtle text-success fw-bold fs-5">
                                                <i class="fa-solid fa-door-open"></i>
                                            </span>
                                            <div>
                                                <strong class="d-block text-dark fs-6" id="selectedRuanganNamaText">-</strong>
                                                <span class="badge bg-secondary-subtle text-secondary py-0 font-monospace" id="selectedRuanganKodeText" style="font-size: 0.75rem;">-</span>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1" onclick="backToStep1Ruangan()">
                                            <i class="fa-solid fa-rotate-left me-1"></i> Ganti
                                        </button>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-secondary">Tanggal Pemakaian <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_pemakaian" id="inputTanggalRuangan" class="form-control" value="{{ old('tanggal_pemakaian', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required onchange="triggerCekJadwal()">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-secondary">Jam Mulai Kegiatan <span class="text-danger">*</span></label>
                                    <select name="jam_mulai" id="selectJamMulai" class="form-select" required onchange="handleTimeSlotChange(); triggerCekJadwal();">
                                        @php
                                            $timeSlots = [
                                                '07:00', '07:30', '08:00', '08:30', '09:00', '09:30',
                                                '10:00', '10:30', '11:00', '11:30', '12:00', '12:30',
                                                '13:00', '13:30', '14:00', '14:30', '15:00', '15:30',
                                                '16:00', '16:30', '17:00', '17:30'
                                            ];
                                        @endphp
                                        @foreach($timeSlots as $slot)
                                            <option value="{{ $slot }}" {{ old('jam_mulai', '08:00') == $slot ? 'selected' : '' }}>
                                                {{ $slot }} WIB
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-secondary">Jam Selesai Kegiatan <span class="text-danger">*</span></label>
                                    <select name="jam_selesai" id="selectJamSelesai" class="form-select" required onchange="triggerCekJadwal();">
                                        @php
                                            $endSlots = [
                                                '07:30', '08:00', '08:30', '09:00', '09:30',
                                                '10:00', '10:30', '11:00', '11:30', '12:00', '12:30',
                                                '13:00', '13:30', '14:00', '14:30', '15:00', '15:30',
                                                '16:00', '16:30', '17:00', '17:30', '18:00'
                                            ];
                                        @endphp
                                        @foreach($endSlots as $slot)
                                            <option value="{{ $slot }}" {{ old('jam_selesai', '12:00') == $slot ? 'selected' : '' }}>
                                                {{ $slot }} WIB
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Box Info Jadwal Terisi & Deteksi Bentrok -->
                                <div class="col-12" id="boxJadwalTerisi" style="display: none;">
                                    <div class="p-3 bg-light rounded-3 border">
                                        <div class="fw-bold small text-secondary mb-1">
                                            <i class="fa-solid fa-calendar-days text-primary me-1"></i> Jadwal Terisi pada Tanggal Tersebut:
                                        </div>
                                        <div id="listJadwalRuangan" class="small text-muted"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SEGMENT CARD 2: IDENTITAS PEMOHON RUANGAN -->
                        <div class="form-segment-card">
                            <div class="form-segment-header justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="segment-step-number">2</div>
                                    <div>
                                        <h5 class="fw-bold text-dark mb-0">Identitas Pemohon / Penanggung Jawab</h5>
                                        <small class="text-muted">Data penanggung jawab kegiatan</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-secondary">Status Pemohon <span class="text-danger">*</span></label>
                                    <select name="kategori_peminjam" id="inputKategoriRuangan" class="form-select" required onchange="saveProfileToStorage()">
                                        <option value="Mahasiswa / Ormawa" {{ old('kategori_peminjam') == 'Mahasiswa / Ormawa' ? 'selected' : '' }}>Mahasiswa / Ormawa (IKM/HIMA)</option>
                                        <option value="Dosen" {{ old('kategori_peminjam') == 'Dosen' ? 'selected' : '' }}>Dosen / Pengajar</option>
                                        <option value="Staf / Tendik" {{ old('kategori_peminjam') == 'Staf / Tendik' ? 'selected' : '' }}>Staf / Tenaga Kependidikan</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-secondary">Nama Pemohon <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_peminjam" id="inputNamaRuangan" class="form-control" placeholder="Nama lengkap Anda" value="{{ old('nama_peminjam', auth()->user()->name ?? '') }}" required oninput="saveProfileToStorage()">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-secondary">Nomor Identitas (NIM / NIP) <span class="text-danger">*</span></label>
                                    <input type="text" name="nomor_identitas" id="inputNomorRuangan" class="form-control font-monospace" placeholder="Misal: 202301045" value="{{ old('nomor_identitas') }}" required oninput="saveProfileToStorage()">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-secondary">Program Studi / Ormawa / Unit <span class="text-danger">*</span></label>
                                    <input type="text" name="prodi_unit" id="inputProdiRuangan" class="form-control" placeholder="Contoh: IKM STIKES / HIMA / Prodi" value="{{ old('prodi_unit') }}" required oninput="saveProfileToStorage()">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-secondary">Nomor WhatsApp Aktif <span class="text-danger">*</span></label>
                                    <input type="text" name="kontak_peminjam" id="inputKontakRuangan" class="form-control" placeholder="Misal: 081234567890 (Untuk konfirmasi buka ruangan & approval)" value="{{ old('kontak_peminjam') }}" required oninput="saveProfileToStorage()">
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-bold small text-secondary">Keperluan / Acara Kegiatan <span class="text-danger">*</span></label>
                                    <textarea name="keperluan" class="form-control" rows="3" placeholder="Contoh: Rapat Kerja Ormawa, Seminar Nasional, Kuliah Umum..." required>{{ old('keperluan') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- SEGMENT CARD 3: PERNYATAAN & SUBMIT RUANGAN -->
                        <div class="form-segment-card">
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

                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                                <button type="button" class="btn btn-outline-secondary rounded-pill px-4 py-2.5" onclick="backToStep1Ruangan()">
                                    <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Katalog Ruangan
                                </button>
                                <button type="submit" class="btn-submit-ruangan w-100 w-md-auto">
                                    <i class="fa-solid fa-calendar-check me-1"></i> Kirim Permohonan Booking Ruangan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>

    <!-- FLOATING BOTTOM ACTION BAR (CTA STICKY) -->
    <div class="floating-bottom-action-bar" id="floatingBottomActionBar">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 1.1rem;">
                <i class="fa-solid fa-clipboard-list"></i>
            </div>
            <div>
                <strong class="d-block text-white" id="floatingBarItemsText">0 Item Aset Dipilih</strong>
                <small class="text-light text-opacity-75" id="floatingBarQtyText">0 Unit total</small>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-3 py-1.5 fw-semibold d-none d-sm-inline-block" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart">
                Lihat Detail
            </button>
            <button type="button" class="btn btn-primary fw-bold rounded-pill px-4 py-2 shadow-sm" onclick="proceedToStep2Form()">
                Lanjut Isi Form &rarr;
            </button>
        </div>
    </div>

    <!-- OFFCANVAS DAFTAR PINJAM PEMINJAMAN ASET -->
    <div class="offcanvas offcanvas-end offcanvas-cart shadow-lg border-0" tabindex="-1" id="offcanvasCart">
        <div class="offcanvas-header border-bottom py-3">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                    <i class="fa-solid fa-clipboard-list"></i>
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
                <button type="button" class="btn btn-primary w-100 fw-bold py-2.5 rounded-3 shadow-sm" data-bs-dismiss="offcanvas" onclick="proceedToStep2Form()">
                    <i class="fa-solid fa-file-signature me-1"></i> Lanjut Isi Data Formulir
                </button>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="footer-portal mt-auto">
        <div class="container">
            <div class="row align-items-center gy-2">
                <div class="col-md-6 text-center text-md-start">
                    <span class="footer-brand-text">SPARTA-PW</span> &copy; {{ date('Y') }} &bull; <span class="fw-semibold">STIKES Panti Waluya Malang</span>
                </div>
                <div class="col-md-6 text-center text-md-end text-muted small">
                    <span>Sistem Peminjaman Aset & Ruangan Terpadu Akademik</span>
                </div>
            </div>
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
                'stok' => (int) $b->stok_tersedia,
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
            ruangans: {!! json_encode($ruanganSearchList) !!},
            authUser: @json(auth()->user() ? [
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ] : null)
        };
    </script>
    <script src="{{ asset('js/portal-publik.js') }}"></script>
</body>
</html>
