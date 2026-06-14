@extends('admin.layouts.master')

@section('title', 'Detail Pengajuan')
@section('page_title', 'Detail Pengajuan')

@php
$programTypeLabels = [
    'owned_by_disabled' => 'Usaha dimiliki oleh penyandang disabilitas',
    'employs_disabled' => 'Mempekerjakan penyandang disabilitas',
    'sells_inclusive_products' => 'Menjual produk ramah disabilitas',
    'accessibility_services' => 'Menyediakan layanan aksesibilitas',
    'community_empowerment' => 'Komunitas/yayasan pemberdayaan disabilitas',
    'other' => 'Lainnya',
];
$rejectionReasons = [
    'Deskripsi program belum jelas',
    'Bukti pendukung tidak sesuai',
    'Informasi belum cukup',
    'Data tidak relevan dengan Program Inklusif',
];
@endphp

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <div class="breadcrumb-custom">
            <a href="{{ route('admin.inclusive-program.index') }}">Program Inklusif</a>
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
                        <span class="fw-semibold">{{ $application->user?->name ?? 'N/A' }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Email Merchant</small>
                        <span>{{ $application->user?->email ?? 'N/A' }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Toko</small>
                        <span>{{ $application->user?->store_name ?? 'N/A' }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Link Katalog</small>
                        @if($application->user?->store_slug)
                        <a href="{{ route('catalog.show', $application->user->store_slug) }}" target="_blank" class="btn-custom-outline btn-custom-sm mt-1">
                            <i class="bi bi-box-arrow-up-right"></i> Buka Katalog
                        </a>
                        @else
                        <span>-</span>
                        @endif
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">Jenis Program</small>
                        <ul class="mt-1 mb-0" style="color:var(--text-secondary);">
                            @php $types = is_array($application->program_types) ? $application->program_types : json_decode($application->program_types, true) ?? []; @endphp
                            @foreach ($types as $type)
                            <li>{{ $programTypeLabels[$type] ?? $type }}</li>
                            @endforeach
                        </ul>
                        @if($application->other_program_type)
                        <small class="text-muted d-block mt-2">Detail Lainnya:</small>
                        <span>{{ $application->other_program_type }}</span>
                        @endif
                    </div>
                    <div class="col-12">
                        <hr style="border-color:var(--border);">
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">Deskripsi Program</small>
                        <span>{{ $application->description ?? '-' }}</span>
                    </div>
                    <div class="col-12">
                        <hr style="border-color:var(--border);">
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Bukti Pendukung</small>
                        @if($application->supporting_file)
                        <a href="{{ asset('storage/' . $application->supporting_file) }}" target="_blank" class="btn-custom-outline btn-custom-sm mt-1">
                            <i class="bi bi-eye"></i> Lihat File
                        </a>
                        @else
                        <span class="text-muted">Tidak ada</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Link Pendukung</small>
                        @if($application->supporting_link)
                        <a href="{{ $application->supporting_link }}" target="_blank" class="btn-custom-outline btn-custom-sm mt-1">
                            <i class="bi bi-box-arrow-up-right"></i> Buka Link
                        </a>
                        @else
                        <span class="text-muted">Tidak ada</span>
                        @endif
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
                        <small class="text-muted d-block">Direview oleh</small>
                        <span>{{ $application->reviewer?->name ?? 'N/A' }} — {{ $application->reviewed_at->format('d F Y H:i') }}</span>
                    </div>
                    @endif
                    @if($application->rejection_reason)
                    <div class="col-12">
                        <small class="text-muted d-block">Alasan Penolakan</small>
                        <div class="p-3 rounded" style="background:#FEF2F2;color:#DC2626;border:1px solid #FECACA;">
                            {{ $application->rejection_reason }}
                        </div>
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
                <form action="{{ route('admin.inclusive-program.approve', $application->id) }}" method="POST" class="mb-3">
                    @csrf
                    <input type="hidden" name="action" value="approve">
                    <button type="submit" class="btn-custom-primary w-100 mb-2" onclick="return confirm('Setujui pengajuan ini?')">
                        <i class="bi bi-check-lg"></i> Setujui
                    </button>
                </form>
                <form action="{{ route('admin.inclusive-program.reject', $application->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="reject">
                    <div class="mb-2">
                        <label for="rejection_reason" class="form-label text-muted small">Alasan Penolakan <span class="text-danger">*</span></label>
                        <select id="rejection_reason" class="form-custom mb-2">
                            <option value="">Pilih alasan...</option>
                            @foreach ($rejectionReasons as $reason)
                            <option value="{{ $reason }}">{{ $reason }}</option>
                            @endforeach
                            <option value="other">Lainnya...</option>
                        </select>
                        <textarea name="rejection_reason" id="rejection_reason_text" class="form-custom" rows="2" placeholder="Atau tulis alasan penolakan..."></textarea>
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
                    <div class="mt-2 p-2 rounded" style="background: #ECFDF5; border: 1px solid #A7F3D0;">
                        <span style="font-size:1.2rem;">♿</span>
                        <span class="fw-semibold" style="color:#059669;">UMKM Inklusif</span>
                    </div>
                @else
                    <div style="font-size:3rem;color:#EF4444;"><i class="bi bi-x-circle-fill"></i></div>
                    <p style="color:var(--text-secondary);margin-top:8px;">Pengajuan ditolak</p>
                @endif
                @if($application->rejection_reason)
                <hr style="border-color:var(--border);">
                <small class="text-muted d-block text-start">Alasan Penolakan</small>
                <p class="text-start mt-1 mb-0" style="color:var(--text-secondary);">{{ $application->rejection_reason }}</p>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('rejection_reason')?.addEventListener('change', function() {
    const textarea = document.getElementById('rejection_reason_text');
    if (this.value === 'other') {
        textarea.value = '';
        textarea.placeholder = 'Tulis alasan penolakan...';
    } else {
        textarea.value = this.value;
    }
});
</script>
@endpush
