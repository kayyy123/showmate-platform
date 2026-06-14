@php
    $activeTab = $activeTab ?? 'manage';
    $user = auth()->user();
    $initial = strtoupper(substr($user?->name ?? 'U', 0, 1));
    $plan = $user?->plan ?? 'free';
@endphp
<aside class="sidebar" id="merchantSidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="bi bi-shop-window"></i>
        </div>
        <div class="brand-text">
            <span class="brand-name">ShowMate</span>
            <span class="brand-subtitle">Merchant Panel</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('merchant.manage') }}"
           class="sidebar-link {{ $activeTab === 'manage' ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill sidebar-link-icon"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('merchant.catalog') }}"
           class="sidebar-link {{ $activeTab === 'catalog' ? 'active' : '' }}">
            <i class="bi bi-link-45deg sidebar-link-icon"></i>
            <span>Katalog Saya</span>
        </a>
        <a href="{{ route('links.index') }}"
           class="sidebar-link {{ $activeTab === 'links' ? 'active' : '' }}">
            <i class="bi bi-link-45deg sidebar-link-icon"></i>
            <span>Tautan Saya</span>
        </a>
        <a href="{{ route('merchant.stats') }}"
           class="sidebar-link {{ $activeTab === 'stats' ? 'active' : '' }}">
            <i class="bi bi-bar-chart-fill sidebar-link-icon"></i>
            <span>Statistik</span>
        </a>
        <a href="{{ route('store-profile.edit') }}"
           class="sidebar-link {{ $activeTab === 'profile' ? 'active' : '' }}">
            <i class="bi bi-store sidebar-link-icon"></i>
            <span>Profil Toko</span>
        </a>
        <a href="{{ route('inclusive-program.index') }}"
           class="sidebar-link {{ $activeTab === 'inclusive' ? 'active' : '' }}">
            <i class="bi bi-people-fill sidebar-link-icon"></i>
            <span>Program Inklusif</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="profile-card">
            <div class="profile-avatar">
                @if($user?->avatar)
                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="profile-avatar-img">
                @elseif($user?->store_logo)
                    <img src="/storage/{{ $user->store_logo }}" alt="{{ $user->name }}" class="profile-avatar-img">
                @else
                    <span class="profile-avatar-text">{{ $initial }}</span>
                @endif
            </div>
            <div class="profile-info">
                <div class="profile-name">{{ $user?->name ?? 'User' }}</div>
                <div class="profile-email">{{ $user?->email ?? '' }}</div>
                @if($plan === 'pro')
                    <span class="profile-badge">PRO</span>
                @endif
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="logout-form">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="bi bi-box-arrow-right"></i>
                Logout
            </button>
        </form>
    </div>
</aside>
