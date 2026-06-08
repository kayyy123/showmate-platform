@extends('admin.layouts.master')

@section('title', 'Pesanan')
@section('page_title', 'Pesanan')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>Daftar Pesanan</h1>
        <p>Kelola semua pesanan merchant</p>
    </div>
</div>

<div class="card-custom">
    <div class="card-header-custom">
        <div class="d-flex gap-2">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-custom" style="width:280px;" placeholder="Cari pesanan..." value="{{ request('search') }}">
                <button type="submit" class="btn-custom-primary btn-custom-sm"><i class="bi bi-search"></i></button>
            </form>
            <a href="{{ route('admin.orders.index') }}" class="btn-custom-outline btn-custom-sm">Semua</a>
            <a href="{{ route('admin.orders.index', ['trashed' => 1]) }}" class="btn-custom-outline btn-custom-sm">
                <i class="bi bi-trash"></i> Sampah
            </a>
        </div>
    </div>
    <div class="card-body-custom">
        <div class="table-responsive">
            <table class="table-modern w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pembeli</th>
                        <th>Produk</th>
                        <th>Merchant</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>
                            <span class="fw-semibold">{{ $order->buyer_name }}</span>
                            <br><small class="text-muted-tbl">{{ $order->buyer_email }}</small>
                        </td>
                        <td>{{ $order->product?->name ?? 'N/A' }}</td>
                        <td>{{ $order->user?->name ?? 'N/A' }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge-custom
                                @if($order->status === 'completed') badge-green
                                @elseif($order->status === 'cancelled') badge-red
                                @else badge-yellow @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="text-muted-tbl">{{ $order->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-custom-link" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($order->trashed())
                                <form method="POST" action="{{ route('admin.orders.restore', $order->id) }}">
                                    @csrf
                                    <button type="submit" class="btn-custom-link" title="Pulihkan">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                </form>
                                @else
                                <form method="POST" action="{{ route('admin.orders.destroy', $order->id) }}" onsubmit="return confirm('Hapus pesanan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-custom-link text-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-4 text-muted">Belum ada pesanan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($orders->hasPages())
    <div class="p-3 border-top border-[var(--table-border)]">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
