@extends('layouts.merchant', ['activeTab' => 'inclusive'])

@section('title', 'Detail Pengajuan')
@section('breadcrumb', 'Detail Pengajuan')

@section('content')
<div class="page-title-section">
    <h1>Detail Pengajuan #{{ $application->id }}</h1>
</div>

<div class="card-dashboard card">
    <div class="card-header-custom">
        <h5>Informasi Pengajuan</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label style="font-size:0.8rem;color:var(--text-muted);">Toko</label>
                <div class="fw-semibold" style="color:var(--text-primary);">{{ $application->store?->name }}</div>
            </div>
            <div class="col-md-6">
                <label style="font-size:0.8rem;color:var(--text-muted);">Jenis Disabilitas</label>
                <div class="fw-semibold" style="color:var(--text-primary);">{{ ucfirst($application->disability_type) }}</div>
            </div>
            <div class="col-12">
                <label style="font-size:0.8rem;color:var(--text-muted);">Deskripsi</label>
                <div style="color:var(--text-secondary);">{{ $application->description ?? '-' }}</div>
            </div>
            <div class="col-12">
                <hr style="border-color:var(--border);">
            </div>
            <div class="col-md-6">
                <label style="font-size:0.8rem;color:var(--text-muted);">KTP</label>
                <div class="mt-1">
                    <a href="{{ asset('storage/' . $application->identity_document) }}" target="_blank" class="btn btn-sm btn-outline-light">
                        <i class="bi bi-eye"></i> Lihat Dokumen
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <label style="font-size:0.8rem;color:var(--text-muted);">Surat Keterangan Disabilitas</label>
                <div class="mt-1">
                    <a href="{{ asset('storage/' . $application->support_document) }}" target="_blank" class="btn btn-sm btn-outline-light">
                        <i class="bi bi-eye"></i> Lihat Dokumen
                    </a>
                </div>
            </div>
            <div class="col-12">
                <hr style="border-color:var(--border);">
            </div>
            <div class="col-md-4">
                <label style="font-size:0.8rem;color:var(--text-muted);">Status</label>
                <div class="mt-1">
                    <span class="badge-status
                        @if($application->status === 'approved') bg-success
                        @elseif($application->status === 'rejected') bg-danger
                        @else bg-warning text-dark @endif">
                        {{ ucfirst($application->status) }}
                    </span>
                </div>
            </div>
            <div class="col-md-4">
                <label style="font-size:0.8rem;color:var(--text-muted);">Diajukan</label>
                <div style="color:var(--text-secondary);">{{ $application->created_at->format('d F Y H:i') }}</div>
            </div>
            @if($application->reviewed_at)
            <div class="col-md-4">
                <label style="font-size:0.8rem;color:var(--text-muted);">Direview</label>
                <div style="color:var(--text-secondary);">{{ $application->reviewed_at->format('d F Y H:i') }}</div>
            </div>
            @endif
            @if($application->admin_note)
            <div class="col-12">
                <label style="font-size:0.8rem;color:var(--text-muted);">Catatan Admin</label>
                <div style="color:var(--text-secondary);">{{ $application->admin_note }}</div>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('inclusive-applications.index') }}" class="btn btn-outline-light">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>
@endsection
