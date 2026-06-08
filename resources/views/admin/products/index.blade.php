@extends('admin.layouts.master')

@section('title', 'Produk')
@section('page_title', 'Produk')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>Daftar Produk</h1>
        <p>Kelola semua produk merchant</p>
    </div>
    <div class="page-header-right">
        <a href="{{ route('admin.products.create') }}" class="btn-custom-primary">
            <i class="bi bi-plus-lg"></i> Tambah Produk
        </a>
    </div>
</div>

<div class="card-custom">
    <div class="card-header-custom">
        <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-custom" style="width:280px;" placeholder="Cari produk..." value="{{ request('search') }}">
            <button type="submit" class="btn-custom-primary btn-custom-sm"><i class="bi bi-search"></i></button>
        </form>
    </div>
    <div class="card-body-custom">
        <div class="table-responsive">
            <table class="table-modern w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Pemilik</th>
                        <th>Toko</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="" style="width:36px;height:36px;border-radius:8px;object-fit:cover;">
                            @else
                                <div style="width:36px;height:36px;border-radius:8px;background:#2D2D2D;display:flex;align-items:center;justify-content:center;color:#64748B;font-size:0.7rem;">
                                    <i class="bi bi-box"></i>
                                </div>
                            @endif
                        </td>
                        <td><span class="fw-semibold">{{ $product->name }}</span></td>
                        <td>{{ $product->user?->name ?? 'N/A' }}</td>
                        <td>{{ $product->store?->name ?? '-' }}</td>
                        <td>{{ $product->category?->name ?? '-' }}</td>
                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge-custom {{ $product->is_active ? 'badge-green' : 'badge-red' }}">
                                {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-custom-link" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.products.toggle-visibility', $product->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn-custom-link" title="{{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="bi {{ $product->is_active ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-custom-link text-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-4 text-muted">Belum ada produk</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($products->hasPages())
    <div class="p-3 border-top border-[var(--table-border)]">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
