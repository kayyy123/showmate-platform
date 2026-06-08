<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository extends BaseRepository
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->withCount('products')->latest()->paginate($perPage);
    }

    public function search(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('name', 'like', "%{$query}%")->latest()->paginate($perPage);
    }

    public function paginateTrashed(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->onlyTrashed()->latest()->paginate($perPage);
    }
}
