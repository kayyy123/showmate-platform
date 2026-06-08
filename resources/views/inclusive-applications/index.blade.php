@extends('layouts.merchant', ['activeTab' => 'inclusive'])

@section('title', 'Program Inklusif')
@section('breadcrumb', 'Program Inklusif')

@section('content')
<div class="page-title-section d-flex justify-content-between align-items-center">
    <div>
        <h1>Program Inklusif</h1>
        <p>Ajukan dan lacak status program inklusif toko Anda</p>
    </div>
    <a href="{{ route('inclusive-applications.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Ajukan Baru
    </a>
</div>

<div class="card-dashboard card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-dashboard">
                <thead>
                    <tr>
                        <th>Toko</th>
                        <th>Disabilitas</th>
                        <th>Status</th>
                        <th>Diajukan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($applications as $app)
                    <tr>
                        <td><span class="fw-semibold">{{ $app->store?->name }}</span></td>
                        <td>{{ ucfirst($app->disability_type) }}</td>
                        <td>
                            <span class="badge-status
                                @if($app->status === 'approved') bg-success
                                @elseif($app->status === 'rejected') bg-danger
                                @else bg-warning text-dark @endif">
                                {{ ucfirst($app->status) }}
                            </span>
                        </td>
                        <td style="color:var(--text-muted);font-size:0.8rem;">{{ $app->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('inclusive-applications.show', $app->id) }}" class="btn btn-sm btn-outline-light">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4" style="color:var(--text-muted);">Belum ada pengajuan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($applications->hasPages())
        <div class="mt-3">
            {{ $applications->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
