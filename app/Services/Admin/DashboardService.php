<?php

namespace App\Services\Admin;

use App\Models\Checkout;
use App\Models\InclusiveApplication;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getStats(): array
    {
        return [
            'total_users' => User::count(),
            'total_stores' => Store::count(),
            'total_products' => Product::count(),
            'total_orders' => Checkout::count(),
            'store_pro' => User::where('role', 'merchant')->where('plan', 'pro')->count(),
            'inclusive_sellers' => Store::whereHas('inclusiveApplications', fn($q) => $q->where('status', 'approved'))->count(),
            'pending_applications' => InclusiveApplication::where('status', 'pending')->count(),
        ];
    }

    public function getMonthlyOrders(): array
    {
        $monthly = Checkout::select(
            DB::raw("DATE_FORMAT(created_at, '%m') as month"),
            DB::raw('count(*) as total'),
            DB::raw('sum(total_price) as revenue')
        )
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%m')"))
            ->orderBy('month')
            ->get();

        $labels = [];
        $orders = [];
        $revenue = [];

        foreach (range(1, 12) as $m) {
            $labels[] = Carbon::create()->month($m)->format('F');
            $found = $monthly->firstWhere('month', str_pad($m, 2, '0', STR_PAD_LEFT));
            $orders[] = $found ? (int) $found->total : 0;
            $revenue[] = $found ? (float) $found->revenue : 0;
        }

        return compact('labels', 'orders', 'revenue');
    }

    public function getRecentActivities(int $limit = 10): array
    {
        $recentOrders = Checkout::with('user')->latest()->take($limit)->get();
        $recentMerchants = User::where('role', 'merchant')->latest()->take($limit)->get();

        return compact('recentOrders', 'recentMerchants');
    }

    public function getMerchantGrowth(): array
    {
        $growth = User::select(
            DB::raw("DATE_FORMAT(created_at, '%m') as month"),
            DB::raw('count(*) as total')
        )
            ->where('role', 'merchant')
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%m')"))
            ->orderBy('month')
            ->get();

        $labels = [];
        $data = [];

        foreach (range(1, 12) as $m) {
            $labels[] = Carbon::create()->month($m)->format('F');
            $found = $growth->firstWhere('month', str_pad($m, 2, '0', STR_PAD_LEFT));
            $data[] = $found ? (int) $found->total : 0;
        }

        return compact('labels', 'data');
    }

    public function getTopProducts(int $limit = 5): array
    {
        return Product::withCount('checkouts')
            ->having('checkouts_count', '>', 0)
            ->orderByDesc('checkouts_count')
            ->take($limit)
            ->get()
            ->toArray();
    }
}
