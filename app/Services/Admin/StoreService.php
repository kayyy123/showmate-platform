<?php

namespace App\Services\Admin;

use App\Models\Store;
use App\Repositories\StoreRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoreService
{
    public function __construct(private StoreRepository $repository) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    public function search(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->search($query, $perPage);
    }

    public function find(int $id): Store
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data): Store
    {
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        if (isset($data['logo']) && $data['logo'] instanceof \Illuminate\Http\UploadedFile) {
            $data['logo'] = $data['logo']->store('stores', 'public');
        }

        return $this->repository->create($data);
    }

    public function update(int $id, array $data): Store
    {
        $store = $this->repository->findOrFail($id);

        if (isset($data['logo']) && $data['logo'] instanceof \Illuminate\Http\UploadedFile) {
            if ($store->logo) {
                Storage::disk('public')->delete($store->logo);
            }
            $data['logo'] = $data['logo']->store('stores', 'public');
        }

        return $this->repository->update($store, $data);
    }

    public function delete(int $id): bool
    {
        $store = $this->repository->findOrFail($id);
        return $this->repository->delete($store);
    }

    public function restore(int $id): bool
    {
        $store = Store::onlyTrashed()->findOrFail($id);
        return $store->restore();
    }

    public function forceDelete(int $id): bool
    {
        $store = Store::onlyTrashed()->findOrFail($id);

        if ($store->logo) {
            Storage::disk('public')->delete($store->logo);
        }

        return $store->forceDelete();
    }

    public function paginateTrashed(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginateTrashed($perPage);
    }
}
