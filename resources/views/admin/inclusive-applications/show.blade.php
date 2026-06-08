@extends('admin.layouts.master')

@section('title', 'Detail Pengajuan')
@section('page_title', 'Detail Pengajuan')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <div class="breadcrumb-custom">
            <a href="{{ route('admin.inclusive-applications.index') }}">Program Inklusif</a>
            <span class="sep">/</span>
            <span>Detail #{{ $application->id }}</span>
        </div>
        <h1>Detail Pengajuan #{{ $application->id }}</h1>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="card-header-custom">
                <h5>Informasi Pengajuan</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Merchant</small>
                        <span class="fw-semibold">{{ $application->store?->user?->name ?? 'N/A' }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Email Merchant</small>
                        <span>{{ $application->store?->user?->email ?? 'N/A' }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Toko</small>
                        <span>{{ $application->store?->name ?? 'N/A' }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Jenis Disabilitas</small>
                        <span class="fw-semibold">{{ ucfirst($application->disability_type) }}</span>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">Deskripsi</small>
                        <span>{{ $application->description ?? '-' }}</span>
                    </div>
                    <div class="col-12">
                        <hr style="border-color:var(--border);">
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">KTP</small>
                        <a href="{{ asset('storage/' . $application->identity_document) }}" target="_blank" class="btn-custom-outline btn-custom-sm mt-1">
                            <i class="bi bi-eye"></i> Lihat Dokumen
                        </a>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Surat Keterangan Disabilitas</small>
                        <a href="{{ asset('storage/' . $application->support_document) }}" target="_blank" class="btn-custom-outline btn-custom-sm mt-1">
                            <i class="bi bi-eye"></i> Lihat Dokumen
                        </a>
                    </div>
                    <div class="col-12">
                        <hr style="border-color:var(--border);">
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Status</small>
                        <span class="badge-custom mt-1
                            @if($application->status === 'approved') badge-green
                            @elseif($application->status === 'rejected') badge-red
                            @else badge-yellow @endif">
                            {{ ucfirst($application->status) }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Diajukan</small>
                        <span>{{ $application->created_at->format('d F Y H:i') }}</span>
                    </div>
                    @if($application->reviewed_at)
                    <div class="col-md-6">
                        <small class="text-muted d-block">Direview</small>
                        <span>{{ $application->reviewed_at->format('d F Y H:i') }}</span>
                    </div>
                    @endif
                    @if($application->admin_note)
                    <div class="col-12">
                        <small class="text-muted d-block">Catatan Admin</small>
                        <span>{{ $application->admin_note }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        @if($application->status === 'pending')
        <div class="card-custom">
            <div class="card-header-custom">
                <h5>Tindakan</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.inclusive-applications.approve', $application->id) }}" method="POST" class="mb-3">
                    @csrf
                    <div class="mb-3">
                        <textarea name="admin_note" class="form-custom" rows="2" placeholder="Catatan (opsional)..."></textarea>
                    </div>
                    <button type="submit" class="btn-custom-primary w-100 mb-2" onclick="return confirm('Setujui pengajuan ini?')">
                        <i class="bi bi-check-lg"></i> Setujui
                    </button>
                </form>
                <form action="{{ route('admin.inclusive-applications.reject', $application->id) }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <textarea name="admin_note" class="form-custom" rows="2" placeholder="Alasan penolakan..."></textarea>
                    </div>
                    <button type="submit" class="btn-custom-danger w-100" onclick="return confirm('Tolak pengajuan ini?')">
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
                @if($application->status === 'approved')
                    <div style="font-size:3rem;color:var(--badge-green);"><i class="bi bi-check-circle-fill"></i></div>
                    <p style="color:var(--text-secondary);margin-top:8px;">Pengajuan telah disetujui</p>
                @else
                    <div style="font-size:3rem;color:#EF4444;"><i class="bi bi-x-circle-fill"></i></div>
                    <p style="color:var(--text-secondary);margin-top:8px;">Pengajuan ditolak</p>
                @endif
                @if($application->admin_note)
                <hr style="border-color:var(--border);">
                <small class="text-muted d-block text-start">Catatan Admin</small>
                <p class="text-start mt-1 mb-0" style="color:var(--text-secondary);">{{ $application->admin_note }}</p>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
