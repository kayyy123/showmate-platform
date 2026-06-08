<?php

namespace App\Repositories;

use App\Models\Store;
use Illuminate\Pagination\LengthAwarePaginator;

class StoreRepository extends BaseRepository
{
    public function __construct(Store $model)
    {
        parent::__construct($model);
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with('user')->latest()->paginate($perPage);
    }

    public function search(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with('user')
            ->where('name', 'like', "%{$query}%")
            ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$query}%"))
            ->latest()
            ->paginate($perPage);
    }

    public function paginateTrashed(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->onlyTrashed()->with('user')->latest()->paginate($perPage);
    }
}
