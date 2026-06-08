<?php

namespace App\Repositories;

use App\Models\Checkout;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository extends BaseRepository
{
    public function __construct(Checkout $model)
    {
        parent::__construct($model);
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with(['user', 'product'])->latest()->paginate($perPage);
    }

    public function search(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with(['user', 'product'])
            ->where('buyer_name', 'like', "%{$query}%")
            ->orWhere('buyer_email', 'like', "%{$query}%")
            ->orWhereHas('product', fn($q) => $q->where('name', 'like', "%{$query}%"))
            ->latest()
            ->paginate($perPage);
    }

    public function paginateTrashed(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->onlyTrashed()->with(['user', 'product'])->latest()->paginate($perPage);
    }
}
