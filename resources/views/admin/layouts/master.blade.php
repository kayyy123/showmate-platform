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
            --sidebar-bg: #0B132B;
            --sidebar-border: #1E293B;
            --sidebar-text: #94A3B8;
            --sidebar-hover: #16213E;
            --sidebar-active: #4F46E5;
            --sidebar-width: 270px;
            --body-bg: #F5F7FB;
            --card-bg: #FFFFFF;
            --card-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --card-radius: 16px;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--body-bg);
            color: #1E293B;
            min-height: 100vh;
        }
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0; z-index: 1025;
            background: rgba(0,0,0,0.5);
        }
        .sidebar-overlay.show { display: block; }

        .sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
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
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .brand-icon {
            width: 44px; height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #7C3AED, #4F46E5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #fff;
            flex-shrink: 0;
        }
        .brand-text { display: flex; flex-direction: column; }
        .brand-name {
            font-size: 1.05rem;
            font-weight: 700;
            color: #F1F5F9;
            letter-spacing: -0.02em;
            line-height: 1.3;
        }
        .brand-subtitle {
            font-size: 0.68rem;
            font-weight: 500;
            color: #64748B;
            letter-spacing: 0.02em;
        }
        .sidebar-nav {
            flex: 1;
            padding: 12px 12px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 14px;
            height: 48px;
            padding: 0 16px;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--sidebar-text);
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .sidebar-link i {
            font-size: 1.1rem;
            width: 22px;
            text-align: center;
            flex-shrink: 0;
        }
        .sidebar-link:hover {
            background-color: var(--sidebar-hover);
            color: #E2E8F0;
        }
        .sidebar-link.active {
            background-color: var(--sidebar-active);
            color: #FFFFFF;
            font-weight: 600;
        }
        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }
        .profile-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border-radius: 12px;
            background-color: rgba(255,255,255,0.04);
        }
        .profile-avatar {
            width: 40px; height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #7C3AED, #4F46E5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            color: #fff;
            flex-shrink: 0;
            overflow: hidden;
        }
        .profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .profile-info { flex: 1; min-width: 0; }
        .profile-name {
            font-size: 0.8rem;
            font-weight: 600;
            color: #E2E8F0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .profile-email {
            font-size: 0.68rem;
            color: #64748B;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .btn-logout-sidebar {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            margin-top: 6px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
            color: #EF4444;
            text-decoration: none;
            transition: all 0.2s;
            width: 100%;
            border: none;
            background: none;
            cursor: pointer;
        }
        .btn-logout-sidebar:hover { background-color: rgba(239,68,68,0.1); }
        .btn-logout-sidebar i { font-size: 1rem; }

        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .topbar {
            background: #FFFFFF;
            border-bottom: 1px solid #E2E8F0;
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1020;
        }
        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .btn-toggle-sidebar {
            display: none;
            background: none;
            border: none;
            color: #64748B;
            font-size: 1.4rem;
            cursor: pointer;
            padding: 4px;
        }
        .topbar-breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8rem;
            color: #94A3B8;
        }
        .topbar-breadcrumb a { color: #94A3B8; text-decoration: none; }
        .topbar-breadcrumb a:hover { color: #4F46E5; }
        .topbar-breadcrumb .sep { color: #CBD5E1; }
        .topbar-right { display: flex; align-items: center; gap: 16px; }
        .topbar-admin {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 4px 12px 4px 4px;
            border-radius: 10px;
            background-color: #F8FAFC;
        }
        .topbar-admin .mini-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #7C3AED, #4F46E5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.7rem;
            color: #fff;
        }
        .topbar-admin .mini-name { font-size: 0.8rem; font-weight: 500; color: #475569; }

        .content-wrapper { padding: 28px 32px; flex: 1; }

        .page-header { margin-bottom: 28px; }
        .page-header h1 { font-size: 1.5rem; font-weight: 800; color: #0F172A; margin: 0; }
        .page-header p { font-size: 0.88rem; color: #64748B; margin: 4px 0 0; }

        .card-stat {
            background: #FFFFFF;
            border: none;
            border-radius: var(--card-radius);
            box-shadow: var(--card-shadow);
            transition: all 0.2s ease;
            height: 100%;
        }
        .card-stat:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transform: translateY(-1px);
        }
        .card-stat .card-body {
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-stat .stat-info { flex: 1; }
        .card-stat .stat-label {
            font-size: 0.8rem;
            font-weight: 500;
            color: #94A3B8;
            margin-bottom: 4px;
        }
        .card-stat .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0F172A;
            line-height: 1.2;
        }
        .card-stat .stat-icon-wrap {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }
        .icon-blue { background-color: #EFF6FF; color: #3B82F6; }
        .icon-purple { background-color: #F5F3FF; color: #8B5CF6; }
        .icon-green { background-color: #F0FDF4; color: #22C55E; }
        .icon-amber { background-color: #FFFBEB; color: #F59E0B; }
        .icon-pink { background-color: #FDF2F8; color: #EC4899; }
        .icon-emerald { background-color: #ECFDF5; color: #10B981; }
        .icon-cyan { background-color: #ECFEFF; color: #06B6D4; }
        .icon-orange { background-color: #FFF7ED; color: #F97316; }

        .table-card {
            background: #FFFFFF;
            border: none;
            border-radius: var(--card-radius);
            box-shadow: var(--card-shadow);
        }
        .table-card .card-header {
            background: none;
            border-bottom: 1px solid #F1F5F9;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .table-card .card-header h5 {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0F172A;
            margin: 0;
        }
        .table-card .card-header .btn-link-custom {
            font-size: 0.8rem;
            font-weight: 600;
            color: #4F46E5;
            text-decoration: none;
            padding: 6px 14px;
            border-radius: 8px;
            background-color: #F8FAFC;
            transition: all 0.2s;
        }
        .table-card .card-header .btn-link-custom:hover {
            background-color: #EEF2FF;
            color: #4338CA;
        }
        .table-card .table {
            margin-bottom: 0;
        }
        .table-card .table th {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94A3B8;
            border-bottom: 1px solid #F1F5F9;
            padding: 12px 24px;
            background: none;
        }
        .table-card .table td {
            font-size: 0.84rem;
            color: #475569;
            border-bottom: 1px solid #F8FAFC;
            padding: 12px 24px;
            vertical-align: middle;
        }
        .table-card .table td .fw-semibold {
            font-weight: 600;
            color: #0F172A;
        }
        .table-card .table tr:last-child td { border-bottom: none; }
        .table-card .table td .text-muted { color: #94A3B8; }

        @media (max-width: 767.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .topbar { padding: 12px 16px; }
            .content-wrapper { padding: 20px 16px; }
            .btn-toggle-sidebar { display: block; }
            .topbar-breadcrumb { display: none; }
        }
        @media (min-width: 768px) and (max-width: 991.98px) {
            .content-wrapper { padding: 24px; }
            .topbar { padding: 14px 24px; }
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94A3B8; }
    </style>
    @stack('styles')
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <aside class="sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <div class="brand-icon"><i class="bi bi-shop-window"></i></div>
            <div class="brand-text">
                <span class="brand-name">ShowMate</span>
                <span class="brand-subtitle">Panel Admin</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
            <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Users
            </a>
            <a href="{{ route('admin.products') }}" class="sidebar-link {{ request()->routeIs('admin.products') ? 'active' : '' }}">
                <i class="bi bi-box-seam-fill"></i> Products
            </a>
            <a href="{{ route('admin.links') }}" class="sidebar-link {{ request()->routeIs('admin.links') ? 'active' : '' }}">
                <i class="bi bi-link-45deg"></i> Links
            </a>
            <a href="{{ route('admin.orders') }}" class="sidebar-link {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                <i class="bi bi-cart-check-fill"></i> Orders
            </a>
            <a href="{{ route('admin.statistics') }}" class="sidebar-link {{ request()->routeIs('admin.statistics') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-fill"></i> Statistics
            </a>
            <a href="{{ route('admin.settings') }}" class="sidebar-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <i class="bi bi-gear-fill"></i> Settings
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="profile-card">
                @php $adminUser = auth()->user(); @endphp
                <div class="profile-avatar">
                    @if($adminUser?->avatar)
                        <img src="{{ $adminUser->avatar }}" alt="">
                    @else
                        {{ strtoupper(substr($adminUser?->name ?? 'A', 0, 1)) }}
                    @endif
                </div>
                <div class="profile-info">
                    <div class="profile-name">{{ $adminUser?->name ?? 'Admin' }}</div>
                    <div class="profile-email">{{ $adminUser?->email ?? '' }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout-sidebar">
                    <i class="bi bi-box-arrow-right"></i> Logout
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
                <div class="topbar-breadcrumb">
                    <a href="{{ route('admin.dashboard') }}">Beranda</a>
                    <span class="sep">/</span>
                    <span>Admin</span>
                </div>
            </div>
            <div class="topbar-right">
                <div class="topbar-admin">
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
            document.querySelectorAll('.sidebar-link').forEach(function (link) {
                link.addEventListener('click', function () {
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
