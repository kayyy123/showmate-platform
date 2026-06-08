<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AssignSubscriptionRequest;
use App\Http\Requests\Admin\SubscriptionPlanRequest;
use App\Models\User;
use App\Services\Admin\SubscriptionService;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    public function __construct(private SubscriptionService $subscriptionService) {}

    public function index()
    {
        $plans = $this->subscriptionService->paginatePlans();
        $subscriptions = $this->subscriptionService->getMerchantSubscriptions();

        return view('admin.subscriptions.index', compact('plans', 'subscriptions'));
    }

    public function create()
    {
        return view('admin.subscriptions.create');
    }

    public function store(SubscriptionPlanRequest $request)
    {
        $this->subscriptionService->createPlan($request->validated());

        return redirect()->route('admin.subscriptions.plans.index')
            ->with('success', 'Paket langganan berhasil dibuat.');
    }

    public function edit(int $id)
    {
        $plan = $this->subscriptionService->findPlan($id);
        return view('admin.subscriptions.edit', compact('plan'));
    }

    public function update(SubscriptionPlanRequest $request, int $id)
    {
        $this->subscriptionService->updatePlan($id, $request->validated());

        return redirect()->route('admin.subscriptions.plans.index')
            ->with('success', 'Paket langganan berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $this->subscriptionService->deletePlan($id);

        return redirect()->route('admin.subscriptions.plans.index')
            ->with('success', 'Paket langganan berhasil dihapus.');
    }

    public function assignForm()
    {
        $plans = \App\Models\SubscriptionPlan::active()->orderBy('name')->get();
        $merchants = User::where('role', 'merchant')->orderBy('name')->get();
        return view('admin.subscriptions.assign', compact('plans', 'merchants'));
    }

    public function assign(AssignSubscriptionRequest $request)
    {
        $this->subscriptionService->assignSubscription(
            $request->user_id,
            $request->subscription_plan_id
        );

        return redirect()->route('admin.subscriptions.plans.index')
            ->with('success', 'Langganan berhasil ditetapkan.');
    }
}
