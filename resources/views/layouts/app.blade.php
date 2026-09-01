<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sistem Inventaris Aset' }} - STIKES Panti Waluya</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-stikes.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 & FontAwesome 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css">
    
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #eef2ff;
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            --secondary: #64748b;
            --accent: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #0f172a;
            --card-bg: #ffffff;
            --body-bg: #f8fafc;
            --border-color: #e2e8f0;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 12px -2px rgba(15, 23, 42, 0.06), 0 2px 6px -1px rgba(15, 23, 42, 0.03);
            --shadow-lg: 0 10px 25px -4px rgba(15, 23, 42, 0.08), 0 6px 12px -2px rgba(15, 23, 42, 0.03);
            --shadow-glow: 0 0 20px rgba(79, 70, 229, 0.25);
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--body-bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            letter-spacing: -0.01em;
            -webkit-font-smoothing: antialiased;
        }

        /* Modern Glass Navbar */
        .navbar-custom {
            background: rgba(15, 23, 42, 0.94);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: sticky;
            top: 0;
            z-index: 1030;
            padding: 0.75rem 0;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.25rem;
            color: #ffffff !important;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            letter-spacing: -0.02em;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            background: var(--primary-gradient);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
        }

        .nav-link-custom {
            color: #94a3b8 !important;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.55rem 1rem !important;
            border-radius: var(--radius-md);
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0 0.15rem;
        }

        .nav-link-custom:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-1px);
        }

        .nav-link-custom.active {
            color: #ffffff !important;
            background: var(--primary-gradient);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
        }

        /* Modern Cards */
        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            overflow: hidden;
        }

        .card-header-modern {
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            padding: 1.1rem 1.4rem;
            font-weight: 700;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Stat Widget Cards */
        .card-stat {
            position: relative;
            overflow: hidden;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
            background: #ffffff;
            padding: 1.25rem 1.4rem;
            box-shadow: var(--shadow-md);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-stat:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
            border-color: #cbd5e1;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
        }

        .stat-icon.primary { background: #eef2ff; color: var(--primary); }
        .stat-icon.success { background: #ecfdf5; color: var(--success); }
        .stat-icon.warning { background: #fffbeb; color: var(--warning); }
        .stat-icon.danger  { background: #fef2f2; color: var(--danger); }
        .stat-icon.info    { background: #f0f9ff; color: var(--accent); }

        .stat-value {
            font-size: 1.65rem;
            font-weight: 800;
            line-height: 1.2;
            color: var(--text-main);
            letter-spacing: -0.03em;
        }

        .stat-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        /* Modern Table */
        .table-modern {
            margin-bottom: 0;
        }

        .table-modern thead th {
            background-color: #f8fafc;
            color: #475569;
            font-size: 0.76rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 0.95rem 1.1rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .table-modern tbody td {
            padding: 1rem 1.1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
        }

        .table-modern tbody tr {
            transition: background-color 0.15s ease;
        }

        .table-modern tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Modern Badges & Status */
        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.01em;
        }

        .badge-status::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
        }

        .badge-status-baik {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-status-baik::before {
            background-color: #10b981;
            box-shadow: 0 0 6px rgba(16, 185, 129, 0.6);
        }

        .badge-status-ringan {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-status-ringan::before {
            background-color: #f59e0b;
            box-shadow: 0 0 6px rgba(245, 158, 11, 0.6);
        }

        .badge-status-berat {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .badge-status-berat::before {
            background-color: #ef4444;
            box-shadow: 0 0 6px rgba(239, 68, 68, 0.6);
        }

        .badge-pill-custom {
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.78rem;
        }

        .badge-code {
            background-color: #f8fafc;
            color: #1e293b;
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
            font-size: 0.82rem;
            padding: 0.32rem 0.65rem;
            border-radius: 8px;
            font-weight: 700;
            border: 1px solid #cbd5e1;
            letter-spacing: 0.02em;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .badge-code::before {
            content: '\f02a';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: #4f46e5;
            font-size: 0.8rem;
        }

        .badge-room-code {
            background-color: #eef2ff;
            color: #4338ca;
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
            font-size: 0.82rem;
            padding: 0.32rem 0.65rem;
            border-radius: 8px;
            font-weight: 700;
            border: 1px solid #c7d2fe;
            letter-spacing: 0.02em;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .badge-room-code::before {
            content: '\f52a';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: #4f46e5;
            font-size: 0.8rem;
        }

        .badge-category {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
            font-weight: 600;
            padding: 0.3rem 0.65rem;
            border-radius: 6px;
            font-size: 0.8rem;
        }

        /* Buttons & Actions */
        .btn-modern-primary {
            background: var(--primary-gradient);
            border: none;
            color: #ffffff;
            font-weight: 600;
            padding: 0.6rem 1.25rem;
            border-radius: var(--radius-md);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-modern-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.35);
            color: #ffffff;
        }

        .btn-icon {
            width: 34px;
            height: 34px;
            padding: 0;
            border-radius: var(--radius-sm);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            transition: all 0.15s ease;
            border: 1px solid transparent;
        }

        .btn-icon:hover {
            transform: translateY(-2px);
        }

        .btn-icon-primary {
            background-color: #eef2ff;
            color: var(--primary);
            border-color: #c7d2fe;
        }
        .btn-icon-primary:hover {
            background-color: var(--primary);
            color: #ffffff;
        }

        .btn-icon-info {
            background-color: #e0f2fe;
            color: #0284c7;
            border-color: #bae6fd;
        }
        .btn-icon-info:hover {
            background-color: #0284c7;
            color: #ffffff;
        }

        .btn-icon-warning {
            background-color: #fef3c7;
            color: #b45309;
            border-color: #fde68a;
        }
        .btn-icon-warning:hover {
            background-color: #f59e0b;
            color: #ffffff;
        }

        .btn-icon-danger {
            background-color: #fee2e2;
            color: #dc2626;
            border-color: #fecaca;
        }
        .btn-icon-danger:hover {
            background-color: #dc2626;
            color: #ffffff;
        }

        /* Form Controls */
        .form-control, .form-select {
            border: 1px solid #cbd5e1;
            border-radius: var(--radius-md);
            padding: 0.6rem 0.9rem;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-main);
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.4rem;
        }

        /* Modal Modern */
        .modal-content {
            border-radius: var(--radius-xl);
            border: none;
            box-shadow: 0 20px 40px -10px rgba(15, 23, 42, 0.25);
            overflow: hidden;
        }

        .modal-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border-color);
            background-color: #f8fafc;
        }

        /* Timeline for Activity */
        .timeline-container {
            position: relative;
            padding-left: 20px;
        }

        .timeline-container::before {
            content: '';
            position: absolute;
            left: 7px;
            top: 10px;
            bottom: 10px;
            width: 2px;
            background: #e2e8f0;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 1.25rem;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-dot {
            position: absolute;
            left: -20px;
            top: 4px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #ffffff;
            border: 3px solid var(--primary);
        }

        .timeline-dot.danger {
            border-color: var(--danger);
        }
        
        .timeline-dot.info {
            border-color: var(--accent);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Footer */
        .footer-custom {
            margin-top: auto;
            background: #ffffff;
            border-top: 1px solid var(--border-color);
            padding: 1.25rem 0;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        /* Print Style */
        @media print {
            .navbar-custom, .btn, .no-print, .card-stat, form, .modal, .footer-custom {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                color: #000000 !important;
                font-family: Arial, Helvetica, sans-serif !important;
            }
            .card {
                box-shadow: none !important;
                border: none !important;
                background: transparent !important;
            }
            .container {
                max-width: 100% !important;
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            @page {
                size: portrait;
                margin: 10mm 12mm;
            }
        }
    </style>
</head>
<body>

    <!-- Header Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('barang.index') }}">
                <img src="{{ asset('images/logo-stikes.png') }}" alt="Logo STIKES Panti Waluya" style="height: 42px; width: auto; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));" class="me-1">
                <div>
                    <span class="d-block" style="line-height: 1.1; font-weight: 800; letter-spacing: -0.02em;">STIKES PANTI WALUYA</span>
                    <small style="font-size: 0.65rem; color: #94a3b8; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase;">Sistem Inventaris & Aset Lab</small>
                </div>
            </a>
            
            <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fa-solid fa-bars-staggered"></i>
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
                        <a class="nav-link-custom {{ request()->routeIs('ruangan.*') ? 'active' : '' }}" href="{{ route('ruangan.index') }}">
                            <i class="fa-solid fa-door-open"></i>
                            <span>Ruangan</span>
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

                    @auth
                    <!-- User Profile & Logout Dropdown -->
                    <li class="nav-item dropdown ms-lg-3 mt-2 mt-lg-0">
                        <a class="btn btn-dark border border-secondary text-white dropdown-toggle d-flex align-items-center gap-2 py-1.5 px-3 rounded-pill" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 26px; height: 26px; font-size: 0.75rem; background: {{ auth()->user()->role === 'it' ? 'linear-gradient(135deg, #4f46e5, #3b82f6)' : 'linear-gradient(135deg, #10b981, #059669)' }};">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="small fw-semibold text-truncate" style="max-width: 130px;">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2 p-2" style="min-width: 220px;">
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
                                <a class="dropdown-item small rounded-3 py-2 d-flex align-items-center gap-2" href="{{ route('user.index') }}">
                                    <i class="fa-solid fa-users-gear text-primary"></i>
                                    <span>Kelola Pengguna</span>
                                </a>
                            </li>
                            @endif
                            <li>
                                <form action="{{ route('logout') }}" method="POST" id="formLogout">
                                    @csrf
                                    <button type="submit" class="dropdown-item small rounded-3 py-2 text-danger d-flex align-items-center gap-2">
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
        <div class="container text-center text-md-between d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <div>
                <strong>STIKES Panti Waluya Malang</strong> &bull; Sistem Informasi Manajemen Aset &copy; {{ date('Y') }}
            </div>
            <div class="text-muted small">
                <i class="fa-solid fa-hospital text-primary me-1"></i> Lab Keperawatan, Kebidanan, & Sarpras
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>

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
                    popup: 'rounded-4 shadow-lg'
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
                    popup: 'rounded-4 shadow-lg'
                }
            });
        @endif

        // Global Delete Confirmation Function
        function confirmDelete(event, formElement, itemName = 'item ini') {
            event.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus ${itemName}? Data yang terhapus tidak dapat dikembalikan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    formElement.submit();
                }
            });
            return false;
        }

        // Initialize tooltips
        document.addEventListener("DOMContentLoaded", function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>

    @stack('scripts')
</body>
</html>