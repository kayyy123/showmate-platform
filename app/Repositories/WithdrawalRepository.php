<?php

namespace App\Repositories;

use App\Models\Withdrawal;
use Illuminate\Pagination\LengthAwarePaginator;

class WithdrawalRepository extends BaseRepository
{
    public function __construct(Withdrawal $model)
    {
        parent::__construct($model);
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with('user')->latest()->paginate($perPage);
    }

    public function paginateTrashed(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->onlyTrashed()->with('user')->latest()->paginate($perPage);
    }
}
