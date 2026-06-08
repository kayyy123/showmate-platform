@extends('admin.layouts.master')

@section('title', 'Edit Toko')
@section('page_title', 'Edit Toko')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <div class="breadcrumb-custom">
            <a href="{{ route('admin.stores.index') }}">Toko</a>
            <span class="sep">/</span>
            <span>Edit</span>
        </div>
        <h1>Edit Toko</h1>
    </div>
</div>

<div class="card-custom">
    <div class="card-body p-4">
        <form action="{{ route('admin.stores.update', $store->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-custom-label">Pemilik (Merchant)</label>
                    <select name="user_id" class="form-custom @error('user_id') is-invalid @enderror" required>
                        @foreach ($merchants as $merchant)
                            <option value="{{ $merchant->id }}" {{ old('user_id', $store->user_id) == $merchant->id ? 'selected' : '' }}>
                                {{ $merchant->name }} ({{ $merchant->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-custom-label">Nama Toko</label>
                    <input type="text" name="name" class="form-custom @error('name') is-invalid @enderror" value="{{ old('name', $store->name) }}" required>
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-custom-label">Slug</label>
                    <input type="text" name="slug" class="form-custom @error('slug') is-invalid @enderror" value="{{ old('slug', $store->slug) }}">
                    @error('slug') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-custom-label">Logo Toko</label>
                    <input type="file" name="logo" class="form-custom @error('logo') is-invalid @enderror" accept="image/*">
                    @if($store->logo)
                        <small class="d-block mt-1 text-muted">Kosongkan jika tidak ingin mengubah logo</small>
                    @endif
                    @error('logo') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-12">
                    <label class="form-custom-label">Deskripsi</label>
                    <textarea name="description" class="form-custom @error('description') is-invalid @enderror" rows="3">{{ old('description', $store->description) }}</textarea>
                    @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-custom-label">WhatsApp</label>
                    <input type="text" name="whatsapp" class="form-custom @error('whatsapp') is-invalid @enderror" value="{{ old('whatsapp', $store->whatsapp) }}">
                    @error('whatsapp') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-custom-label">Instagram</label>
                    <input type="text" name="instagram" class="form-custom @error('instagram') is-invalid @enderror" value="{{ old('instagram', $store->instagram) }}">
                    @error('instagram') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-custom-label">TikTok</label>
                    <input type="text" name="tiktok" class="form-custom @error('tiktok') is-invalid @enderror" value="{{ old('tiktok', $store->tiktok) }}">
                    @error('tiktok') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-custom-label">Shopee</label>
                    <input type="text" name="shopee" class="form-custom @error('shopee') is-invalid @enderror" value="{{ old('shopee', $store->shopee) }}">
                    @error('shopee') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-custom-label">Tokopedia</label>
                    <input type="text" name="tokopedia" class="form-custom @error('tokopedia') is-invalid @enderror" value="{{ old('tokopedia', $store->tokopedia) }}">
                    @error('tokopedia') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-6">
                    <div class="form-check mt-4">
                        <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" {{ old('is_active', $store->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Toko Aktif</label>
                    </div>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn-custom-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('admin.stores.index') }}" class="btn-custom-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
