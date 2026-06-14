@extends('admin.layouts.master')

@section('title', 'Program Inklusif')
@section('page_title', 'Program Inklusif')

@php
$programTypeLabels = [
    'owned_by_disabled' => 'Dimiliki penyandang disabilitas',
    'employs_disabled' => 'Mempekerjakan penyandang disabilitas',
    'sells_inclusive_products' => 'Menjual produk ramah disabilitas',
    'accessibility_services' => 'Layanan aksesibilitas',
    'community_empowerment' => 'Komunitas pemberdayaan disabilitas',
    'other' => 'Lainnya',
];
@endphp

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>Pengajuan Program Inklusif</h1>
        <p>Kelola pengajuan program inklusif merchant</p>
    </div>
</div>

<div class="card-custom">
    <div class="card-header-custom">
        <div class="d-flex gap-2 flex-wrap">
            <form action="{{ route('admin.inclusive-program.index') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-custom" style="width:240px;" placeholder="Cari merchant/toko..." value="{{ request('search') }}">
                <button type="submit" class="btn-custom-primary btn-custom-sm"><i class="bi bi-search"></i></button>
            </form>
            <a href="{{ route('admin.inclusive-program.index') }}" class="btn-custom-outline btn-custom-sm {{ !request('status') ? 'active' : '' }}">Semua</a>
            <a href="{{ route('admin.inclusive-program.index', ['status' => 'pending']) }}" class="btn-custom-outline btn-custom-sm {{ request('status') === 'pending' ? 'active' : '' }}">Pending</a>
            <a href="{{ route('admin.inclusive-program.index', ['status' => 'approved']) }}" class="btn-custom-outline btn-custom-sm {{ request('status') === 'approved' ? 'active' : '' }}">Disetujui</a>
            <a href="{{ route('admin.inclusive-program.index', ['status' => 'rejected']) }}" class="btn-custom-outline btn-custom-sm {{ request('status') === 'rejected' ? 'active' : '' }}">Ditolak</a>
        </div>
    </div>
    <div class="card-body-custom">
        <div class="table-responsive">
            <table class="table-modern w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Merchant</th>
                        <th>Toko</th>
                        <th>Jenis Program</th>
                        <th>Status</th>
                        <th>Diajukan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($applications as $app)
                    <tr>
                        <td>{{ $app->id }}</td>
                        <td><span class="fw-semibold">{{ $app->user?->name ?? 'N/A' }}</span></td>
                        <td>{{ $app->user?->store_name ?? 'N/A' }}</td>
                        <td>
                            @php
                                $types = is_array($app->program_types) ? $app->program_types : json_decode($app->program_types, true) ?? [];
                                $labels = array_map(fn($t) => $programTypeLabels[$t] ?? $t, $types);
                            @endphp
                            {{ implode(', ', $labels) }}
                        </td>
                        <td>
                            <span class="badge-custom
                                @if($app->status === 'approved') badge-green
                                @elseif($app->status === 'rejected') badge-red
                                @else badge-yellow @endif">
                                {{ ucfirst($app->status) }}
                            </span>
                        </td>
                        <td class="text-muted-tbl">{{ $app->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.inclusive-program.show', $app->id) }}" class="btn-custom-link" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada pengajuan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($applications->hasPages())
    <div class="p-3 border-top border-[var(--table-border)]">
        {{ $applications->links() }}
    </div>
    @endif
</div>
@endsection
