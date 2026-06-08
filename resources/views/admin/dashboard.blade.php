@extends('admin.layouts.master')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="page-header">
    <h1>Dashboard</h1>
    <p>Selamat datang di panel admin ShowMate</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card card-stat">
            <div class="card-body">
                <div class="stat-info">
                    <div class="stat-label">Total User</div>
                    <div class="stat-value">{{ number_format($stats['totalUsers']) }}</div>
                </div>
                <div class="stat-icon-wrap icon-blue">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card card-stat">
            <div class="card-body">
                <div class="stat-info">
                    <div class="stat-label">Total Produk</div>
                    <div class="stat-value">{{ number_format($stats['totalProducts']) }}</div>
                </div>
                <div class="stat-icon-wrap icon-purple">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card card-stat">
            <div class="card-body">
                <div class="stat-info">
                    <div class="stat-label">Total Link</div>
                    <div class="stat-value">{{ number_format($stats['totalLinks']) }}</div>
                </div>
                <div class="stat-icon-wrap icon-green">
                    <i class="bi bi-link-45deg"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card card-stat">
            <div class="card-body">
                <div class="stat-info">
                    <div class="stat-label">Total Order</div>
                    <div class="stat-value">{{ number_format($stats['totalOrders']) }}</div>
                </div>
                <div class="stat-icon-wrap icon-amber">
                    <i class="bi bi-cart-check-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card card-stat">
            <div class="card-body">
                <div class="stat-info">
                    <div class="stat-label">Total Visitor</div>
                    <div class="stat-value">{{ number_format($stats['totalVisitors']) }}</div>
                </div>
                <div class="stat-icon-wrap icon-pink">
                    <i class="bi bi-eye-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card card-stat">
            <div class="card-body">
                <div class="stat-info">
                    <div class="stat-label">Produk Aktif</div>
                    <div class="stat-value">{{ number_format($stats['activeProducts']) }}</div>
                </div>
                <div class="stat-icon-wrap icon-emerald">
                    <i class="bi bi-toggle-on"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card card-stat">
            <div class="card-body">
                <div class="stat-info">
                    <div class="stat-label">Link Aktif</div>
                    <div class="stat-value">{{ number_format($stats['activeLinks']) }}</div>
                </div>
                <div class="stat-icon-wrap icon-cyan">
                    <i class="bi bi-toggle-on"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card card-stat">
            <div class="card-body">
                <div class="stat-info">
                    <div class="stat-label">Pending Order</div>
                    <div class="stat-value">{{ number_format($stats['pendingOrders']) }}</div>
                </div>
                <div class="stat-icon-wrap icon-orange">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="table-card">
            <div class="card-header">
                <h5><i class="bi bi-person-plus me-2" style="color: #4F46E5;"></i>User Terbaru</h5>
                <a href="{{ route('admin.users') }}" class="btn-link-custom">
                    Lihat Semua <i class="bi bi-chevron-right ms-1" style="font-size: 0.7rem;"></i>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Bergabung</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentUsers ?? [] as $user)
                        <tr>
                            <td><span class="fw-semibold">{{ $user['name'] }}</span></td>
                            <td>{{ $user['email'] }}</td>
                            <td class="text-muted">{{ $user['joined'] }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">Belum ada user</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="table-card">
            <div class="card-header">
                <h5><i class="bi bi-link-45deg me-2" style="color: #4F46E5;"></i>Link Terbaru</h5>
                <a href="{{ route('admin.links') }}" class="btn-link-custom">
                    Lihat Semua <i class="bi bi-chevron-right ms-1" style="font-size: 0.7rem;"></i>
                </a>
            </div>
            <div class="table-responsive">
                @php
                    $recentLinks = \App\Models\Link::with('user:id,name')
                        ->latest()
                        ->take(5)
                        ->get();
                @endphp
                <table class="table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>URL</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentLinks ?? [] as $link)
                        <tr>
                            <td><span class="fw-semibold">{{ $link->title }}</span></td>
                            <td class="text-muted" style="max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $link->url }}
                            </td>
                            <td>{{ $link->user?->name ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">Belum ada link</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
