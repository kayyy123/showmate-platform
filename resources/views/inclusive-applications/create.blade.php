@extends('layouts.merchant', ['activeTab' => 'inclusive'])

@section('title', 'Ajukan Program Inklusif')
@section('breadcrumb', 'Pengajuan Baru')

@section('content')
<div class="page-title-section">
    <h1>Ajukan Program Inklusif</h1>
    <p>Lengkapi data berikut untuk mengikuti program inklusif</p>
</div>

<div class="card-dashboard card">
    <div class="card-body">
        <form action="{{ route('inclusive-applications.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="store_id">Pilih Toko</label>
                <select name="store_id" id="store_id" class="form-select" required>
                    <option value="">-- Pilih Toko --</option>
                    @foreach ($stores as $store)
                        <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                    @endforeach
                </select>
                @error('store_id') <div class="text-danger mt-1" style="font-size:0.8rem;">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="disability_type">Jenis Disabilitas</label>
                <select name="disability_type" id="disability_type" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    @foreach ($disabilityTypes as $val => $label)
                        <option value="{{ $val }}" {{ old('disability_type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('disability_type') <div class="text-danger mt-1" style="font-size:0.8rem;">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="identity_document">Upload KTP</label>
                <input type="file" name="identity_document" id="identity_document" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                <div style="font-size:0.75rem;color:var(--text-muted);margin-top:4px;">Format: JPG, JPEG, PNG, atau PDF. Maksimal 2MB.</div>
                @error('identity_document') <div class="text-danger mt-1" style="font-size:0.8rem;">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="support_document">Upload Surat Keterangan Disabilitas</label>
                <input type="file" name="support_document" id="support_document" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                <div style="font-size:0.75rem;color:var(--text-muted);margin-top:4px;">Format: JPG, JPEG, PNG, atau PDF. Maksimal 2MB.</div>
                @error('support_document') <div class="text-danger mt-1" style="font-size:0.8rem;">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="description">Deskripsi (Opsional)</label>
                <textarea name="description" id="description" class="form-control" rows="3" placeholder="Ceritakan kebutuhan atau kondisi Anda...">{{ old('description') }}</textarea>
                @error('description') <div class="text-danger mt-1" style="font-size:0.8rem;">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Kirim Pengajuan</button>
                <a href="{{ route('inclusive-applications.index') }}" class="btn btn-outline-light">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
