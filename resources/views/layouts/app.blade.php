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
    <link rel="icon" type="image/png" href="{{ asset('images/logo-stikes.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 & FontAwesome 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css">
    
    <style>
        :root {
            /* Institutional Palette (STIKES Panti Waluya) */
            --color-navy: #0F2C59;
            --color-sapphire: #1E3A8A;
            --color-blue: #2563EB;
            --color-ice: #F0F7FF;
            --color-sky: #BAE6FD;
            
            --primary: #1E3A8A;
            --primary-hover: #0F2C59;
            --primary-light: #EFF6FF;
            --primary-gradient: linear-gradient(180deg, #1E3A8A 0%, #0F2C59 100%);
            --secondary: #475569;
            --accent: #0F2C59;
            --success: #15803D;
            --warning: #B45309;
            --danger: #B91C1C;
            --dark: #0F172A;
            --card-bg: #FFFFFF;
            --body-bg: #F8FAFC;
            --border-color: #E2E8F0;
            --text-main: #0F172A;
            --text-muted: #475569;
            
            /* Sizing & Elevation */
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-xl: 16px;
            --shadow-sm: 0 1px 2px 0 rgba(15, 23, 42, 0.05);
            --shadow-md: 0 1px 3px 0 rgba(15, 23, 42, 0.08), 0 1px 2px -1px rgba(15, 23, 42, 0.04);
            --shadow-lg: 0 4px 6px -1px rgba(15, 23, 42, 0.08), 0 2px 4px -2px rgba(15, 23, 42, 0.04);
        }

        /* Dark Theme Ergonomic Tokens */
        [data-bs-theme="dark"] {
            --color-navy: #09182E;
            --color-sapphire: #1E3A8A;
            --color-blue: #3B82F6;
            --color-ice: #1E293B;
            --color-sky: #0369A1;
            
            --primary: #3B82F6;
            --primary-hover: #60A5FA;
            --primary-light: #1E293B;
            --primary-gradient: linear-gradient(180deg, #1E293B 0%, #0F172A 100%);
            --secondary: #94A3B8;
            --accent: #3B82F6;
            --dark: #F8FAFC;
            --card-bg: #1E293B;
            --body-bg: #0F172A;
            --border-color: #334155;
            --text-main: #F1F5F9;
            --text-muted: #94A3B8;
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

        /* Accessibility: Universal Focus Ring */
        :focus-visible {
            outline: 2px solid #2563EB !important;
            outline-offset: 2px !important;
        }

        /* Clean Institutional Header Navbar */
        .navbar-custom {
            background-color: #0F2C59;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            position: sticky;
            top: 0;
            z-index: 1030;
            padding: 0.65rem 0;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.15);
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

        .nav-link-custom {
            color: #E2E8F0 !important;
            font-weight: 600;
            font-size: 0.88rem;
            padding: 0.5rem 0.9rem !important;
            border-radius: var(--radius-md);
            transition: background-color 0.15s ease, color 0.15s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0 0.15rem;
        }

        .nav-link-custom:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-link-custom.active {
            color: #0F2C59 !important;
            background-color: #ffffff !important;
            font-weight: 700;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Cards */
        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .card-header-modern {
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.3rem;
            font-weight: 700;
            font-size: 0.95rem;
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
            padding: 1.2rem 1.3rem;
            box-shadow: var(--shadow-sm);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .card-stat:hover {
            border-color: #CBD5E1;
            box-shadow: var(--shadow-md);
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .stat-icon.primary { background: #EFF6FF; color: #1E3A8A; border: 1px solid #BFDBFE; }
        .stat-icon.success { background: #ECFDF5; color: #15803D; border: 1px solid #A7F3D0; }
        .stat-icon.warning { background: #FFFBEB; color: #B45309; border: 1px solid #FDE68A; }
        .stat-icon.danger  { background: #FEF2F2; color: #B91C1C; border: 1px solid #FECACA; }
        .stat-icon.info    { background: #F0F9FF; color: #0369A1; border: 1px solid #BAE6FD; }

        .stat-value {
            font-size: 1.55rem;
            font-weight: 800;
            line-height: 1.2;
            color: var(--text-main);
            letter-spacing: -0.02em;
        }

        .stat-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        /* Tables */
        .table-modern {
            margin-bottom: 0;
        }

        .table-modern thead th {
            background-color: #F8FAFC;
            color: #334155;
            font-size: 0.76rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .table-modern tbody td {
            padding: 0.85rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #F1F5F9;
            font-size: 0.88rem;
        }

        .table-modern tbody tr:hover {
            background-color: #F8FAFC;
        }

        /* Badges & Status */
        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.3rem 0.65rem;
            border-radius: 9999px;
            font-size: 0.76rem;
            font-weight: 600;
        }

        .badge-status::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
        }

        .badge-status-baik {
            background-color: #DCFCE7;
            color: #14532D;
            border: 1px solid #86EFAC;
        }
        .badge-status-baik::before {
            background-color: #16A34A;
        }

        .badge-status-ringan {
            background-color: #FEF3C7;
            color: #78350F;
            border: 1px solid #FDE68A;
        }
        .badge-status-ringan::before {
            background-color: #D97706;
        }

        .badge-status-berat {
            background-color: #FEE2E2;
            color: #7F1D1D;
            border: 1px solid #FECACA;
        }
        .badge-status-berat::before {
            background-color: #DC2626;
        }

        .badge-pill-custom {
            padding: 0.3rem 0.65rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.76rem;
        }

        .badge-code {
            background-color: #F8FAFC;
            color: #0F172A;
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
            font-size: 0.8rem;
            padding: 0.25rem 0.55rem;
            border-radius: var(--radius-sm);
            font-weight: 700;
            border: 1px solid #CBD5E1;
            letter-spacing: 0.02em;
            display: inline-block;
        }

        .badge-room-code {
            background-color: #EFF6FF;
            color: #1E3A8A;
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
            font-size: 0.8rem;
            padding: 0.25rem 0.55rem;
            border-radius: var(--radius-sm);
            font-weight: 700;
            border: 1px solid #BFDBFE;
            letter-spacing: 0.02em;
            display: inline-block;
        }

        .badge-category {
            background-color: #F1F5F9;
            color: #334155;
            border: 1px solid #E2E8F0;
            font-weight: 600;
            padding: 0.25rem 0.55rem;
            border-radius: var(--radius-sm);
            font-size: 0.78rem;
        }

        /* Buttons & Actions */
        .btn {
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 0.88rem;
            transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease, box-shadow 0.15s ease;
        }

        .btn-modern-primary {
            background-color: #1E3A8A;
            border: 1px solid #0F2C59;
            color: #ffffff;
            font-weight: 600;
            padding: 0.55rem 1.15rem;
            border-radius: var(--radius-md);
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-modern-primary:hover {
            background-color: #0F2C59;
            color: #ffffff;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            border-radius: var(--radius-sm);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.82rem;
            transition: background-color 0.15s ease, color 0.15s ease;
            border: 1px solid transparent;
        }

        .btn-icon-primary {
            background-color: #EFF6FF;
            color: #1E3A8A;
            border-color: #BFDBFE;
        }
        .btn-icon-primary:hover {
            background-color: #1E3A8A;
            color: #ffffff;
        }

        .btn-icon-info {
            background-color: #F0F9FF;
            color: #0369A1;
            border-color: #BAE6FD;
        }
        .btn-icon-info:hover {
            background-color: #0284C7;
            color: #ffffff;
        }

        .btn-icon-warning {
            background-color: #FFFBEB;
            color: #B45309;
            border-color: #FDE68A;
        }
        .btn-icon-warning:hover {
            background-color: #D97706;
            color: #ffffff;
        }

        .btn-icon-danger {
            background-color: #FEF2F2;
            color: #B91C1C;
            border-color: #FECACA;
        }
        .btn-icon-danger:hover {
            background-color: #DC2626;
            color: #ffffff;
        }

        /* Form Controls */
        .form-control, .form-select {
            border: 1px solid #CBD5E1;
            border-radius: var(--radius-md);
            padding: 0.55rem 0.85rem;
            font-size: 0.88rem;
            font-weight: 500;
            color: var(--text-main);
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #2563EB;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .form-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.35rem;
        }

        /* Modals */
        .modal-content {
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.15);
            overflow: hidden;
        }

        .modal-header {
            padding: 1.1rem 1.4rem;
            border-bottom: 1px solid var(--border-color);
            background-color: #FFFFFF;
        }

        .modal-body {
            padding: 1.4rem;
        }

        .modal-footer {
            padding: 0.9rem 1.4rem;
            border-top: 1px solid var(--border-color);
            background-color: #F8FAFC;
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
            background: #E2E8F0;
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
            background: #F1F5F9;
        }
        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }

        /* Footer */
        .footer-custom {
            margin-top: auto;
            background: #ffffff;
            border-top: 1px solid var(--border-color);
            padding: 1.15rem 0;
            font-size: 0.82rem;
            color: var(--text-muted);
        }

        /* Dark Theme Component Overrides */
        [data-bs-theme="dark"] .navbar-custom {
            background-color: #0B1329;
            border-bottom: 1px solid #1E293B;
        }

        [data-bs-theme="dark"] .card,
        [data-bs-theme="dark"] .card-stat,
        [data-bs-theme="dark"] .card-header-modern {
            background-color: #1E293B !important;
            border-color: #334155 !important;
            color: #F1F5F9 !important;
        }

        [data-bs-theme="dark"] .card-header-modern {
            border-bottom-color: #334155 !important;
        }

        /* Dark Mode Text & Color Utilities */
        [data-bs-theme="dark"] .text-dark { color: #F1F5F9 !important; }
        [data-bs-theme="dark"] .text-muted { color: #94A3B8 !important; }
        [data-bs-theme="dark"] .text-secondary { color: #CBD5E1 !important; }
        [data-bs-theme="dark"] .text-body { color: #F1F5F9 !important; }

        /* Dark Mode Backgrounds */
        [data-bs-theme="dark"] .bg-white { background-color: #1E293B !important; color: #F1F5F9 !important; }
        [data-bs-theme="dark"] .bg-light { background-color: #0F172A !important; border-color: #334155 !important; color: #94A3B8 !important; }
        [data-bs-theme="dark"] .bg-dark-subtle { background-color: #0F172A !important; color: #F1F5F9 !important; border-color: #334155 !important; }
        [data-bs-theme="dark"] .bg-primary-subtle { background-color: rgba(59, 130, 246, 0.15) !important; color: #60A5FA !important; border-color: rgba(96, 165, 250, 0.3) !important; }
        [data-bs-theme="dark"] .bg-secondary-subtle { background-color: rgba(148, 163, 184, 0.15) !important; color: #94A3B8 !important; border-color: rgba(148, 163, 184, 0.3) !important; }
        [data-bs-theme="dark"] .bg-warning-subtle { background-color: rgba(245, 158, 11, 0.15) !important; color: #FBBF24 !important; border-color: rgba(251, 191, 36, 0.3) !important; }
        [data-bs-theme="dark"] .bg-danger-subtle { background-color: rgba(239, 68, 68, 0.15) !important; color: #F87171 !important; border-color: rgba(248, 113, 113, 0.3) !important; }
        [data-bs-theme="dark"] .bg-info-subtle { background-color: rgba(14, 165, 233, 0.15) !important; color: #38BDF8 !important; border-color: rgba(56, 189, 248, 0.3) !important; }

        /* Dark Mode Badges */
        [data-bs-theme="dark"] .badge-code {
            background-color: #0F172A !important;
            color: #93C5FD !important;
            border: 1px solid #334155 !important;
        }
        [data-bs-theme="dark"] .badge-room-code {
            background-color: #0F172A !important;
            color: #60A5FA !important;
            border: 1px solid #1E3A8A !important;
        }
        [data-bs-theme="dark"] .badge-category {
            background-color: #0F172A !important;
            color: #CBD5E1 !important;
            border: 1px solid #334155 !important;
        }

        /* Dark Mode Status Badges */
        [data-bs-theme="dark"] .badge-status-baik {
            background-color: rgba(16, 185, 129, 0.15) !important;
            color: #34D399 !important;
            border: 1px solid rgba(52, 211, 153, 0.3) !important;
        }
        [data-bs-theme="dark"] .badge-status-baik::before {
            background-color: #34D399;
        }
        [data-bs-theme="dark"] .badge-status-ringan {
            background-color: rgba(245, 158, 11, 0.15) !important;
            color: #FBBF24 !important;
            border: 1px solid rgba(251, 191, 36, 0.3) !important;
        }
        [data-bs-theme="dark"] .badge-status-ringan::before {
            background-color: #FBBF24;
        }
        [data-bs-theme="dark"] .badge-status-berat {
            background-color: rgba(239, 68, 68, 0.15) !important;
            color: #F87171 !important;
            border: 1px solid rgba(248, 113, 113, 0.3) !important;
        }
        [data-bs-theme="dark"] .badge-status-berat::before {
            background-color: #F87171;
        }

        /* Dark Mode Stat Card Icons */
        [data-bs-theme="dark"] .stat-icon.primary { background-color: rgba(59, 130, 246, 0.15) !important; color: #60A5FA !important; border: 1px solid rgba(96, 165, 250, 0.25) !important; }
        [data-bs-theme="dark"] .stat-icon.success { background-color: rgba(16, 185, 129, 0.15) !important; color: #34D399 !important; border: 1px solid rgba(52, 211, 153, 0.25) !important; }
        [data-bs-theme="dark"] .stat-icon.warning { background-color: rgba(245, 158, 11, 0.15) !important; color: #FBBF24 !important; border: 1px solid rgba(251, 191, 36, 0.25) !important; }
        [data-bs-theme="dark"] .stat-icon.danger  { background-color: rgba(239, 68, 68, 0.15) !important; color: #F87171 !important; border: 1px solid rgba(248, 113, 113, 0.25) !important; }
        [data-bs-theme="dark"] .stat-icon.info    { background-color: rgba(14, 165, 233, 0.15) !important; color: #38BDF8 !important; border: 1px solid rgba(56, 189, 248, 0.25) !important; }

        /* Dark Mode Action Buttons */
        [data-bs-theme="dark"] .btn-icon-primary {
            background-color: rgba(59, 130, 246, 0.15) !important;
            color: #60A5FA !important;
            border-color: rgba(96, 165, 250, 0.3) !important;
        }
        [data-bs-theme="dark"] .btn-icon-primary:hover {
            background-color: #2563EB !important;
            color: #FFFFFF !important;
        }
        [data-bs-theme="dark"] .btn-icon-info {
            background-color: rgba(14, 165, 233, 0.15) !important;
            color: #38BDF8 !important;
            border-color: rgba(56, 189, 248, 0.3) !important;
        }
        [data-bs-theme="dark"] .btn-icon-info:hover {
            background-color: #0284C7 !important;
            color: #FFFFFF !important;
        }
        [data-bs-theme="dark"] .btn-icon-warning {
            background-color: rgba(245, 158, 11, 0.15) !important;
            color: #FBBF24 !important;
            border-color: rgba(251, 191, 36, 0.3) !important;
        }
        [data-bs-theme="dark"] .btn-icon-warning:hover {
            background-color: #D97706 !important;
            color: #FFFFFF !important;
        }
        [data-bs-theme="dark"] .btn-icon-danger {
            background-color: rgba(239, 68, 68, 0.15) !important;
            color: #F87171 !important;
            border-color: rgba(248, 113, 113, 0.3) !important;
        }
        [data-bs-theme="dark"] .btn-icon-danger:hover {
            background-color: #DC2626 !important;
            color: #FFFFFF !important;
        }

        [data-bs-theme="dark"] .btn-outline-dark {
            color: #CBD5E1 !important;
            border-color: #475569 !important;
        }
        [data-bs-theme="dark"] .btn-outline-dark:hover {
            background-color: #334155 !important;
            color: #FFFFFF !important;
            border-color: #64748B !important;
        }

        [data-bs-theme="dark"] .table-modern thead th {
            background-color: #0F172A !important;
            color: #94A3B8 !important;
            border-bottom-color: #334155 !important;
        }

        [data-bs-theme="dark"] .table-modern tbody td {
            border-bottom-color: #334155 !important;
            color: #F1F5F9 !important;
        }

        [data-bs-theme="dark"] .table-modern tbody tr:hover {
            background-color: #243248 !important;
        }

        [data-bs-theme="dark"] .input-group-text {
            background-color: #1E293B !important;
            border-color: #334155 !important;
            color: #94A3B8 !important;
        }

        [data-bs-theme="dark"] .form-control,
        [data-bs-theme="dark"] .form-select {
            background-color: #0F172A;
            border-color: #334155;
            color: #F1F5F9;
        }

        [data-bs-theme="dark"] .form-control:focus,
        [data-bs-theme="dark"] .form-select:focus {
            background-color: #0F172A;
            border-color: #3B82F6;
            color: #F1F5F9;
        }

        [data-bs-theme="dark"] .form-label {
            color: #CBD5E1;
        }

        [data-bs-theme="dark"] .modal-content {
            background-color: #1E293B;
            border-color: #334155;
            color: #F1F5F9;
        }

        [data-bs-theme="dark"] .modal-header,
        [data-bs-theme="dark"] .modal-footer {
            background-color: #0F172A;
            border-color: #334155;
        }

        [data-bs-theme="dark"] .dropdown-menu {
            background-color: #1E293B;
            border-color: #334155;
            color: #F1F5F9;
        }

        [data-bs-theme="dark"] .dropdown-item {
            color: #E2E8F0;
        }

        [data-bs-theme="dark"] .dropdown-item:hover {
            background-color: #334155;
            color: #FFFFFF;
        }

        [data-bs-theme="dark"] .text-success {
            color: #34D399 !important;
        }

        [data-bs-theme="dark"] .footer-custom {
            background-color: #0B1329 !important;
            border-top: 1px solid #1E293B !important;
            color: #94A3B8 !important;
        }

        [data-bs-theme="dark"] .nav-link-custom.active {
            color: #60A5FA !important;
            background-color: rgba(59, 130, 246, 0.18) !important;
            border: 1px solid rgba(96, 165, 250, 0.3) !important;
        }

        [data-bs-theme="dark"] ::-webkit-scrollbar-track {
            background: #0F172A;
        }
        [data-bs-theme="dark"] ::-webkit-scrollbar-thumb {
            background: #334155;
        }
        [data-bs-theme="dark"] ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }

        /* Theme Toggle Button */
        .theme-toggle-btn {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #F8FAFC;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .theme-toggle-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            color: #FFFFFF;
            transform: scale(1.05);
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
                <img src="{{ asset('images/logo-stikes.png') }}" alt="Logo STIKES Panti Waluya" style="height: 40px; width: auto; object-fit: contain;" class="me-1">
                <div>
                    <span class="d-block text-white" style="line-height: 1.1; font-weight: 800; letter-spacing: -0.02em;">SPARTA-PW</span>
                    <small style="font-size: 0.65rem; color: #CBD5E1; font-weight: 600; letter-spacing: 0.04em; text-transform: uppercase;">STIKES Panti Waluya Malang</small>
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
                        <a class="nav-link-custom {{ request()->routeIs('peminjaman.*') ? 'active' : '' }}" href="{{ route('peminjaman.index') }}">
                            <i class="fa-solid fa-hand-holding-hand"></i>
                            <span>Pinjam Aset</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-custom {{ request()->routeIs('peminjaman-ruangan.*') ? 'active' : '' }}" href="{{ route('peminjaman-ruangan.index') }}">
                            <i class="fa-solid fa-calendar-check"></i>
                            <span>Pinjam Ruangan</span>
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
        <div class="container text-center">
            <div>
                <strong>SPARTA-PW</strong> &bull; Sistem Peminjaman Aset & Ruangan Terpadu &copy; {{ date('Y') }} STIKES Panti Waluya Malang
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>

    <script>
        // Theme Switcher Functions
        function updateThemeUI(theme) {
            const icon = document.getElementById('themeIcon');
            if (!icon) return;
            if (theme === 'dark') {
                icon.className = 'fa-solid fa-sun text-warning';
            } else {
                icon.className = 'fa-solid fa-moon text-white';
            }
        }

        function toggleAppTheme() {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('sparta_theme', newTheme);
            updateThemeUI(newTheme);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
            updateThemeUI(currentTheme);
        });

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

        // Global Delete Confirmation Function
        function confirmDelete(event, formElement, itemName = 'item ini') {
            event.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus ${itemName}? Data yang terhapus tidak dapat dikembalikan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#B91C1C',
                cancelButtonColor: '#475569',
                confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-3'
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