@extends('admin.layouts.master')

@section('title', 'Detail Pesanan')
@section('page_title', 'Detail Pesanan')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <div class="breadcrumb-custom">
            <a href="{{ route('admin.orders.index') }}">Pesanan</a>
            <span class="sep">/</span>
            <span>Detail #{{ $order->id }}</span>
        </div>
        <h1>Detail Pesanan #{{ $order->id }}</h1>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="card-header-custom">
                <h5>Informasi Pesanan</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Pembeli</small>
                        <span class="fw-semibold">{{ $order->buyer_name }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Email</small>
                        <span>{{ $order->buyer_email }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Telepon</small>
                        <span>{{ $order->buyer_phone ?? '-' }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Jumlah</small>
                        <span>{{ $order->quantity }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Total Harga</small>
                        <span style="font-size:1.25rem;font-weight:700;color:var(--text-heading);">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Status</small>
                        <span class="badge-custom mt-1
                            @if($order->status === 'completed') badge-green
                            @elseif($order->status === 'cancelled') badge-red
                            @else badge-yellow @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="col-md-12">
                        <small class="text-muted d-block">Catatan</small>
                        <span>{{ $order->notes ?? '-' }}</span>
                    </div>
                    <div class="col-md-12">
                        <small class="text-muted d-block">Dibuat</small>
                        <span>{{ $order->created_at->format('d F Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card-custom">
            <div class="card-header-custom">
                <h5>Ubah Status</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <select name="status" class="form-custom">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-custom-primary w-100"><i class="bi bi-check-lg"></i> Update Status</button>
                </form>
            </div>
        </div>

        <div class="card-custom mt-4">
            <div class="card-header-custom">
                <h5>Produk</h5>
            </div>
            <div class="card-body p-4">
                <small class="text-muted d-block">Nama Produk</small>
                <span class="fw-semibold">{{ $order->product?->name ?? 'N/A' }}</span>
                <hr style="border-color:var(--border);margin:12px 0;">
                <small class="text-muted d-block">Merchant</small>
                <span>{{ $order->user?->name ?? 'N/A' }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
