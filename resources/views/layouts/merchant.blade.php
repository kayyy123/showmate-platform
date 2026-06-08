<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'ShowMate') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-sidebar: #0F172A;
            --sidebar-border: #1E293B;
            --sidebar-text: #E2E8F0;
            --sidebar-hover: #1E293B;
            --sidebar-active: #4F46E5;
            --sidebar-active-text: #FFFFFF;
            --sidebar-width: 270px;

            --bg-body: #0B0F14;
            --bg-card: #1A1F2E;
            --bg-card-hover: #1E2435;
            --accent: #FFD600;
            --accent-rgb: 255, 214, 0;
            --border: #2A2F3A;
            --text-primary: #FFFFFF;
            --text-secondary: #9CA3AF;
            --text-muted: #6B7280;

            --profile-bg: #111827;
            --profile-radius: 14px;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-primary);
            min-height: 100vh;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 1025;
            background: rgba(0,0,0,0.5);
        }
        .sidebar-overlay.show { display: block; }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background-color: var(--bg-sidebar);
            border-right: 1px solid var(--sidebar-border);
            display: flex;
            flex-direction: column;
            z-index: 1030;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 24px 24px 20px;
            border-bottom: 1px solid var(--sidebar-border);
        }
        .brand-icon {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: linear-gradient(135deg, #4F46E5, #7C3AED);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: #fff;
            flex-shrink: 0;
        }
        .brand-text { display: flex; flex-direction: column; }
        .brand-name {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--sidebar-text);
            letter-spacing: -0.02em;
            line-height: 1.3;
        }
        .brand-subtitle {
            font-size: 0.7rem;
            font-weight: 500;
            color: var(--text-muted);
            letter-spacing: 0.02em;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 14px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 14px;
            height: 50px;
            padding: 0 16px;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--sidebar-text);
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .sidebar-link:hover {
            background-color: var(--sidebar-hover);
            color: #fff;
        }
        .sidebar-link.active {
            background-color: var(--sidebar-active);
            color: var(--sidebar-active-text);
            font-weight: 600;
        }
        .sidebar-link-icon {
            font-size: 1.15rem;
            width: 22px;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar-footer {
            padding: 16px 14px;
            border-top: 1px solid var(--sidebar-border);
        }
        .profile-card {
            background-color: var(--profile-bg);
            border-radius: var(--profile-radius);
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .profile-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background-color: rgba(79, 70, 229, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
        }
        .profile-avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .profile-avatar-text {
            font-size: 1rem;
            font-weight: 700;
            color: #4F46E5;
        }
        .profile-info { flex: 1; min-width: 0; }
        .profile-name {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--sidebar-text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .profile-email {
            font-size: 0.7rem;
            color: var(--text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .profile-badge {
            display: inline-block;
            margin-top: 4px;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 0.6rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            background-color: rgba(255, 214, 0, 0.12);
            color: var(--accent);
            border: 1px solid rgba(255, 214, 0, 0.25);
        }
        .logout-form { margin-top: 8px; }
        .btn-logout {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 500;
            color: #ef4444;
            text-decoration: none;
            transition: all 0.2s;
            width: 100%;
            border: none;
            background: none;
            cursor: pointer;
        }
        .btn-logout:hover { background-color: rgba(239,68,68,0.1); }

        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            border-bottom: 1px solid var(--border);
            background-color: rgba(11, 15, 20, 0.85);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 1020;
        }
        .topbar-left { display: flex; align-items: center; gap: 16px; }
        .topbar-breadcrumb {
            display: flex; align-items: center; gap: 8px;
            font-size: 0.85rem; color: var(--text-secondary);
        }
        .topbar-breadcrumb a { color: var(--text-muted); text-decoration: none; }
        .topbar-breadcrumb a:hover { color: var(--accent); }
        .topbar-breadcrumb .sep { color: var(--text-muted); }
        .topbar-right { display: flex; align-items: center; gap: 12px; }
        .topbar-admin-info {
            display: flex; align-items: center; gap: 10px;
            padding: 4px 12px 4px 4px;
            border-radius: 10px;
            background-color: rgba(255,255,255,0.04);
        }
        .mini-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background-color: rgba(var(--accent-rgb), 0.15);
            color: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.75rem;
        }
        .mini-name { font-size: 0.8rem; font-weight: 500; color: var(--text-secondary); }

        .content-wrapper { padding: 28px 32px; flex: 1; }
        .btn-toggle-sidebar {
            display: none;
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 1.4rem;
            cursor: pointer;
            padding: 4px 8px;
        }

        .card-dashboard {
            background-color: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            transition: all 0.25s ease;
        }
        .card-dashboard:hover {
            background-color: var(--bg-card-hover);
            border-color: rgba(var(--accent-rgb), 0.2);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }
        .card-dashboard .card-body { padding: 22px 24px; }
        .stat-icon {
            width: 44px; height: 44px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
        }
        .stat-label { font-size: 0.8rem; color: var(--text-secondary); font-weight: 500; text-transform: uppercase; letter-spacing: 0.04em; }
        .stat-value { font-size: 1.75rem; font-weight: 800; color: var(--text-primary); line-height: 1.2; }
        .badge-status { font-size: 0.7rem; font-weight: 600; padding: 4px 10px; border-radius: 20px; }
        .table-dashboard {
            --bs-table-bg: transparent;
            --bs-table-color: var(--text-primary);
            --bs-table-border-color: var(--border);
            --bs-table-hover-bg: rgba(255,255,255,0.03);
            --bs-table-striped-bg: rgba(255,255,255,0.02);
            margin-bottom: 0;
        }
        .table-dashboard th {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
            padding: 12px 16px;
        }
        .table-dashboard td {
            font-size: 0.85rem;
            color: var(--text-secondary);
            border-bottom: 1px solid rgba(42, 47, 58, 0.5);
            padding: 12px 16px;
            vertical-align: middle;
        }
        .table-dashboard td .fw-semibold { color: var(--text-primary); }
        .card-header-custom {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-header-custom h5 { margin: 0; font-size: 0.95rem; font-weight: 700; }
        .page-title-section { margin-bottom: 24px; }
        .page-title-section h1 { font-size: 1.4rem; font-weight: 800; margin: 0; }
        .page-title-section p { font-size: 0.85rem; color: var(--text-secondary); margin: 4px 0 0; }

        .form-control, .form-select {
            background-color: var(--bg-card);
            border: 1px solid var(--border);
            color: var(--text-primary);
            border-radius: 12px;
            padding: 10px 16px;
            font-size: 0.9rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 2px rgba(var(--accent-rgb), 0.15);
            background-color: var(--bg-card);
            color: var(--text-primary);
        }
        .form-control::placeholder { color: var(--text-muted); }
        .btn-primary {
            background-color: var(--accent);
            border-color: var(--accent);
            color: #000;
            font-weight: 600;
            border-radius: 12px;
            padding: 10px 20px;
        }
        .btn-primary:hover { background-color: #ffe44d; border-color: #ffe44d; color: #000; }
        label { font-size: 0.85rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 4px; }

        a { color: var(--accent); text-decoration: none; }
        a:hover { color: #ffe44d; }
        hr { border-color: var(--border); }

        @media (max-width: 767.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .topbar { padding: 0 16px; }
            .content-wrapper { padding: 20px 16px; }
            .btn-toggle-sidebar { display: block; }
            .topbar-breadcrumb { display: none; }
            .topbar-admin-info .mini-name { display: none; }
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--sidebar-border); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }
    </style>
    @stack('styles')
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    @include('layouts.sidebar', ['activeTab' => $activeTab ?? 'manage'])

    <div class="main-wrapper">
        <header class="topbar">
            <div class="topbar-left">
                <button class="btn-toggle-sidebar" id="sidebarToggle" aria-label="Toggle sidebar">
                    <i class="bi bi-list"></i>
                </button>
                <div class="topbar-breadcrumb">
                    <a href="{{ route('merchant.manage') }}">Merchant</a>
                    <span class="sep">/</span>
                    <span>@yield('breadcrumb', 'Dashboard')</span>
                </div>
            </div>
            <div class="topbar-right">
                <div class="topbar-admin-info">
                    <div class="mini-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
                    <span class="mini-name">{{ auth()->user()->name ?? 'User' }}</span>
                </div>
            </div>
        </header>

        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('merchantSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const toggle = document.getElementById('sidebarToggle');
            if (toggle && sidebar && overlay) {
                toggle.addEventListener('click', function () {
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                });
                overlay.addEventListener('click', function () {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                });
            }
            document.querySelectorAll('.sidebar-link').forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 767.98) {
                        sidebar.classList.remove('show');
                        overlay.classList.remove('show');
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
