@extends('layouts.merchant')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@push('styles')
<style>
    .stat-icon-users { background-color: rgba(59, 130, 246, 0.15); color: #3B82F6; }
    .stat-icon-products { background-color: rgba(139, 92, 246, 0.15); color: #8B5CF6; }
    .stat-icon-links { background-color: rgba(16, 185, 129, 0.15); color: #10B981; }
    .stat-icon-orders { background-color: rgba(245, 158, 11, 0.15); color: #F59E0B; }
    .stat-icon-visitors { background-color: rgba(236, 72, 153, 0.15); color: #EC4899; }
    .stat-icon-active { background-color: rgba(34, 197, 94, 0.15); color: #22C55E; }
    .stat-icon-pending { background-color: rgba(239, 68, 68, 0.15); color: #EF4444; }
</style>
@endpush

@section('content')
<div class="page-title-section">
    <h1>Dashboard</h1>
    <p>Selamat datang kembali, {{ auth()->user()->name }}!</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card-dashboard card">
            <div class="card-body d-flex align-items-start gap-3">
                <div class="stat-icon stat-icon-users flex-shrink-0"><i class="bi bi-box-seam-fill"></i></div>
                <div>
                    <div class="stat-label">Total Produk</div>
                    <div class="stat-value">{{ number_format($totalProducts ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-dashboard card">
            <div class="card-body d-flex align-items-start gap-3">
                <div class="stat-icon stat-icon-products flex-shrink-0"><i class="bi bi-link-45deg"></i></div>
                <div>
                    <div class="stat-label">Total Link</div>
                    <div class="stat-value">{{ number_format($totalLinks ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-dashboard card">
            <div class="card-body d-flex align-items-start gap-3">
                <div class="stat-icon stat-icon-visitors flex-shrink-0"><i class="bi bi-eye-fill"></i></div>
                <div>
                    <div class="stat-label">Total Visitor</div>
                    <div class="stat-value">{{ number_format($totalVisits ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-dashboard card">
            <div class="card-body d-flex align-items-start gap-3">
                <div class="stat-icon stat-icon-orders flex-shrink-0"><i class="bi bi-cart-check-fill"></i></div>
                <div>
                    <div class="stat-label">Total Order</div>
                    <div class="stat-value">{{ number_format($totalCheckouts ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card-dashboard card">
            <div class="card-body d-flex align-items-start gap-3">
                <div class="stat-icon stat-icon-active flex-shrink-0"><i class="bi bi-toggle-on"></i></div>
                <div>
                    <div class="stat-label">Produk Aktif</div>
                    <div class="stat-value">{{ number_format($activeProducts ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-dashboard card">
            <div class="card-body d-flex align-items-start gap-3">
                <div class="stat-icon stat-icon-active flex-shrink-0"><i class="bi bi-toggle-on"></i></div>
                <div>
                    <div class="stat-label">Link Aktif</div>
                    <div class="stat-value">{{ number_format($activeLinks ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-dashboard card">
            <div class="card-body d-flex align-items-start gap-3">
                <div class="stat-icon stat-icon-pending flex-shrink-0"><i class="bi bi-clock-history"></i></div>
                <div>
                    <div class="stat-label">Pending Order</div>
                    <div class="stat-value">{{ number_format($pendingOrders ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card-dashboard card">
            <div class="card-header-custom">
                <h5><i class="bi bi-box me-2" style="color: var(--accent);"></i>Produk Terbaru</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dashboard">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentProducts ?? [] as $product)
                            <tr>
                                <td><span class="fw-semibold">{{ $product['name'] ?? $product->name }}</span></td>
                                <td>Rp {{ number_format($product['price'] ?? $product->price, 0, ',', '.') }}</td>
                                <td>
                                    @if($product['is_active'] ?? $product->is_active)
                                        <span class="badge-status" style="background-color: rgba(34, 197, 94, 0.15); color: #22C55E;">Aktif</span>
                                    @else
                                        <span class="badge-status" style="background-color: rgba(239, 68, 68, 0.15); color: #EF4444;">Nonaktif</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">Belum ada produk</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-dashboard card">
            <div class="card-header-custom">
                <h5><i class="bi bi-cart me-2" style="color: var(--accent);"></i>Order Terbaru</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dashboard">
                        <thead>
                            <tr>
                                <th>Pembeli</th>
                                <th>Produk</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentCheckouts ?? [] as $checkout)
                            <tr>
                                <td><span class="fw-semibold">{{ $checkout['buyer_name'] ?? $checkout->buyer_name }}</span></td>
                                <td>{{ $checkout['product_name'] ?? ($checkout->product?->name ?? '-') }}</td>
                                <td>Rp {{ number_format($checkout['total_price'] ?? $checkout->total_price, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge-status"
                                        @switch($checkout['status'] ?? $checkout->status)
                                            @case('completed')
                                                style="background-color: rgba(34, 197, 94, 0.15); color: #22C55E;"
                                                @break
                                            @case('pending')
                                                style="background-color: rgba(245, 158, 11, 0.15); color: #F59E0B;"
                                                @break
                                            @case('cancelled')
                                                style="background-color: rgba(239, 68, 68, 0.15); color: #EF4444;"
                                                @break
                                            @default
                                                style="background-color: rgba(156, 163, 175, 0.15); color: #9CA3AF;"
                                        @endswitch
                                    >
                                        {{ ucfirst($checkout['status'] ?? $checkout->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">Belum ada order</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
