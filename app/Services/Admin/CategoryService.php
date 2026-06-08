<?php

namespace App\Services\Admin;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class CategoryService
{
    public function __construct(private CategoryRepository $repository) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    public function search(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->search($query, $perPage);
    }

    public function find(int $id): Category
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data): Category
    {
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        return $this->repository->create($data);
    }

    public function update(int $id, array $data): Category
    {
        $category = $this->repository->findOrFail($id);

        if (isset($data['slug']) && $data['slug'] !== $category->slug) {
            $data['slug'] = Str::slug($data['slug']);
        }

        return $this->repository->update($category, $data);
    }

    public function delete(int $id): bool
    {
        $category = $this->repository->findOrFail($id);
        return $this->repository->delete($category);
    }

    public function restore(int $id): bool
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        return $category->restore();
    }

    public function forceDelete(int $id): bool
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        return $category->forceDelete();
    }

    public function paginateTrashed(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginateTrashed($perPage);
    }

    public function getAllActive(): array
    {
        return Category::active()->orderBy('name')->get()->toArray();
    }
}
