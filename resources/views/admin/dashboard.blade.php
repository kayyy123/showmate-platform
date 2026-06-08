@extends('admin.layouts.master')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@push('styles')
<style>
    .stat-icon-users { background-color: rgba(59, 130, 246, 0.15); color: #3B82F6; }
    .stat-icon-products { background-color: rgba(139, 92, 246, 0.15); color: #8B5CF6; }
    .stat-icon-links { background-color: rgba(16, 185, 129, 0.15); color: #10B981; }
    .stat-icon-orders { background-color: rgba(245, 158, 11, 0.15); color: #F59E0B; }
    .stat-icon-visitors { background-color: rgba(236, 72, 153, 0.15); color: #EC4899; }
    .stat-icon-active-products { background-color: rgba(34, 197, 94, 0.15); color: #22C55E; }
    .stat-icon-active-links { background-color: rgba(6, 182, 212, 0.15); color: #06B6D4; }
    .stat-icon-pending { background-color: rgba(239, 68, 68, 0.15); color: #EF4444; }
</style>
@endpush

@section('content')
<div class="page-title-section">
    <h1>Dashboard</h1>
    <p>Selamat datang kembali, {{ auth()->user()->name }}! Berikut ringkasan platform.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card-dashboard card">
            <div class="card-body d-flex align-items-start gap-3">
                <div class="stat-icon stat-icon-users flex-shrink-0">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="stat-label">Total User</div>
                    <div class="stat-value">{{ number_format($stats['totalUsers']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-dashboard card">
            <div class="card-body d-flex align-items-start gap-3">
                <div class="stat-icon stat-icon-products flex-shrink-0">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
                <div>
                    <div class="stat-label">Total Produk</div>
                    <div class="stat-value">{{ number_format($stats['totalProducts']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-dashboard card">
            <div class="card-body d-flex align-items-start gap-3">
                <div class="stat-icon stat-icon-links flex-shrink-0">
                    <i class="bi bi-link-45deg"></i>
                </div>
                <div>
                    <div class="stat-label">Total Link</div>
                    <div class="stat-value">{{ number_format($stats['totalLinks']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-dashboard card">
            <div class="card-body d-flex align-items-start gap-3">
                <div class="stat-icon stat-icon-orders flex-shrink-0">
                    <i class="bi bi-cart-check-fill"></i>
                </div>
                <div>
                    <div class="stat-label">Total Order</div>
                    <div class="stat-value">{{ number_format($stats['totalOrders']) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card-dashboard card">
            <div class="card-body d-flex align-items-start gap-3">
                <div class="stat-icon stat-icon-visitors flex-shrink-0">
                    <i class="bi bi-eye-fill"></i>
                </div>
                <div>
                    <div class="stat-label">Total Visitor</div>
                    <div class="stat-value">{{ number_format($stats['totalVisitors']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-dashboard card">
            <div class="card-body d-flex align-items-start gap-3">
                <div class="stat-icon stat-icon-active-products flex-shrink-0">
                    <i class="bi bi-toggle-on"></i>
                </div>
                <div>
                    <div class="stat-label">Produk Aktif</div>
                    <div class="stat-value">{{ number_format($stats['activeProducts']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-dashboard card">
            <div class="card-body d-flex align-items-start gap-3">
                <div class="stat-icon stat-icon-active-links flex-shrink-0">
                    <i class="bi bi-toggle-on"></i>
                </div>
                <div>
                    <div class="stat-label">Link Aktif</div>
                    <div class="stat-value">{{ number_format($stats['activeLinks']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-dashboard card">
            <div class="card-body d-flex align-items-start gap-3">
                <div class="stat-icon stat-icon-pending flex-shrink-0">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div>
                    <div class="stat-label">Pending Order</div>
                    <div class="stat-value">{{ number_format($stats['pendingOrders']) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card-dashboard card">
            <div class="card-header-custom">
                <h5><i class="bi bi-graph-up me-2" style="color: var(--accent);"></i>Visitor 30 Hari Terakhir</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="visitorChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-dashboard card">
            <div class="card-header-custom">
                <h5><i class="bi bi-cursor me-2" style="color: var(--accent);"></i>Klik Link 30 Hari Terakhir</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="linkChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card-dashboard card">
            <div class="card-header-custom">
                <h5><i class="bi bi-person-plus me-2" style="color: var(--accent);"></i>Recent User</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dashboard">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Produk</th>
                                <th>Link</th>
                                <th>Bergabung</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentUsers ?? [] as $user)
                            <tr>
                                <td><span class="fw-semibold">{{ $user['name'] }}</span></td>
                                <td>{{ $user['email'] }}</td>
                                <td>{{ $user['total_products'] }}</td>
                                <td>{{ $user['total_links'] }}</td>
                                <td>{{ $user['joined'] }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada user</td>
                            </tr>
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
                <h5><i class="bi bi-box me-2" style="color: var(--accent);"></i>Recent Product</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dashboard">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Pemilik</th>
                                <th>Harga</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentProducts ?? [] as $product)
                            <tr>
                                <td><span class="fw-semibold">{{ $product['name'] }}</span></td>
                                <td>{{ $product['owner'] }}</td>
                                <td>Rp {{ number_format($product['price'], 0, ',', '.') }}</td>
                                <td>
                                    @if($product['is_active'])
                                        <span class="badge-status" style="background-color: rgba(34, 197, 94, 0.15); color: #22C55E;">Aktif</span>
                                    @else
                                        <span class="badge-status" style="background-color: rgba(239, 68, 68, 0.15); color: #EF4444;">Nonaktif</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Belum ada produk</td>
                            </tr>
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = true;
    const gridColor = 'rgba(42, 47, 58, 0.5)';
    const tickColor = '#9CA3AF';
    const accentColor = '#FFD600';

    function createChart(id, label, data, color) {
        const ctx = document.getElementById(id);
        if (!ctx) return;

        const labels = data.map(d => {
            const parts = d.date ? d.date.split('-') : [];
            return parts.length === 3 ? parts[2] + '/' + parts[1] : '';
        });
        const values = data.map(d => d.count || 0);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: values,
                    borderColor: color,
                    backgroundColor: color + '20',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    pointBackgroundColor: color,
                    pointBorderColor: isDark ? '#1A1F2E' : '#fff',
                    pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1A1F2E',
                        titleColor: '#fff',
                        bodyColor: '#9CA3AF',
                        borderColor: '#2A2F3A',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                    }
                },
                scales: {
                    x: {
                        grid: { color: gridColor, drawBorder: false },
                        ticks: { color: tickColor, font: { size: 11 } },
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor, drawBorder: false },
                        ticks: {
                            color: tickColor,
                            font: { size: 11 },
                            stepSize: 1,
                        }
                    }
                }
            }
        });
    }

    const visitorData = @json($visitorTrend ?? []);
    const linkData = @json($linkTrend ?? []);

    if (visitorData.length > 0) {
        createChart('visitorChart', 'Visitor', visitorData, '#3B82F6');
    } else {
        document.getElementById('visitorChart')?.parentNode?.parentNode?.parentNode?.parentNode?.parentNode?.remove
        const el = document.getElementById('visitorChart');
        if (el) {
            el.parentNode.innerHTML = '<div class="d-flex align-items-center justify-content-center h-100 text-muted" style="font-size:0.9rem;">Belum ada data visitor</div>';
        }
    }

    if (linkData.length > 0) {
        createChart('linkChart', 'Klik Link', linkData, '#10B981');
    } else {
        const el = document.getElementById('linkChart');
        if (el) {
            el.parentNode.innerHTML = '<div class="d-flex align-items-center justify-content-center h-100 text-muted" style="font-size:0.9rem;min-height:260px;">Belum ada data klik link</div>';
        }
    }
});
</script>
@endpush
