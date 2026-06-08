<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\DashboardService;

class AdminDashboardController extends Controller
{
    public function __construct(private DashboardService $dashboardService) {}

    public function index()
    {
        $stats = $this->dashboardService->getStats();
        $monthlyOrders = $this->dashboardService->getMonthlyOrders();
        $activities = $this->dashboardService->getRecentActivities();
        $merchantGrowth = $this->dashboardService->getMerchantGrowth();
        $topProducts = $this->dashboardService->getTopProducts();

        return view('admin.dashboard', compact(
            'stats',
            'monthlyOrders',
            'activities',
            'merchantGrowth',
            'topProducts'
        ));
    }
}
