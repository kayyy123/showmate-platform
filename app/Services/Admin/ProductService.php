<?php

namespace App\Services\Admin;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function __construct(private ProductRepository $repository) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    public function search(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->search($query, $perPage);
    }

    public function find(int $id): Product
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data): Product
    {
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $data['image'] = $data['image']->store('products', 'public');
        }

        return $this->repository->create($data);
    }

    public function update(int $id, array $data): Product
    {
        $product = $this->repository->findOrFail($id);

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $data['image']->store('products', 'public');
        }

        return $this->repository->update($product, $data);
    }

    public function delete(int $id): bool
    {
        $product = $this->repository->findOrFail($id);
        return $this->repository->delete($product);
    }

    public function restore(int $id): bool
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        return $product->restore();
    }

    public function forceDelete(int $id): bool
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        return $product->forceDelete();
    }

    public function toggleVisibility(int $id): Product
    {
        return $this->repository->updateVisibility($id);
    }

    public function paginateTrashed(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginateTrashed($perPage);
    }
}
