<?php

namespace App\Repositories;

use App\Models\SubscriptionPlan;
use Illuminate\Pagination\LengthAwarePaginator;

class SubscriptionPlanRepository extends BaseRepository
{
    public function __construct(SubscriptionPlan $model)
    {
        parent::__construct($model);
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->withCount('subscriptions')->latest()->paginate($perPage);
    }
}
