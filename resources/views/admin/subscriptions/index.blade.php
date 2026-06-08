@extends('admin.layouts.master')

@section('title', 'Langganan')
@section('page_title', 'Langganan')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>Paket Langganan</h1>
        <p>Kelola paket dan langganan merchant</p>
    </div>
    <div class="page-header-right">
        <a href="{{ route('admin.subscriptions.assign-form') }}" class="btn-custom-outline me-2">
            <i class="bi bi-person-plus"></i> Tetapkan Langganan
        </a>
        <a href="{{ route('admin.subscriptions.plans.create') }}" class="btn-custom-primary">
            <i class="bi bi-plus-lg"></i> Tambah Paket
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    @forelse ($plans as $plan)
    <div class="col-xl-4 col-md-6">
        <div class="card-custom h-100">
            <div class="card-body p-4 text-center">
                <div class="stat-icon icon-purple mx-auto mb-3" style="width:56px;height:56px;border-radius:16px;font-size:1.5rem;">
                    <i class="bi bi-gem"></i>
                </div>
                <h5 class="fw-bold mb-1" style="color:var(--text-heading);">{{ $plan->name }}</h5>
                <div class="text-muted mb-3" style="font-size:0.8rem;">{{ $plan->description }}</div>
                <div class="mb-3">
                    <span style="font-size:2rem;font-weight:800;color:var(--text-heading);">Rp{{ number_format($plan->price, 0, ',', '.') }}</span>
                    <span class="text-muted" style="font-size:0.85rem;">/{{ $plan->duration_days }}hr</span>
                </div>
                <div style="font-size:0.85rem;color:var(--text-secondary);">
                    <div class="mb-1"><i class="bi bi-box-seam me-2"></i>Max {{ $plan->max_products }} produk</div>
                    <div class="mb-1"><i class="bi bi-shop me-2"></i>Max {{ $plan->max_stores }} toko</div>
                    @if($plan->features)
                        @foreach (json_decode($plan->features, true) ?? [] as $feature)
                            <div class="mb-1"><i class="bi bi-check-circle text-success me-2"></i>{{ $feature }}</div>
                        @endforeach
                    @endif
                </div>
                <div class="mt-3">
                    <span class="badge-custom {{ $plan->is_active ? 'badge-green' : 'badge-red' }}">
                        {{ $plan->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                <div class="mt-3 d-flex justify-content-center gap-2">
                    <a href="{{ route('admin.subscriptions.plans.edit', $plan->id) }}" class="btn-custom-link">Edit</a>
                    <form method="POST" action="{{ route('admin.subscriptions.plans.destroy', $plan->id) }}" onsubmit="return confirm('Hapus paket ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-custom-link text-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5 text-muted">Belum ada paket langganan</div>
    @endforelse
</div>

<div class="card-custom mt-4">
    <div class="card-header-custom">
        <h5><i class="bi bi-people-fill me-2"></i>Langganan Merchant</h5>
    </div>
    <div class="card-body-custom">
        <div class="table-responsive">
            <table class="table-modern w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Merchant</th>
                        <th>Paket</th>
                        <th>Mulai</th>
                        <th>Berakhir</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($subscriptions as $sub)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="fw-semibold">{{ $sub->user?->name ?? 'N/A' }}</span></td>
                        <td>{{ $sub->plan?->name ?? 'N/A' }}</td>
                        <td>{{ $sub->starts_at->format('d M Y') }}</td>
                        <td>{{ $sub->ends_at ? $sub->ends_at->format('d M Y') : '-' }}</td>
                        <td>
                            <span class="badge-custom
                                @if($sub->status === 'active') badge-green
                                @elseif($sub->status === 'expired') badge-red
                                @else badge-yellow @endif">
                                {{ ucfirst($sub->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada langganan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($subscriptions->hasPages())
    <div class="p-3 border-top border-[var(--table-border)]">
        {{ $subscriptions->links() }}
    </div>
    @endif
</div>
@endsection
