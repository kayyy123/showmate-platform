@extends('admin.layouts.master')

@section('title', 'Detail Penarikan')
@section('page_title', 'Detail Penarikan')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <div class="breadcrumb-custom">
            <a href="{{ route('admin.withdrawals.index') }}">Penarikan</a>
            <span class="sep">/</span>
            <span>Detail #{{ $withdrawal->id }}</span>
        </div>
        <h1>Detail Penarikan #{{ $withdrawal->id }}</h1>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="card-header-custom">
                <h5>Informasi Penarikan</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Merchant</small>
                        <span class="fw-semibold">{{ $withdrawal->user?->name ?? 'N/A' }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Email Merchant</small>
                        <span>{{ $withdrawal->user?->email ?? 'N/A' }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Jumlah Penarikan</small>
                        <span style="font-size:1.25rem;font-weight:700;color:var(--text-heading);">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Status</small>
                        <span class="badge-custom mt-1
                            @if($withdrawal->status === 'approved') badge-green
                            @elseif($withdrawal->status === 'rejected') badge-red
                            @else badge-yellow @endif">
                            {{ ucfirst($withdrawal->status) }}
                        </span>
                    </div>
                    <div class="col-12"><hr style="border-color:var(--border);"></div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Bank</small>
                        <span class="fw-semibold">{{ $withdrawal->bank_name }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">No. Rekening</small>
                        <span>{{ $withdrawal->bank_account }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Atas Nama</small>
                        <span>{{ $withdrawal->bank_holder }}</span>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">Catatan Admin</small>
                        <span>{{ $withdrawal->notes ?? '-' }}</span>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">Diajukan</small>
                        <span>{{ $withdrawal->created_at->format('d F Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        @if($withdrawal->status === 'pending')
        <div class="card-custom">
            <div class="card-header-custom">
                <h5>Tindakan</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.withdrawals.approve', $withdrawal->id) }}" method="POST" class="mb-3">
                    @csrf
                    <button type="submit" class="btn-custom-primary w-100 mb-2" onclick="return confirm('Setujui penarikan ini?')">
                        <i class="bi bi-check-lg"></i> Setujui
                    </button>
                </form>
                <form action="{{ route('admin.withdrawals.reject', $withdrawal->id) }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <textarea name="notes" class="form-custom" rows="2" placeholder="Alasan penolakan..."></textarea>
                    </div>
                    <button type="submit" class="btn-custom-danger w-100" onclick="return confirm('Tolak penarikan ini?')">
                        <i class="bi bi-x-lg"></i> Tolak
                    </button>
                </form>
            </div>
        </div>
        @else
        <div class="card-custom">
            <div class="card-header-custom">
                <h5>Informasi Status</h5>
            </div>
            <div class="card-body p-4 text-center">
                @if($withdrawal->status === 'approved')
                    <div style="font-size:3rem;color:var(--badge-green);"><i class="bi bi-check-circle-fill"></i></div>
                    <p style="color:var(--text-secondary);margin-top:8px;">Penarikan telah disetujui</p>
                @else
                    <div style="font-size:3rem;color:#EF4444;"><i class="bi bi-x-circle-fill"></i></div>
                    <p style="color:var(--text-secondary);margin-top:8px;">Penarikan ditolak</p>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
