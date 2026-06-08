<?php

namespace App\Services\Admin;

use App\Models\Withdrawal;
use App\Repositories\WithdrawalRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class WithdrawalService
{
    public function __construct(private WithdrawalRepository $repository) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    public function find(int $id): Withdrawal
    {
        return $this->repository->findOrFail($id);
    }

    public function approve(int $id): Withdrawal
    {
        $withdrawal = $this->repository->findOrFail($id);
        return $this->repository->update($withdrawal, ['status' => 'approved']);
    }

    public function reject(int $id, string $notes = null): Withdrawal
    {
        $withdrawal = $this->repository->findOrFail($id);
        return $this->repository->update($withdrawal, [
            'status' => 'rejected',
            'notes' => $notes,
        ]);
    }

    public function delete(int $id): bool
    {
        $withdrawal = $this->repository->findOrFail($id);
        return $this->repository->delete($withdrawal);
    }

    public function restore(int $id): bool
    {
        $withdrawal = Withdrawal::onlyTrashed()->findOrFail($id);
        return $withdrawal->restore();
    }

    public function forceDelete(int $id): bool
    {
        $withdrawal = Withdrawal::onlyTrashed()->findOrFail($id);
        return $withdrawal->forceDelete();
    }

    public function paginateTrashed(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginateTrashed($perPage);
    }
}
