@extends('admin.layouts.master')

@section('title', 'Penarikan Dana')
@section('page_title', 'Penarikan Dana')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>Penarikan Dana</h1>
        <p>Kelola permintaan penarikan dana merchant</p>
    </div>
</div>

<div class="card-custom">
    <div class="card-header-custom">
        <div class="d-flex gap-2">
            <a href="{{ route('admin.withdrawals.index') }}" class="btn-custom-outline btn-custom-sm {{ !request('trashed') ? 'active' : '' }}">Semua</a>
            <a href="{{ route('admin.withdrawals.index', ['trashed' => 1]) }}" class="btn-custom-outline btn-custom-sm">
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
                        <th>Merchant</th>
                        <th>Jumlah</th>
                        <th>Bank</th>
                        <th>No. Rekening</th>
                        <th>Atas Nama</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($withdrawals as $w)
                    <tr>
                        <td>{{ $w->id }}</td>
                        <td><span class="fw-semibold">{{ $w->user?->name ?? 'N/A' }}</span></td>
                        <td style="font-weight:600;color:var(--text-bold);">Rp {{ number_format($w->amount, 0, ',', '.') }}</td>
                        <td>{{ $w->bank_name }}</td>
                        <td>{{ $w->bank_account }}</td>
                        <td>{{ $w->bank_holder }}</td>
                        <td>
                            <span class="badge-custom
                                @if($w->status === 'approved') badge-green
                                @elseif($w->status === 'rejected') badge-red
                                @else badge-yellow @endif">
                                {{ ucfirst($w->status) }}
                            </span>
                        </td>
                        <td class="text-muted-tbl">{{ $w->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.withdrawals.show', $w->id) }}" class="btn-custom-link" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($w->trashed())
                                <form method="POST" action="{{ route('admin.withdrawals.restore', $w->id) }}">
                                    @csrf
                                    <button type="submit" class="btn-custom-link" title="Pulihkan">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                </form>
                                @else
                                <form method="POST" action="{{ route('admin.withdrawals.destroy', $w->id) }}" onsubmit="return confirm('Hapus penarikan ini?')">
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
                    <tr><td colspan="9" class="text-center py-4 text-muted">Belum ada penarikan dana</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($withdrawals->hasPages())
    <div class="p-3 border-top border-[var(--table-border)]">
        {{ $withdrawals->links() }}
    </div>
    @endif
</div>
@endsection
