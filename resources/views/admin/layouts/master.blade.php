<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name', 'ShowMate') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <style>
        :root {
            --bg-body: #0B0F14;
            --bg-sidebar: #111827;
            --bg-card: #1A1F2E;
            --bg-card-hover: #1E2435;
            --accent: #FFD600;
            --accent-rgb: 255, 214, 0;
            --border: #2A2F3A;
            --text-primary: #FFFFFF;
            --text-secondary: #9CA3AF;
            --text-muted: #6B7280;
            --sidebar-width: 260px;
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
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background-color: var(--bg-sidebar);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            z-index: 1030;
            overflow-y: auto;
        }
        .sidebar-brand {
            display: flex;
            align-items: center;
            height: 64px;
            padding: 0 24px;
            border-bottom: 1px solid var(--border);
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text-primary);
            text-decoration: none;
            letter-spacing: -0.02em;
        }
        .sidebar-brand:hover { color: var(--accent); }
        .sidebar-brand .brand-dot {
            width: 8px; height: 8px;
            background-color: var(--accent);
            border-radius: 50%;
            display: inline-block;
            margin-right: 10px;
        }
        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
        }
        .sidebar-nav .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.2s ease;
            margin-bottom: 2px;
        }
        .sidebar-nav .nav-item i { font-size: 1.1rem; width: 20px; text-align: center; }
        .sidebar-nav .nav-item:hover {
            background-color: rgba(255,255,255,0.05);
            color: var(--text-primary);
        }
        .sidebar-nav .nav-item.active {
            background-color: rgba(var(--accent-rgb), 0.12);
            color: var(--accent);
        }
        .sidebar-nav .nav-label {
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-muted);
            padding: 12px 14px 6px;
        }
        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid var(--border);
        }
        .sidebar-footer .admin-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 12px;
        }
        .sidebar-footer .admin-avatar {
            width: 38px; height: 38px;
            border-radius: 50%;
            background-color: rgba(var(--accent-rgb), 0.15);
            color: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            flex-shrink: 0;
        }
        .sidebar-footer .admin-info { flex: 1; min-width: 0; }
        .sidebar-footer .admin-name {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sidebar-footer .admin-email {
            font-size: 0.7rem;
            color: var(--text-secondary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sidebar-footer .btn-logout {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
            color: #ef4444;
            text-decoration: none;
            transition: all 0.2s;
            width: 100%;
            border: none;
            background: none;
            cursor: pointer;
        }
        .sidebar-footer .btn-logout:hover { background-color: rgba(239,68,68,0.1); }
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
            padding: 0 32px;
            border-bottom: 1px solid var(--border);
            background-color: rgba(11, 15, 20, 0.85);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 1020;
        }
        .topbar-left { display: flex; align-items: center; gap: 16px; }
        .topbar-title { font-size: 1.1rem; font-weight: 700; }
        .topbar-breadcrumb {
            display: flex; align-items: center; gap: 8px;
            font-size: 0.8rem; color: var(--text-secondary);
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
        .topbar-admin-info .mini-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background-color: rgba(var(--accent-rgb), 0.15);
            color: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.75rem;
        }
        .topbar-admin-info .mini-name { font-size: 0.8rem; font-weight: 500; }
        .content-wrapper { padding: 28px 32px; flex: 1; }
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
        .chart-container { position: relative; height: 260px; width: 100%; }
        .chart-container canvas { max-height: 260px; }
        .card-header-custom {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-header-custom h5 { margin: 0; font-size: 0.95rem; font-weight: 700; }
        .page-title-section { margin-bottom: 24px; }
        .page-title-section h1 { font-size: 1.4rem; font-weight: 800; margin: 0; }
        .page-title-section p { font-size: 0.85rem; color: var(--text-secondary); margin: 4px 0 0; }

        .btn-toggle-sidebar {
            display: none;
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 1.3rem;
            cursor: pointer;
            padding: 4px 8px;
        }

        @media (max-width: 767.98px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            .sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .topbar { padding: 0 16px; }
            .content-wrapper { padding: 20px 16px; }
            .btn-toggle-sidebar { display: block; }
            .sidebar-overlay {
                display: none;
                position: fixed; inset: 0; z-index: 1025;
                background: rgba(0,0,0,0.5);
            }
            .sidebar-overlay.show { display: block; }
            .topbar-breadcrumb { display: none; }
            .topbar-admin-info .mini-name { display: none; }
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }
        a { color: var(--accent); text-decoration: none; }
        a:hover { color: #ffe44d; }
        .dropdown-menu {
            background-color: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
        }
        .dropdown-menu .dropdown-item {
            color: var(--text-secondary);
            font-size: 0.85rem;
            padding: 8px 16px;
        }
        .dropdown-menu .dropdown-item:hover { background-color: rgba(255,255,255,0.05); color: var(--text-primary); }
        .dropdown-menu .dropdown-divider { border-color: var(--border); }
    </style>
    @stack('styles')
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <aside class="sidebar" id="adminSidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <span class="brand-dot"></span>
            ShowMate Admin
        </a>
        <nav class="sidebar-nav">
            <div class="nav-label">Menu</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                Dashboard
            </a>
            <a href="{{ route('admin.users') }}" class="nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                Users
            </a>
            <a href="{{ route('admin.products') }}" class="nav-item {{ request()->routeIs('admin.products') ? 'active' : '' }}">
                <i class="bi bi-box-seam-fill"></i>
                Products
            </a>
            <a href="{{ route('admin.links') }}" class="nav-item {{ request()->routeIs('admin.links') ? 'active' : '' }}">
                <i class="bi bi-link-45deg"></i>
                Links
            </a>
            <a href="{{ route('admin.orders') }}" class="nav-item {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                <i class="bi bi-cart-check-fill"></i>
                Orders
            </a>
            <a href="{{ route('admin.statistics') }}" class="nav-item {{ request()->routeIs('admin.statistics') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-fill"></i>
                Statistics
            </a>
            <a href="{{ route('admin.settings') }}" class="nav-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <i class="bi bi-gear-fill"></i>
                Settings
            </a>
        </nav>
        <div class="sidebar-footer">
            <div class="admin-profile">
                <div class="admin-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                <div class="admin-info">
                    <div class="admin-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                    <div class="admin-email">{{ auth()->user()->email ?? '' }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="main-wrapper">
        <header class="topbar">
            <div class="topbar-left">
                <button class="btn-toggle-sidebar" id="sidebarToggle" aria-label="Toggle sidebar">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <div class="topbar-title">@yield('page_title', 'Dashboard')</div>
                    <div class="topbar-breadcrumb">
                        <a href="{{ route('admin.dashboard') }}">Admin</a>
                        <span class="sep">/</span>
                        <span>@yield('breadcrumb', 'Dashboard')</span>
                    </div>
                </div>
            </div>
            <div class="topbar-right">
                <div class="topbar-admin-info">
                    <div class="mini-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                    <span class="mini-name">{{ auth()->user()->name ?? 'Admin' }}</span>
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
            const sidebar = document.getElementById('adminSidebar');
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
        });
    </script>
    @stack('scripts')
</body>
</html>
