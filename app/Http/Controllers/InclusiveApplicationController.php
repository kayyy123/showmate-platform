<?php

namespace App\Http\Controllers;

use App\Http\Requests\InclusiveApplicationRequest;
use App\Models\InclusiveApplication;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InclusiveApplicationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $storeIds = $user->stores()->pluck('id');
        $applications = InclusiveApplication::with('store')
            ->whereIn('store_id', $storeIds)
            ->latest()
            ->paginate(15);

        return view('inclusive-applications.index', compact('applications'));
    }

    public function create()
    {
        $stores = auth()->user()->stores;
        $disabilityTypes = [
            'tunanetra' => 'Tunanetra',
            'tunarungu' => 'Tunarungu',
            'tunawicara' => 'Tunawicara',
            'tunadaksa' => 'Tunadaksa (Fisik)',
            'tunagrahita' => 'Tunagrahita',
            'lainnya' => 'Lainnya',
        ];

        return view('inclusive-applications.create', compact('stores', 'disabilityTypes'));
    }

    public function store(InclusiveApplicationRequest $request)
    {
        $store = Store::findOrFail($request->store_id);

        if ($store->user_id !== auth()->id()) {
            abort(403);
        }

        $data = $request->validated();
        $data['identity_document'] = $request->file('identity_document')->store('inclusive/identity', 'public');
        $data['support_document'] = $request->file('support_document')->store('inclusive/support', 'public');

        InclusiveApplication::create($data);

        return redirect()->route('inclusive-applications.index')
            ->with('success', 'Pengajuan Program Inklusif berhasil dikirim.');
    }

    public function show(int $id)
    {
        $application = InclusiveApplication::with('store')->findOrFail($id);

        $userStoreIds = auth()->user()->stores()->pluck('id');
        if (!$userStoreIds->contains($application->store_id)) {
            abort(403);
        }

        return view('inclusive-applications.show', compact('application'));
    }
}
