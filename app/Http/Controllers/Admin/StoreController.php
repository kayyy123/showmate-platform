<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRequest;
use App\Models\User;
use App\Services\Admin\StoreService;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __construct(private StoreService $storeService) {}

    public function index(Request $request)
    {
        if ($request->has('search')) {
            $stores = $this->storeService->search($request->search);
        } else {
            $stores = $this->storeService->paginate();
        }

        return view('admin.stores.index', compact('stores'));
    }

    public function create()
    {
        $merchants = User::where('role', 'merchant')->orderBy('name')->get();
        return view('admin.stores.create', compact('merchants'));
    }

    public function store(StoreRequest $request)
    {
        $this->storeService->create($request->validated());

        return redirect()->route('admin.stores.index')
            ->with('success', 'Toko berhasil dibuat.');
    }

    public function edit(int $id)
    {
        $store = $this->storeService->find($id);
        $merchants = User::where('role', 'merchant')->orderBy('name')->get();
        return view('admin.stores.edit', compact('store', 'merchants'));
    }

    public function update(StoreRequest $request, int $id)
    {
        $this->storeService->update($id, $request->validated());

        return redirect()->route('admin.stores.index')
            ->with('success', 'Toko berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $this->storeService->delete($id);

        return redirect()->route('admin.stores.index')
            ->with('success', 'Toko berhasil dihapus.');
    }

    public function restore(int $id)
    {
        $this->storeService->restore($id);

        return redirect()->route('admin.stores.index')
            ->with('success', 'Toko berhasil dipulihkan.');
    }

    public function forceDelete(int $id)
    {
        $this->storeService->forceDelete($id);

        return redirect()->route('admin.stores.index')
            ->with('success', 'Toko berhasil dihapus permanen.');
    }

    public function trashed()
    {
        $stores = $this->storeService->paginateTrashed();
        return view('admin.stores.index', compact('stores'));
    }
}
