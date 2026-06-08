@extends('admin.layouts.master')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>Dashboard</h1>
        <p>Selamat datang di panel admin ShowMate</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon icon-primary"><i class="bi bi-people-fill"></i></div>
            </div>
            <div class="stat-label">Total User</div>
            <div class="stat-value">{{ $stats['total_users'] }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon icon-purple"><i class="bi bi-shop"></i></div>
            </div>
            <div class="stat-label">Total Store</div>
            <div class="stat-value">{{ $stats['total_stores'] }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon icon-green"><i class="bi bi-box-seam-fill"></i></div>
            </div>
            <div class="stat-label">Total Produk</div>
            <div class="stat-value">{{ $stats['total_products'] }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon icon-amber"><i class="bi bi-cart-check-fill"></i></div>
            </div>
            <div class="stat-label">Total Pesanan</div>
            <div class="stat-value">{{ $stats['total_orders'] }}</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-4 col-md-6">
        <div class="stat-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon icon-cyan"><i class="bi bi-gem"></i></div>
            </div>
            <div class="stat-label">Store Pro</div>
            <div class="stat-value">{{ $stats['store_pro'] }}</div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="stat-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon icon-pink"><i class="bi bi-people-fill"></i></div>
            </div>
            <div class="stat-label">Inclusive Sellers</div>
            <div class="stat-value">{{ $stats['inclusive_sellers'] }}</div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="stat-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon icon-orange"><i class="bi bi-clock"></i></div>
            </div>
            <div class="stat-label">Pending Applications</div>
            <div class="stat-value">{{ $stats['pending_applications'] }}</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="card-custom">
            <div class="card-header-custom">
                <h5><i class="bi bi-graph-up me-2"></i>Pesanan Bulanan</h5>
            </div>
            <div class="card-body p-4">
                <canvas id="ordersChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card-custom">
            <div class="card-header-custom">
                <h5><i class="bi bi-people-fill me-2"></i>Pertumbuhan Merchant</h5>
            </div>
            <div class="card-body p-4">
                <canvas id="merchantChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <div class="col-xl-6">
        <div class="card-custom">
            <div class="card-header-custom">
                <h5><i class="bi bi-clock-history me-2"></i>Aktivitas Terbaru</h5>
            </div>
            <div class="card-body-custom">
                <div class="table-responsive">
                    <table class="table-modern w-100">
                        <thead>
                            <tr>
                                <th>Merchant</th>
                                <th>Produk</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($activities['recentOrders'] as $order)
                            <tr>
                                <td><span class="fw-semibold">{{ $order->user?->name ?? 'N/A' }}</span></td>
                                <td>{{ $order->product?->name ?? 'N/A' }}</td>
                                <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge-custom
                                        @if($order->status === 'completed') badge-green
                                        @elseif($order->status === 'cancelled') badge-red
                                        @else badge-yellow @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada pesanan</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card-custom">
            <div class="card-header-custom">
                <h5><i class="bi bi-box-seam me-2"></i>Produk Terpopuler</h5>
            </div>
            <div class="card-body-custom">
                <div class="table-responsive">
                    <table class="table-modern w-100">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Pemilik</th>
                                <th>Harga</th>
                                <th>Pesanan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($topProducts as $product)
                            <tr>
                                <td><span class="fw-semibold">{{ $product['name'] }}</span></td>
                                <td>{{ $product['user']['name'] ?? 'N/A' }}</td>
                                <td>Rp {{ number_format($product['price'], 0, ',', '.') }}</td>
                                <td><span class="badge-custom badge-blue">{{ $product['checkouts_count'] }}x</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    new Chart(document.getElementById('ordersChart'), {
        type: 'line',
        data: {
            labels: @json($monthlyOrders['labels']),
            datasets: [
                {
                    label: 'Pesanan',
                    data: @json($monthlyOrders['orders']),
                    borderColor: '#5B4DF8',
                    backgroundColor: 'rgba(91,77,248,0.1)',
                    fill: true,
                    tension: 0.4,
                },
                {
                    label: 'Pendapatan (Rp)',
                    data: @json($monthlyOrders['revenue']),
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16,185,129,0.1)',
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y1',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { labels: { color: '#94A3B8', font: { family: 'Inter' } } } },
            scales: {
                x: { ticks: { color: '#94A3B8' }, grid: { color: '#2D2D2D' } },
                y: { ticks: { color: '#94A3B8' }, grid: { color: '#2D2D2D' } },
                y1: { position: 'right', ticks: { color: '#94A3B8' }, grid: { display: false } }
            }
        }
    });

    new Chart(document.getElementById('merchantChart'), {
        type: 'bar',
        data: {
            labels: @json($merchantGrowth['labels']),
            datasets: [{
                label: 'Merchant Baru',
                data: @json($merchantGrowth['data']),
                backgroundColor: 'rgba(91,77,248,0.7)',
                borderColor: '#5B4DF8',
                borderWidth: 1,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { labels: { color: '#94A3B8', font: { family: 'Inter' } } } },
            scales: {
                x: { ticks: { color: '#94A3B8' }, grid: { color: '#2D2D2D' } },
                y: { ticks: { color: '#94A3B8' }, grid: { color: '#2D2D2D' } }
            }
        }
    });
});
</script>
@endpush
