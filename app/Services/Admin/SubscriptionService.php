<?php

namespace App\Services\Admin;

use App\Models\MerchantSubscription;
use App\Models\SubscriptionPlan;
use App\Repositories\SubscriptionPlanRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class SubscriptionService
{
    public function __construct(
        private SubscriptionPlanRepository $planRepository,
        private UserRepository $userRepository,
    ) {}

    public function paginatePlans(int $perPage = 15): LengthAwarePaginator
    {
        return $this->planRepository->paginate($perPage);
    }

    public function findPlan(int $id): SubscriptionPlan
    {
        return $this->planRepository->findOrFail($id);
    }

    public function createPlan(array $data): SubscriptionPlan
    {
        if (isset($data['features']) && is_array($data['features'])) {
            $data['features'] = json_encode($data['features']);
        }

        return $this->planRepository->create($data);
    }

    public function updatePlan(int $id, array $data): SubscriptionPlan
    {
        $plan = $this->planRepository->findOrFail($id);

        if (isset($data['features']) && is_array($data['features'])) {
            $data['features'] = json_encode($data['features']);
        }

        return $this->planRepository->update($plan, $data);
    }

    public function deletePlan(int $id): bool
    {
        $plan = $this->planRepository->findOrFail($id);
        return $this->planRepository->delete($plan);
    }

    public function assignSubscription(int $userId, int $planId): MerchantSubscription
    {
        $plan = $this->planRepository->findOrFail($planId);
        $user = $this->userRepository->findOrFail($userId);

        MerchantSubscription::where('user_id', $userId)
            ->where('status', 'active')
            ->update(['status' => 'expired']);

        return MerchantSubscription::create([
            'user_id' => $userId,
            'subscription_plan_id' => $planId,
            'starts_at' => now(),
            'ends_at' => now()->addDays($plan->duration_days),
            'status' => 'active',
        ]);
    }

    public function getMerchantSubscriptions(int $perPage = 15): LengthAwarePaginator
    {
        return MerchantSubscription::with(['user', 'plan'])
            ->latest()
            ->paginate($perPage);
    }

    public function expireSubscriptions(): int
    {
        return MerchantSubscription::where('status', 'active')
            ->where('ends_at', '<', now())
            ->update(['status' => 'expired']);
    }
}
