<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') &bull; SPARTA-PW STIKES Panti Waluya</title>
    
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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Nunito:wght@800;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 & FontAwesome 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css">
    
    <!-- Custom App Dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('css/app-dashboard.css') }}">
    @stack('styles')
</head>
<body>

    <!-- Header Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid px-lg-4 px-3">
            <a class="navbar-brand" href="{{ route('barang.index') }}">
                <img src="{{ asset('images/logo-stikes.png') }}" alt="Logo STIKES Panti Waluya" style="height: 34px; width: auto; object-fit: contain;">
                <div>
                    <span class="d-block nav-brand-title" style="line-height: 1.1; font-weight: 800; letter-spacing: -0.025em;">SPARTA-PW</span>
                    <small class="nav-brand-subtitle" style="font-size: 0.64rem; font-weight: 600; letter-spacing: 0.04em; text-transform: uppercase;">STIKES Panti Waluya Malang</small>
                </div>
            </a>
            
            <button class="navbar-toggler border-0 position-relative" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-label="Toggle navigation">
                <i class="fa-solid fa-bars-staggered"></i>
                @if((isset($pendingAsetCount) && $pendingAsetCount > 0) || (isset($pendingRuanganCount) && $pendingRuanganCount > 0))
                    <span class="position-absolute top-0 start-100 translate-middle p-1.5 bg-danger border border-light rounded-circle">
                        <span class="visually-hidden">Pengajuan Pending</span>
                    </span>
                @endif
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-lg-auto align-items-lg-center nav-pill-cluster my-2 my-lg-0">
                    <!-- 1. Data Aset -->
                    <li class="nav-item">
                        <a class="nav-link-custom {{ request()->routeIs('barang.*') ? 'active' : '' }}" href="{{ route('barang.index') }}">
                            <span>Data Aset</span>
                        </a>
                    </li>

                    <!-- 2. Peminjaman Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link-custom dropdown-toggle {{ (request()->routeIs('peminjaman.*') || request()->routeIs('peminjaman-ruangan.*')) ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span>Peminjaman</span>
                            @php
                                $totalPending = ($pendingAsetCount ?? 0) + ($pendingRuanganCount ?? 0);
                            @endphp
                            @if($totalPending > 0)
                                <span class="badge-pending-counter" title="{{ $totalPending }} pengajuan menunggu persetujuan">
                                    {{ $totalPending > 99 ? '99+' : $totalPending }}
                                </span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-custom shadow border-0 rounded-3 mt-2 p-1.5">
                            <li>
                                <a class="dropdown-item dropdown-item-custom {{ request()->routeIs('peminjaman.*') ? 'active' : '' }}" href="{{ route('peminjaman.index') }}">
                                    <div class="d-flex align-items-center justify-content-between w-100">
                                        <span>Peminjaman Aset</span>
                                        @if(isset($pendingAsetCount) && $pendingAsetCount > 0)
                                            <span class="badge bg-danger rounded-pill px-2 py-0.5 ms-2 font-monospace" style="font-size: 0.68rem;">
                                                {{ $pendingAsetCount > 99 ? '99+' : $pendingAsetCount }}
                                            </span>
                                        @endif
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item dropdown-item-custom {{ request()->routeIs('peminjaman-ruangan.*') ? 'active' : '' }}" href="{{ route('peminjaman-ruangan.index') }}">
                                    <div class="d-flex align-items-center justify-content-between w-100">
                                        <span>Peminjaman Ruangan</span>
                                        @if(isset($pendingRuanganCount) && $pendingRuanganCount > 0)
                                            <span class="badge bg-danger rounded-pill px-2 py-0.5 ms-2 font-monospace" style="font-size: 0.68rem;">
                                                {{ $pendingRuanganCount > 99 ? '99+' : $pendingRuanganCount }}
                                            </span>
                                        @endif
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- 3. Master Data Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link-custom dropdown-toggle {{ (request()->routeIs('ruangan.*') || request()->routeIs('kategori.*')) ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span>Master Data</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-custom shadow border-0 rounded-3 mt-2 p-1.5">
                            <li>
                                <a class="dropdown-item dropdown-item-custom {{ request()->routeIs('ruangan.*') ? 'active' : '' }}" href="{{ route('ruangan.index') }}">
                                    <span>Data Ruangan</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item dropdown-item-custom {{ request()->routeIs('kategori.*') ? 'active' : '' }}" href="{{ route('kategori.index') }}">
                                    <span>Kategori Aset</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- 4. Laporan & KIR -->
                    <li class="nav-item">
                        <a class="nav-link-custom {{ request()->routeIs('laporan.*') ? 'active' : '' }}" href="{{ route('laporan.index') }}">
                            <span>Laporan & KIR</span>
                        </a>
                    </li>

                    <!-- 5. Pengguna (Khusus IT) -->
                    @if(auth()->check() && auth()->user()->role === 'it')
                    <li class="nav-item">
                        <a class="nav-link-custom {{ request()->routeIs('user.*') ? 'active' : '' }}" href="{{ route('user.index') }}">
                            <span>Pengguna</span>
                        </a>
                    </li>
                    @endif
                </ul>

                <div class="d-flex align-items-center gap-2 ms-lg-auto mt-2 mt-lg-0">
                    <!-- Theme Switch Toggle Button -->
                    <button type="button" class="theme-toggle-btn" id="btnThemeToggle" onclick="toggleAppTheme()" title="Ganti Mode Gelap / Terang" aria-label="Toggle theme">
                        <i class="fa-solid fa-moon" id="themeIcon"></i>
                    </button>

                    @auth
                    <!-- User Profile & Logout Dropdown -->
                    <div class="dropdown">
                        <a class="btn-user-pill dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 24px; height: 24px; font-size: 0.72rem; background: {{ auth()->user()->role === 'it' ? '#2563eb' : '#059669' }};">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="small fw-semibold text-truncate" style="max-width: 130px;">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom shadow border-0 rounded-3 mt-2 p-2" style="min-width: 220px;">
                            <li class="px-3 py-2 border-bottom mb-1">
                                <div class="fw-bold small text-dark">{{ auth()->user()->name }}</div>
                                <div class="text-muted font-monospace" style="font-size: 0.72rem;">{{ auth()->user()->email }}</div>
                                <div class="mt-1.5">
                                    @if(auth()->user()->role === 'it')
                                        <span class="badge bg-primary-subtle text-primary px-2 py-0.5 rounded font-monospace" style="font-size: 0.68rem;">
                                            Admin IT (Super User)
                                        </span>
                                    @else
                                        <span class="badge bg-success-subtle text-success px-2 py-0.5 rounded font-monospace" style="font-size: 0.68rem;">
                                            Admin SARPRAS
                                        </span>
                                    @endif
                                </div>
                            </li>
                            @if(auth()->user()->role === 'it')
                            <li>
                                <a class="dropdown-item dropdown-item-custom small rounded-2 py-2 d-flex align-items-center gap-2" href="{{ route('user.index') }}">
                                    <span>Kelola Pengguna</span>
                                </a>
                            </li>
                            @endif
                            <li>
                                <form action="{{ route('logout') }}" method="POST" id="formLogout">
                                    @csrf
                                    <button type="submit" class="dropdown-item dropdown-item-custom small rounded-2 py-2 text-danger d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                        <span>Keluar (Logout)</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="container py-4 flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer-custom no-print mt-auto">
        <div class="container">
            <div class="row align-items-center gy-2">
                <div class="col-md-6 text-center text-md-start">
                    <span class="footer-brand-text">SPARTA-PW</span> &copy; {{ date('Y') }} &bull; 
                    <span class="fw-semibold">STIKES Panti Waluya Malang</span>
                    <span class="text-muted small ms-1 d-none d-sm-inline">&bull; Unit Sarana & Prasarana</span>
                </div>
                <div class="col-md-6 text-center text-md-end text-muted small">
                    <span>Sistem Informasi Inventaris & Peminjaman Sarpras</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('js/app-dashboard.js') }}"></script>

    <script>
        // SweetAlert Flash Messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
                toast: true,
                position: 'top-end',
                customClass: {
                    popup: 'rounded-3 shadow-sm'
                }
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                toast: true,
                position: 'top-end',
                customClass: {
                    popup: 'rounded-3 shadow-sm'
                }
            });
        @endif
    </script>

    @stack('scripts')
</body>
</html>
