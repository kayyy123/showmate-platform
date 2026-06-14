<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'ShowMate') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: { 900: '#08152F', 800: '#0F1F3D', 700: '#1E293B' },
                        purple: { 500: '#5B4DF8', 600: '#4F46E5' },
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                }
            }
        }
    </script>
    <style>
        :root {
            --sidebar-width: 270px;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --transition: 300ms ease;

            --primary: #003366;
            --primary-hover: #002244;
            --primary-light: #E8EEF4;
            --secondary: #FFD700;
            --accent: #E60000;
            --bg-body: #F5F5F5;
            --bg-surface: #FFFFFF;
            --text-primary: #1F2937;
            --text-secondary: #475569;
            --text-muted: #94A3B8;
            --text-heading: #0F172A;
            --border: #D1D5DB;
            --border-light: #E5E7EB;
            --card-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --card-shadow-hover: 0 4px 12px rgba(0,0,0,0.08);
            --navbar-bg: #FFFFFF;
            --navbar-border: #E5E7EB;
            --sidebar-hover: #F8FAFC;
            --input-bg: #F8FAFC;
            --input-border: #D1D5DB;
            --input-focus: var(--primary);
            --table-header-bg: #F8FAFC;
            --table-header-text: #94A3B8;
            --table-border: #F1F5F9;
            --table-hover: #FAFBFC;
            --table-cell: #475569;
            --table-bold: #0F172A;
            --badge-blue-bg: #DBEAFE;
            --badge-blue: #2563EB;
            --badge-green-bg: #D1FAE5;
            --badge-green: #059669;
            --badge-red-bg: #FEE2E2;
            --badge-red: #DC2626;
            --badge-yellow-bg: #FEF3C7;
            --badge-yellow: #D97706;
            --overlay: rgba(0,0,0,0.4);
            --scrollbar: #CBD5E1;
            --scrollbar-hover: #94A3B8;
            --icon-primary: #3B82F6;
            --icon-primary-bg: #EFF6FF;
            --icon-purple: #8B5CF6;
            --icon-purple-bg: #F5F3FF;
            --icon-green: #10B981;
            --icon-green-bg: #ECFDF5;
            --icon-amber: #F59E0B;
            --icon-amber-bg: #FFFBEB;
            --icon-pink: #EC4899;
            --icon-pink-bg: #FDF2F8;
            --icon-cyan: #06B6D4;
            --icon-cyan-bg: #ECFEFF;
            --icon-orange: #F97316;
            --icon-orange-bg: #FFF7ED;
            --theme-btn-bg: #F1F5F9;
            --theme-btn-color: #64748B;
            --theme-btn-hover: #E2E8F0;
            --dropdown-bg: #FFFFFF;
            --dropdown-border: #E5E7EB;
            --dropdown-hover: #F8FAFC;
            --search-bg: #F8FAFC;
            --search-border: #E5E7EB;
            --search-text: #1F2937;
            --search-placeholder: #94A3B8;
            --notif-bg: #F8FAFC;
            --notif-hover: #F1F5F9;
        }

        [data-theme="dark"] {
            --primary: #FFD700;
            --primary-hover: #FFE44D;
            --primary-light: #2A2A2A;
            --secondary: #003366;
            --accent: #00F1FF;
            --bg-body: #121212;
            --bg-surface: #1E1E1E;
            --text-primary: #FFFFFF;
            --text-secondary: #9CA3AF;
            --text-muted: #6B7280;
            --text-heading: #F1F5F9;
            --border: #2D2D2D;
            --border-light: #333333;
            --card-shadow: 0 1px 3px rgba(0,0,0,0.3), 0 1px 2px rgba(0,0,0,0.2);
            --card-shadow-hover: 0 4px 16px rgba(0,0,0,0.4);
            --navbar-bg: #1A1A1A;
            --navbar-border: #2D2D2D;
            --sidebar-hover: #1A1A1A;
            --input-bg: #2A2A2A;
            --input-border: #3D3D3D;
            --input-focus: var(--primary);
            --table-header-bg: #2A2A2A;
            --table-header-text: #6B7280;
            --table-border: #2D2D2D;
            --table-hover: #2A2A2A;
            --table-cell: #9CA3AF;
            --table-bold: #F1F5F9;
            --badge-blue-bg: rgba(37,99,235,0.15);
            --badge-blue: #60A5FA;
            --badge-green-bg: rgba(5,150,105,0.15);
            --badge-green: #34D399;
            --badge-red-bg: rgba(220,38,38,0.15);
            --badge-red: #F87171;
            --badge-yellow-bg: rgba(217,119,6,0.15);
            --badge-yellow: #FBBF24;
            --overlay: rgba(0,0,0,0.7);
            --scrollbar: #3D3D3D;
            --scrollbar-hover: #555555;
            --icon-primary: #60A5FA;
            --icon-primary-bg: rgba(59,130,246,0.15);
            --icon-purple: #A78BFA;
            --icon-purple-bg: rgba(139,92,246,0.15);
            --icon-green: #34D399;
            --icon-green-bg: rgba(16,185,129,0.15);
            --icon-amber: #FBBF24;
            --icon-amber-bg: rgba(245,158,11,0.15);
            --icon-pink: #F472B6;
            --icon-pink-bg: rgba(236,72,153,0.15);
            --icon-cyan: #22D3EE;
            --icon-cyan-bg: rgba(6,182,212,0.15);
            --icon-orange: #FB923C;
            --icon-orange-bg: rgba(249,115,22,0.15);
            --theme-btn-bg: #2A2A2A;
            --theme-btn-color: #FFD700;
            --theme-btn-hover: #333333;
            --dropdown-bg: #1E1E1E;
            --dropdown-border: #2D2D2D;
            --dropdown-hover: #2A2A2A;
            --search-bg: #2A2A2A;
            --search-border: #3D3D3D;
            --search-text: #FFFFFF;
            --search-placeholder: #6B7280;
            --notif-bg: #2A2A2A;
            --notif-hover: #333333;
        }

        *, *::before, *::after {
            transition: background-color var(--transition),
                        color var(--transition),
                        border-color var(--transition),
                        box-shadow var(--transition);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0; padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-primary);
            min-height: 100vh;
        }
        a { text-decoration: none; color: inherit; }

        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0; z-index: 1025;
            background: var(--overlay);
        }
        .sidebar-overlay.show { display: block; }

        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh; display: flex; flex-direction: column;
        }
        .navbar {
            position: sticky; top: 0; z-index: 1020;
            background: var(--navbar-bg);
            border-bottom: 1px solid var(--navbar-border);
            padding: 0 28px; height: 64px;
            display: flex; align-items: center; justify-content: space-between;
            gap: 16px;
        }
        .navbar-left { display: flex; align-items: center; gap: 12px; flex-shrink: 0; }
        .btn-toggle-nav {
            display: none; background: none; border: none;
            color: var(--text-muted); font-size: 1.4rem; cursor: pointer; padding: 4px;
        }
        .navbar-page-title { font-size: 1rem; font-weight: 700; color: var(--text-heading); white-space: nowrap; }

        .navbar-search {
            flex: 1; max-width: 420px; position: relative;
        }
        .navbar-search .search-icon {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            color: var(--text-muted); font-size: 0.9rem; pointer-events: none;
        }
        .navbar-search input {
            width: 100%; height: 40px;
            background: var(--search-bg);
            border: 1px solid var(--search-border);
            border-radius: var(--radius-md);
            padding: 0 16px 0 40px;
            font-size: 0.85rem;
            color: var(--search-text);
            outline: none;
            transition: border-color 0.2s;
        }
        .navbar-search input::placeholder { color: var(--search-placeholder); }
        .navbar-search input:focus { border-color: var(--input-focus); }

        .navbar-right { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
        .btn-icon {
            width: 38px; height: 38px;
            border-radius: var(--radius-md);
            border: none; background: none;
            color: var(--text-muted);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem; cursor: pointer;
            position: relative;
            transition: all 0.2s;
        }
        .btn-icon:hover { background: var(--notif-bg); color: var(--text-primary); }
        .btn-icon .notif-dot {
            position: absolute; top: 8px; right: 8px;
            width: 7px; height: 7px; border-radius: 50%;
            background: var(--accent);
        }
        .theme-toggle {
            width: 38px; height: 38px;
            border-radius: var(--radius-md);
            border: none;
            background: var(--theme-btn-bg);
            color: var(--theme-btn-color);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; cursor: pointer;
            transition: all 0.2s;
        }
        .theme-toggle:hover { background: var(--theme-btn-hover); transform: scale(1.05); }

        .user-dropdown-btn {
            display: flex; align-items: center; gap: 8px;
            padding: 4px 10px 4px 4px;
            border-radius: var(--radius-md);
            border: none; background: none; cursor: pointer;
            transition: all 0.2s;
        }
        .user-dropdown-btn:hover { background: var(--notif-bg); }
        .user-dropdown-btn .ud-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: var(--primary);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.7rem; color: #fff;
        }
        .user-dropdown-btn .ud-name { font-size: 0.82rem; font-weight: 500; color: var(--text-primary); }
        .user-dropdown-btn .ud-chevron { font-size: 0.7rem; color: var(--text-muted); }

        .dropdown-custom {
            background: var(--dropdown-bg);
            border: 1px solid var(--dropdown-border);
            border-radius: var(--radius-md);
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            padding: 6px;
            min-width: 200px;
        }
        .dropdown-custom .dropdown-item {
            border-radius: var(--radius-sm);
            padding: 8px 12px;
            font-size: 0.85rem;
            color: var(--text-secondary);
            transition: all 0.15s;
            display: flex; align-items: center; gap: 10px;
        }
        .dropdown-custom .dropdown-item i { font-size: 1rem; width: 18px; text-align: center; }
        .dropdown-custom .dropdown-item:hover { background: var(--dropdown-hover); color: var(--text-primary); }
        .dropdown-custom .dropdown-divider { border-color: var(--border-light); margin: 4px 0; }
        .dropdown-custom .dropdown-item.text-danger { color: #EF4444; }
        .dropdown-custom .dropdown-item.text-danger:hover { background: rgba(239,68,68,0.1); }

        .content-wrapper { padding: 24px 28px; flex: 1; }

        .page-header { margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
        .page-header-left { }
        .page-header h1 { font-size: 1.35rem; font-weight: 800; color: var(--text-heading); margin: 0; }
        .page-header p { font-size: 0.85rem; color: var(--text-muted); margin: 2px 0 0; }
        .page-header-right { display: flex; align-items: center; gap: 8px; }
        .breadcrumb-custom {
            display: flex; align-items: center; gap: 6px;
            font-size: 0.8rem; color: var(--text-muted); margin-bottom: 4px;
        }
        .breadcrumb-custom a { color: var(--text-muted); }
        .breadcrumb-custom a:hover { color: var(--primary); }
        .breadcrumb-custom .sep { color: var(--text-muted); font-size: 0.6rem; }

        .stat-card {
            background: var(--bg-surface);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-lg);
            box-shadow: var(--card-shadow);
            transition: all 0.25s ease;
            height: 100%;
        }
        .stat-card:hover {
            box-shadow: var(--card-shadow-hover);
            transform: translateY(-2px);
            border-color: var(--primary);
        }
        .stat-card .card-body { padding: 20px 22px; }
        .stat-card .stat-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 12px; }
        .stat-card .stat-icon {
            width: 44px; height: 44px;
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; flex-shrink: 0;
        }
        .stat-card .stat-badge { font-size: 0.6rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; padding: 2px 10px; border-radius: 20px; }
        .stat-card .stat-label { font-size: 0.78rem; font-weight: 500; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.04em; }
        .stat-card .stat-value { font-size: 1.65rem; font-weight: 800; color: var(--text-heading); line-height: 1.2; }
        .stat-card .stat-footer { font-size: 0.75rem; color: var(--text-muted); margin-top: 4px; }

        .icon-primary { background: var(--icon-primary-bg); color: var(--icon-primary); }
        .icon-purple { background: var(--icon-purple-bg); color: var(--icon-purple); }
        .icon-green { background: var(--icon-green-bg); color: var(--icon-green); }
        .icon-amber { background: var(--icon-amber-bg); color: var(--icon-amber); }
        .icon-pink { background: var(--icon-pink-bg); color: var(--icon-pink); }
        .icon-cyan { background: var(--icon-cyan-bg); color: var(--icon-cyan); }
        .icon-orange { background: var(--icon-orange-bg); color: var(--icon-orange); }

        .card-custom {
            background: var(--bg-surface);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-lg);
            box-shadow: var(--card-shadow);
        }
        .card-custom .card-header-custom {
            padding: 18px 22px;
            border-bottom: 1px solid var(--table-border);
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 8px;
        }
        .card-custom .card-header-custom h5 { font-size: 0.95rem; font-weight: 700; color: var(--text-heading); margin: 0; display: flex; align-items: center; gap: 8px; }
        .card-custom .card-body-custom { padding: 0; }
        .btn-custom-primary {
            display: inline-flex; align-items: center; gap: 6px;
            height: 38px; padding: 0 18px;
            border-radius: var(--radius-md);
            background: var(--primary);
            color: #fff;
            border: none; font-size: 0.85rem; font-weight: 600;
            cursor: pointer; transition: all 0.2s;
        }
        .btn-custom-primary:hover { background: var(--primary-hover); transform: translateY(-1px); }
        .btn-custom-outline {
            display: inline-flex; align-items: center; gap: 6px;
            height: 38px; padding: 0 18px;
            border-radius: var(--radius-md);
            background: none;
            color: var(--text-secondary);
            border: 1px solid var(--border);
            font-size: 0.85rem; font-weight: 500;
            cursor: pointer; transition: all 0.2s;
        }
        .btn-custom-outline:hover { background: var(--sidebar-hover); border-color: var(--text-muted); }
        .btn-custom-sm { height: 32px; padding: 0 14px; font-size: 0.8rem; border-radius: var(--radius-sm); }
        .btn-custom-danger { background: #EF4444; color: #fff; }
        .btn-custom-danger:hover { background: #DC2626; }
        .btn-custom-link {
            font-size: 0.82rem; font-weight: 600;
            color: var(--primary); background: none; border: none;
            cursor: pointer; padding: 6px 12px; border-radius: var(--radius-sm);
            transition: all 0.2s;
            display: inline-flex; align-items: center; gap: 4px;
        }
        .btn-custom-link:hover { background: var(--sidebar-hover); }

        .table-modern { margin-bottom: 0; }
        .table-modern thead th {
            font-size: 0.7rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.05em; color: var(--table-header-text);
            background: var(--table-header-bg);
            border-bottom: 1px solid var(--table-border);
            padding: 14px 22px;
            position: sticky; top: 0; z-index: 5;
        }
        .table-modern tbody td {
            font-size: 0.84rem; color: var(--table-cell);
            border-bottom: 1px solid var(--table-border);
            padding: 14px 22px;
            vertical-align: middle;
        }
        .table-modern tbody tr:hover td { background: var(--table-hover); }
        .table-modern tbody tr:last-child td { border-bottom: none; }
        .table-modern td .fw-semibold { font-weight: 600; color: var(--table-bold); }
        .table-modern .text-muted-tbl { color: var(--text-muted); }

        .badge-custom {
            font-size: 0.7rem; font-weight: 600;
            padding: 3px 12px; border-radius: 20px;
            display: inline-block;
        }
        .badge-blue { background: var(--badge-blue-bg); color: var(--badge-blue); }
        .badge-green { background: var(--badge-green-bg); color: var(--badge-green); }
        .badge-red { background: var(--badge-red-bg); color: var(--badge-red); }
        .badge-yellow { background: var(--badge-yellow-bg); color: var(--badge-yellow); }

        .form-custom {
            width: 100%; height: 42px;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: var(--radius-md);
            padding: 0 16px;
            font-size: 0.88rem; color: var(--text-primary);
            outline: none; transition: all 0.2s;
        }
        .form-custom:focus { border-color: var(--input-focus); box-shadow: 0 0 0 3px rgba(var(--input-focus), 0.1); }
        .form-custom::placeholder { color: var(--text-muted); }
        .form-custom-label { font-size: 0.82rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 4px; display: block; }
        select.form-custom { appearance: auto; }
        textarea.form-custom { height: auto; padding: 12px 16px; }

        .modal-custom .modal-content {
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .modal-custom .modal-header {
            border-bottom: 1px solid var(--table-border);
            padding: 18px 22px;
        }
        .modal-custom .modal-header .modal-title { font-size: 1rem; font-weight: 700; color: var(--text-heading); }
        .modal-custom .modal-header .btn-close { filter: none; }
        .modal-custom .modal-body { padding: 22px; }
        .modal-custom .modal-footer {
            border-top: 1px solid var(--table-border);
            padding: 16px 22px;
        }
        .modal-backdrop-custom { backdrop-filter: blur(4px); }

        .pagination-custom { margin-bottom: 0; gap: 4px; }
        .pagination-custom .page-item .page-link {
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text-secondary);
            background: none;
            padding: 6px 12px;
            font-size: 0.82rem;
            transition: all 0.2s;
        }
        .pagination-custom .page-item .page-link:hover { background: var(--sidebar-hover); border-color: var(--text-muted); }
        .pagination-custom .page-item.active .page-link { background: var(--primary); border-color: var(--primary); color: #fff; }
        .pagination-custom .page-item.disabled .page-link { opacity: 0.4; pointer-events: none; }

        @media (max-width: 767.98px) {
            #adminSidebar { transform: translateX(-100%); }
            #adminSidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .navbar { padding: 0 16px; }
            .content-wrapper { padding: 16px; }
            .btn-toggle-nav { display: block; }
            .navbar-search { max-width: 200px; }
            .navbar-search input { font-size: 0.8rem; }
            .user-dropdown-btn .ud-name { display: none; }
            .breadcrumb-custom { display: none; }
        }
        @media (min-width: 768px) and (max-width: 991.98px) {
            .content-wrapper { padding: 20px 24px; }
            .navbar { padding: 0 20px; }
            .navbar-search { max-width: 280px; }
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--scrollbar); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--scrollbar-hover); }
    </style>
    @stack('styles')
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <aside class="fixed top-0 left-0 h-screen w-[270px] bg-[#08152F] flex flex-col z-[1030] overflow-y-auto transition-transform duration-300 border-r border-[#1E293B]" id="adminSidebar">
        <div class="flex items-center gap-3.5 px-6 pt-7 pb-6">
            <div class="w-11 h-11 rounded-xl bg-[#5B4DF8] flex items-center justify-center text-white text-lg flex-shrink-0">
                <i class="bi bi-shop-window"></i>
            </div>
            <div>
                <div class="text-white font-bold text-[1.05rem] -tracking-[0.02em] leading-tight">ShowMate</div>
                <div class="text-[#94A3B8] text-[0.68rem] font-medium tracking-wide">Panel Admin</div>
            </div>
        </div>

        <nav class="flex-1 px-3.5 py-2 flex flex-col gap-1.5">
            @php
                $navItems = [
                    ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'bi-grid-1x2-fill'],
                    ['route' => 'admin.stores', 'label' => 'Toko', 'icon' => 'bi-shop'],
                    ['route' => 'admin.products', 'label' => 'Produk', 'icon' => 'bi-box-seam-fill'],
                    ['route' => 'admin.categories', 'label' => 'Kategori', 'icon' => 'bi-tags-fill'],
                    ['route' => 'admin.orders', 'label' => 'Pesanan', 'icon' => 'bi-cart-check-fill'],
                    ['route' => 'admin.withdrawals', 'label' => 'Penarikan', 'icon' => 'bi-wallet2'],
                    ['route' => 'admin.subscriptions.plans', 'label' => 'Langganan', 'icon' => 'bi-gem'],
                    ['route' => 'admin.inclusive-program', 'label' => 'Program Inklusif', 'icon' => 'bi-people-fill'],
                ];
            @endphp

            @foreach ($navItems as $item)
                @php
                    $isActive = $item['route'] !== '#' && request()->routeIs(($item['route'] === 'admin.dashboard' ? 'admin.dashboard' : $item['route'] . '.*'));
                @endphp
                <a href="{{ $item['route'] !== '#' ? route($item['route'] === 'admin.dashboard' ? 'admin.dashboard' : $item['route'] . '.index') : '#' }}"
                   class="flex items-center gap-3.5 h-[52px] px-4 rounded-xl text-sm font-medium transition-all duration-200
                    {{ $isActive
                        ? 'bg-gradient-to-r from-[#4F46E5] to-[#5B4DF8] text-white font-semibold shadow-lg shadow-purple-600/20'
                        : 'text-[#94A3B8] hover:bg-white/[0.06] hover:text-white' }}">
                    <i class="{{ $item['icon'] }} text-lg w-[22px] text-center flex-shrink-0"></i>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="border-t border-[#1E293B] px-3.5 py-4">
            @php $adminUser = auth()->user(); @endphp
            <div class="flex items-center gap-3.5">
                <div class="w-[44px] h-[44px] rounded-full bg-[#5B4DF8] flex items-center justify-center text-white font-bold text-sm flex-shrink-0 overflow-hidden">
                    @if($adminUser?->avatar)
                        <img src="{{ $adminUser->avatar }}" alt="" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($adminUser?->name ?? 'A', 0, 1)) }}
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-white text-sm font-semibold truncate">{{ $adminUser?->name ?? 'Admin' }}</div>
                    <div class="text-[#64748B] text-xs truncate">{{ $adminUser?->email ?? '' }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 rounded-xl text-red-400 text-sm font-medium transition-all duration-200 hover:bg-red-500/10">
                    <i class="bi bi-box-arrow-right text-base"></i>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="main-wrapper">
        <nav class="navbar">
            <div class="navbar-left">
                <button class="btn-toggle-nav" id="sidebarToggle" aria-label="Toggle sidebar">
                    <i class="bi bi-list"></i>
                </button>
                <span class="navbar-page-title">@yield('page_title', 'Dashboard')</span>
            </div>

            <div class="navbar-search d-none d-md-block">
                <i class="bi bi-search search-icon"></i>
                <input type="text" placeholder="Cari sesuatu..." aria-label="Search">
            </div>

            <div class="navbar-right">
                <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
                    <i class="bi bi-sun-fill" id="themeIcon"></i>
                </button>
                <button class="btn-icon" aria-label="Notifications">
                    <i class="bi bi-bell-fill"></i>
                    <span class="notif-dot"></span>
                </button>
                <div class="dropdown">
                    <button class="user-dropdown-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="ud-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                        <span class="ud-name d-none d-lg-inline">{{ auth()->user()->name ?? 'Admin' }}</span>
                        <i class="bi bi-chevron-down ud-chevron"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-custom">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person-circle"></i> Profile</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-gear"></i> Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        (function () {
            const html = document.documentElement;
            const saved = localStorage.getItem('admin-theme');
            const prefers = window.matchMedia('(prefers-color-scheme: dark)').matches;
            html.setAttribute('data-theme', saved || (prefers ? 'dark' : 'light'));
        })();

        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const toggle = document.getElementById('sidebarToggle');
            const themeBtn = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            const html = document.documentElement;

            function applyTheme(theme) {
                html.setAttribute('data-theme', theme);
                localStorage.setItem('admin-theme', theme);
                if (themeIcon) themeIcon.className = theme === 'dark' ? 'bi bi-moon-fill' : 'bi bi-sun-fill';
            }

            if (themeBtn) {
                themeBtn.addEventListener('click', function () {
                    applyTheme(html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
                });
            }

            const initTheme = html.getAttribute('data-theme');
            if (themeIcon) themeIcon.className = initTheme === 'dark' ? 'bi bi-moon-fill' : 'bi bi-sun-fill';

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
            document.querySelectorAll('#adminSidebar nav a').forEach(function (link) {
                link.addEventListener('click', function () {
                    if (window.innerWidth <= 767.98) {
                        sidebar.classList.remove('show');
                        overlay.classList.remove('show');
                    }
                });
            });
        });

        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
            background: '#1E293B',
            color: '#fff',
            iconColor: '#10B981',
        });
        @endif

        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
            background: '#1E293B',
            color: '#fff',
            iconColor: '#EF4444',
        });
        @endif
    </script>
    @stack('scripts')
</body>
</html>
