@extends('admin.layouts.master')

@section('title', 'Edit Produk')
@section('page_title', 'Edit Produk')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <div class="breadcrumb-custom">
            <a href="{{ route('admin.products.index') }}">Produk</a>
            <span class="sep">/</span>
            <span>Edit</span>
        </div>
        <h1>Edit Produk</h1>
    </div>
</div>

<div class="card-custom">
    <div class="card-body p-4">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-custom-label">Pemilik (Merchant)</label>
                    <select name="user_id" class="form-custom @error('user_id') is-invalid @enderror" required>
                        @foreach ($merchants as $merchant)
                            <option value="{{ $merchant->id }}" {{ old('user_id', $product->user_id) == $merchant->id ? 'selected' : '' }}>{{ $merchant->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-custom-label">Nama Produk</label>
                    <input type="text" name="name" class="form-custom @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-custom-label">Toko</label>
                    <select name="store_id" class="form-custom @error('store_id') is-invalid @enderror">
                        <option value="">Pilih Toko</option>
                        @foreach ($stores as $store)
                            <option value="{{ $store->id }}" {{ old('store_id', $product->store_id) == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-custom-label">Kategori</label>
                    <select name="category_id" class="form-custom @error('category_id') is-invalid @enderror">
                        <option value="">Pilih Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-custom-label">Harga</label>
                    <input type="number" name="price" class="form-custom @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" step="0.01" required>
                    @error('price') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-custom-label">Tipe</label>
                    <select name="type" class="form-custom @error('type') is-invalid @enderror" required>
                        <option value="physical" {{ old('type', $product->type) === 'physical' ? 'selected' : '' }}>Fisik</option>
                        <option value="service" {{ old('type', $product->type) === 'service' ? 'selected' : '' }}>Jasa</option>
                        <option value="digital" {{ old('type', $product->type) === 'digital' ? 'selected' : '' }}>Digital</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-custom-label">Tipe Harga</label>
                    <select name="pricing_type" class="form-custom @error('pricing_type') is-invalid @enderror" required>
                        <option value="fixed" {{ old('pricing_type', $product->pricing_type) === 'fixed' ? 'selected' : '' }}>Tetap</option>
                        <option value="negotiable" {{ old('pricing_type', $product->pricing_type) === 'negotiable' ? 'selected' : '' }}>Nego</option>
                        <option value="free" {{ old('pricing_type', $product->pricing_type) === 'free' ? 'selected' : '' }}>Gratis</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-custom-label">Gambar Produk</label>
                    <input type="file" name="image" class="form-custom @error('image') is-invalid @enderror" accept="image/*">
                    @if($product->image) <small class="d-block mt-1 text-muted">Kosongkan jika tidak ingin mengubah</small> @endif
                    @error('image') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-custom-label">Tag</label>
                    <input type="text" name="tag" class="form-custom @error('tag') is-invalid @enderror" value="{{ old('tag', $product->tag) }}">
                </div>
                <div class="col-md-12">
                    <label class="form-custom-label">Deskripsi</label>
                    <textarea name="description" class="form-custom @error('description') is-invalid @enderror" rows="3">{{ old('description', $product->description) }}</textarea>
                </div>
                <div class="col-md-6">
                    <div class="form-check mt-4">
                        <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Produk Aktif</label>
                    </div>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn-custom-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('admin.products.index') }}" class="btn-custom-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
