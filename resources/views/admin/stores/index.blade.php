@extends('admin.layouts.master')

@section('title', 'Toko')
@section('page_title', 'Toko')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>Daftar Toko</h1>
        <p>Kelola semua toko merchant</p>
    </div>
    <div class="page-header-right">
        <a href="{{ route('admin.stores.create') }}" class="btn-custom-primary">
            <i class="bi bi-plus-lg"></i> Tambah Toko
        </a>
    </div>
</div>

<div class="card-custom">
    <div class="card-header-custom">
        <form action="{{ route('admin.stores.index') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-custom" style="width:280px;" placeholder="Cari toko..." value="{{ request('search') }}">
            <button type="submit" class="btn-custom-primary btn-custom-sm"><i class="bi bi-search"></i></button>
        </form>
    </div>
    <div class="card-body-custom">
        <div class="table-responsive">
            <table class="table-modern w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Logo</th>
                        <th>Nama Toko</th>
                        <th>Pemilik</th>
                        <th>WhatsApp</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stores as $store)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($store->logo)
                                <img src="{{ asset('storage/' . $store->logo) }}" alt="" style="width:36px;height:36px;border-radius:8px;object-fit:cover;">
                            @else
                                <div style="width:36px;height:36px;border-radius:8px;background:#2D2D2D;display:flex;align-items:center;justify-content:center;color:#64748B;font-size:0.7rem;">
                                    <i class="bi bi-shop"></i>
                                </div>
                            @endif
                        </td>
                        <td><span class="fw-semibold">{{ $store->name }}</span></td>
                        <td>{{ $store->user?->name ?? 'N/A' }}</td>
                        <td>{{ $store->whatsapp ?? '-' }}</td>
                        <td>
                            <span class="badge-custom {{ $store->is_active ? 'badge-green' : 'badge-red' }}">
                                {{ $store->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="text-muted-tbl">{{ $store->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.stores.edit', $store->id) }}" class="btn-custom-link" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.stores.destroy', $store->id) }}" onsubmit="return confirm('Hapus toko ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-custom-link text-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada toko</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($stores->hasPages())
    <div class="p-3 border-top border-[var(--table-border)]">
        {{ $stores->links() }}
    </div>
    @endif
</div>
@endsection
