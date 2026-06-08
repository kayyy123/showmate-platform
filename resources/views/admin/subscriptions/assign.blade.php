@extends('admin.layouts.master')

@section('title', 'Tetapkan Langganan')
@section('page_title', 'Tetapkan Langganan')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <div class="breadcrumb-custom">
            <a href="{{ route('admin.subscriptions.plans.index') }}">Langganan</a>
            <span class="sep">/</span>
            <span>Tetapkan</span>
        </div>
        <h1>Tetapkan Langganan ke Merchant</h1>
    </div>
</div>

<div class="card-custom">
    <div class="card-body p-4">
        <form action="{{ route('admin.subscriptions.assign') }}" method="POST">
            @csrf
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-custom-label">Merchant</label>
                    <select name="user_id" class="form-custom @error('user_id') is-invalid @enderror" required>
                        <option value="">Pilih Merchant</option>
                        @foreach ($merchants as $merchant)
                            <option value="{{ $merchant->id }}" {{ old('user_id') == $merchant->id ? 'selected' : '' }}>
                                {{ $merchant->name }} ({{ $merchant->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-custom-label">Paket Langganan</label>
                    <select name="subscription_plan_id" class="form-custom @error('subscription_plan_id') is-invalid @enderror" required>
                        <option value="">Pilih Paket</option>
                        @foreach ($plans as $plan)
                            <option value="{{ $plan->id }}" {{ old('subscription_plan_id') == $plan->id ? 'selected' : '' }}>
                                {{ $plan->name }} - Rp{{ number_format($plan->price, 0, ',', '.') }}/{{ $plan->duration_days }}hr
                            </option>
                        @endforeach
                    </select>
                    @error('subscription_plan_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn-custom-primary"><i class="bi bi-check-lg"></i> Tetapkan</button>
                <a href="{{ route('admin.subscriptions.plans.index') }}" class="btn-custom-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
