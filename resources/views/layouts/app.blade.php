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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
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
        <div class="container">
            <a class="navbar-brand" href="{{ route('barang.index') }}">
                <img src="{{ asset('images/logo-stikes.png') }}" alt="Logo STIKES Panti Waluya" style="height: 40px; width: auto; object-fit: contain;" class="me-1">
                <div>
                    <span class="d-block text-white" style="line-height: 1.1; font-weight: 800; letter-spacing: -0.02em;">SPARTA-PW</span>
                    <small style="font-size: 0.65rem; color: #CBD5E1; font-weight: 600; letter-spacing: 0.04em; text-transform: uppercase;">STIKES Panti Waluya Malang</small>
                </div>
            </a>
            
            <button class="navbar-toggler border-0 text-white position-relative" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-label="Toggle navigation">
                <i class="fa-solid fa-bars-staggered"></i>
                @if((isset($pendingAsetCount) && $pendingAsetCount > 0) || (isset($pendingRuanganCount) && $pendingRuanganCount > 0))
                    <span class="position-absolute top-0 start-100 translate-middle p-1.5 bg-danger border border-light rounded-circle">
                        <span class="visually-hidden">Pengajuan Pending</span>
                    </span>
                @endif
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link-custom {{ request()->routeIs('barang.*') ? 'active' : '' }}" href="{{ route('barang.index') }}">
                            <i class="fa-solid fa-box-archive"></i>
                            <span>Data Aset</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-custom {{ request()->routeIs('peminjaman.*') ? 'active' : '' }}" href="{{ route('peminjaman.index') }}">
                            <i class="fa-solid fa-hand-holding-hand"></i>
                            <span>Pinjam Aset</span>
                            @if(isset($pendingAsetCount) && $pendingAsetCount > 0)
                                <span class="badge-pending-counter" title="{{ $pendingAsetCount }} pengajuan aset menunggu persetujuan">
                                    {{ $pendingAsetCount > 99 ? '99+' : $pendingAsetCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-custom {{ request()->routeIs('peminjaman-ruangan.*') ? 'active' : '' }}" href="{{ route('peminjaman-ruangan.index') }}">
                            <i class="fa-solid fa-calendar-check"></i>
                            <span>Pinjam Ruangan</span>
                            @if(isset($pendingRuanganCount) && $pendingRuanganCount > 0)
                                <span class="badge-pending-counter" title="{{ $pendingRuanganCount }} pengajuan ruangan menunggu persetujuan">
                                    {{ $pendingRuanganCount > 99 ? '99+' : $pendingRuanganCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-custom {{ request()->routeIs('ruangan.*') ? 'active' : '' }}" href="{{ route('ruangan.index') }}">
                            <i class="fa-solid fa-door-open"></i>
                            <span>Master Ruangan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-custom {{ request()->routeIs('kategori.*') ? 'active' : '' }}" href="{{ route('kategori.index') }}">
                            <i class="fa-solid fa-tags"></i>
                            <span>Kategori</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-custom {{ request()->routeIs('laporan.*') ? 'active' : '' }}" href="{{ route('laporan.index') }}">
                            <i class="fa-solid fa-file-lines"></i>
                            <span>Laporan & KIR</span>
                        </a>
                    </li>

                    @if(auth()->check() && auth()->user()->role === 'it')
                    <li class="nav-item">
                        <a class="nav-link-custom {{ request()->routeIs('user.*') ? 'active' : '' }}" href="{{ route('user.index') }}">
                            <i class="fa-solid fa-users-gear"></i>
                            <span>Manajemen User</span>
                        </a>
                    </li>
                    @endif

                    <!-- Theme Switch Toggle Button -->
                    <li class="nav-item ms-lg-2 my-1 my-lg-0">
                        <button type="button" class="theme-toggle-btn" id="btnThemeToggle" onclick="toggleAppTheme()" title="Ganti Mode Gelap / Terang" aria-label="Toggle theme">
                            <i class="fa-solid fa-moon" id="themeIcon"></i>
                        </button>
                    </li>

                    @auth
                    <!-- User Profile & Logout Dropdown -->
                    <li class="nav-item dropdown ms-lg-3 mt-2 mt-lg-0">
                        <a class="btn btn-outline-light text-white dropdown-toggle d-flex align-items-center gap-2 py-1.5 px-3 rounded-3" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-color: rgba(255,255,255,0.25);">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 26px; height: 26px; font-size: 0.75rem; background: {{ auth()->user()->role === 'it' ? '#1E3A8A' : '#15803D' }};">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="small fw-semibold text-truncate" style="max-width: 130px;">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border rounded-3 mt-2 p-2" style="min-width: 220px; border-color: #E2E8F0;">
                            <li class="px-3 py-2 border-bottom mb-1">
                                <div class="fw-bold small text-dark">{{ auth()->user()->name }}</div>
                                <div class="text-muted" style="font-size: 0.72rem;">{{ auth()->user()->email }}</div>
                                <div class="mt-1">
                                    @if(auth()->user()->role === 'it')
                                        <span class="badge bg-primary-subtle text-primary border border-primary px-2 py-0.5 rounded-pill font-monospace" style="font-size: 0.68rem;">
                                            <i class="fa-solid fa-shield-halved me-1"></i>Admin IT (Super User)
                                        </span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success px-2 py-0.5 rounded-pill font-monospace" style="font-size: 0.68rem;">
                                            <i class="fa-solid fa-boxes-packing me-1"></i>Admin SARPRAS
                                        </span>
                                    @endif
                                </div>
                            </li>
                            @if(auth()->user()->role === 'it')
                            <li>
                                <a class="dropdown-item small rounded-2 py-2 d-flex align-items-center gap-2" href="{{ route('user.index') }}">
                                    <i class="fa-solid fa-users-gear text-primary"></i>
                                    <span>Kelola Pengguna</span>
                                </a>
                            </li>
                            @endif
                            <li>
                                <form action="{{ route('logout') }}" method="POST" id="formLogout">
                                    @csrf
                                    <button type="submit" class="dropdown-item small rounded-2 py-2 text-danger d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                        <span>Keluar (Logout)</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="container py-4 flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer-custom no-print">
        <div class="container">
            <div class="row align-items-center gy-2">
                <div class="col-md-6 text-center text-md-start">
                    <span class="footer-brand-text">SPARTA-PW</span> &copy; {{ date('Y') }} &bull; 
                    <span class="fw-semibold">STIKES Panti Waluya Malang</span>
                </div>
                <div class="col-md-6 text-center text-md-end text-muted small">
                    <span>Sistem Peminjaman Aset & Ruangan Terpadu</span>
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