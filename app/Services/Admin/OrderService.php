<?php

namespace App\Services\Admin;

use App\Models\Checkout;
use App\Repositories\OrderRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderService
{
    public function __construct(private OrderRepository $repository) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    public function search(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->search($query, $perPage);
    }

    public function find(int $id): Checkout
    {
        return $this->repository->findOrFail($id);
    }

    public function updateStatus(int $id, string $status): Checkout
    {
        $order = $this->repository->findOrFail($id);
        return $this->repository->update($order, ['status' => $status]);
    }

    public function delete(int $id): bool
    {
        $order = $this->repository->findOrFail($id);
        return $this->repository->delete($order);
    }

    public function restore(int $id): bool
    {
        $order = Checkout::onlyTrashed()->findOrFail($id);
        return $order->restore();
    }

    public function forceDelete(int $id): bool
    {
        $order = Checkout::onlyTrashed()->findOrFail($id);
        return $order->forceDelete();
    }

    public function paginateTrashed(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginateTrashed($perPage);
    }
}
