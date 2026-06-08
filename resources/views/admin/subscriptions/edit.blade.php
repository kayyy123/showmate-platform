@extends('admin.layouts.master')

@section('title', 'Edit Paket Langganan')
@section('page_title', 'Edit Paket')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <div class="breadcrumb-custom">
            <a href="{{ route('admin.subscriptions.plans.index') }}">Langganan</a>
            <span class="sep">/</span>
            <span>Edit Paket</span>
        </div>
        <h1>Edit Paket Langganan</h1>
    </div>
</div>

<div class="card-custom">
    <div class="card-body p-4">
        <form action="{{ route('admin.subscriptions.plans.update', $plan->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-custom-label">Nama Paket</label>
                    <input type="text" name="name" class="form-custom @error('name') is-invalid @enderror" value="{{ old('name', $plan->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-custom-label">Slug</label>
                    <input type="text" name="slug" class="form-custom @error('slug') is-invalid @enderror" value="{{ old('slug', $plan->slug) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-custom-label">Harga (Rp)</label>
                    <input type="number" name="price" class="form-custom @error('price') is-invalid @enderror" value="{{ old('price', $plan->price) }}" step="0.01" required>
                </div>
                <div class="col-md-4">
                    <label class="form-custom-label">Durasi (hari)</label>
                    <input type="number" name="duration_days" class="form-custom @error('duration_days') is-invalid @enderror" value="{{ old('duration_days', $plan->duration_days) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-custom-label">Max Produk</label>
                    <input type="number" name="max_products" class="form-custom @error('max_products') is-invalid @enderror" value="{{ old('max_products', $plan->max_products) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-custom-label">Max Toko</label>
                    <input type="number" name="max_stores" class="form-custom @error('max_stores') is-invalid @enderror" value="{{ old('max_stores', $plan->max_stores) }}" required>
                </div>
                <div class="col-md-12">
                    <label class="form-custom-label">Deskripsi</label>
                    <textarea name="description" class="form-custom @error('description') is-invalid @enderror" rows="3">{{ old('description', $plan->description) }}</textarea>
                </div>
                <div class="col-md-12">
                    <label class="form-custom-label">Fitur (satu per baris)</label>
                    <textarea name="features" class="form-custom @error('features') is-invalid @enderror" rows="4">{{ old('features', is_array($plan->features) ? implode("\n", $plan->features) : $plan->features) }}</textarea>
                </div>
                <div class="col-md-6">
                    <div class="form-check mt-4">
                        <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" {{ old('is_active', $plan->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Paket Aktif</label>
                    </div>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn-custom-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('admin.subscriptions.plans.index') }}" class="btn-custom-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
