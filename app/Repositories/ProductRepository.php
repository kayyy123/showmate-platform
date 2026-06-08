<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository extends BaseRepository
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with(['user', 'store', 'category'])->latest()->paginate($perPage);
    }

    public function search(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with(['user', 'store', 'category'])
            ->where('name', 'like', "%{$query}%")
            ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$query}%"))
            ->latest()
            ->paginate($perPage);
    }

    public function paginateTrashed(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->onlyTrashed()->with(['user', 'store'])->latest()->paginate($perPage);
    }

    public function updateVisibility(int $id): Product
    {
        $product = $this->findOrFail($id);
        $product->update(['is_active' => !$product->is_active]);
        return $product->fresh();
    }
}
