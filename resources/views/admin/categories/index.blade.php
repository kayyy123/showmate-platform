@extends('admin.layouts.master')

@section('title', 'Kategori')
@section('page_title', 'Kategori')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>Daftar Kategori</h1>
        <p>Kelola kategori produk</p>
    </div>
    <div class="page-header-right">
        <a href="{{ route('admin.categories.create') }}" class="btn-custom-primary">
            <i class="bi bi-plus-lg"></i> Tambah Kategori
        </a>
    </div>
</div>

<div class="card-custom">
    <div class="card-header-custom">
        <form action="{{ route('admin.categories.index') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-custom" style="width:280px;" placeholder="Cari kategori..." value="{{ request('search') }}">
            <button type="submit" class="btn-custom-primary btn-custom-sm"><i class="bi bi-search"></i></button>
        </form>
    </div>
    <div class="card-body-custom">
        <div class="table-responsive">
            <table class="table-modern w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Slug</th>
                        <th>Ikon</th>
                        <th>Jumlah Produk</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="fw-semibold">{{ $category->name }}</span></td>
                        <td class="text-muted-tbl">{{ $category->slug }}</td>
                        <td>{{ $category->icon ?? '-' }}</td>
                        <td><span class="badge-custom badge-blue">{{ $category->products_count ?? 0 }}</span></td>
                        <td>
                            <span class="badge-custom {{ $category->is_active ? 'badge-green' : 'badge-red' }}">
                                {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn-custom-link" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-custom-link text-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada kategori</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($categories->hasPages())
    <div class="p-3 border-top border-[var(--table-border)]">
        {{ $categories->links() }}
    </div>
    @endif
</div>
@endsection
