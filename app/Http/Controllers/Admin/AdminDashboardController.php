<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CatalogVisit;
use App\Models\Checkout;
use App\Models\Link;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalLinks = Link::count();
        $totalOrders = Checkout::count();
        $totalVisitors = CatalogVisit::count();
        $activeProducts = Product::where('is_active', true)->count();
        $activeLinks = Link::where('is_active', true)->count();
        $pendingOrders = Checkout::where('status', 'pending')->count();

        $now = Carbon::now();
        $thirtyDaysAgo = $now->copy()->subDays(29)->startOfDay();

        $visitorTrend = CatalogVisit::where('visited_at', '>=', $thirtyDaysAgo)
            ->select(DB::raw('DATE(visited_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $linkTrend = Link::where('created_at', '>=', $thirtyDaysAgo)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $dates = [];
        for ($i = 29; $i >= 0; $i--) {
            $dates[] = $now->copy()->subDays($i)->format('Y-m-d');
        }

        $visitorTrendFormatted = [];
        $linkTrendFormatted = [];
        foreach ($dates as $d) {
            $visitorTrendFormatted[] = ['date' => $d, 'count' => (int) ($visitorTrend[$d] ?? 0)];
            $linkTrendFormatted[] = ['date' => $d, 'count' => (int) ($linkTrend[$d] ?? 0)];
        }

        $recentUsers = User::latest()
            ->take(5)
            ->get()
            ->map(fn ($u) => [
                'name' => $u->name,
                'email' => $u->email,
                'total_products' => Product::where('user_id', $u->id)->count(),
                'total_links' => Link::where('user_id', $u->id)->count(),
                'joined' => $u->created_at->format('d M Y'),
            ]);

        $recentProducts = Product::with('user:id,name')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($p) => [
                'name' => $p->name,
                'owner' => $p->user?->name ?? 'Unknown',
                'price' => $p->price,
                'is_active' => $p->is_active,
            ]);

        return view('admin.dashboard', [
            'stats' => [
                'totalUsers' => $totalUsers,
                'totalProducts' => $totalProducts,
                'totalLinks' => $totalLinks,
                'totalOrders' => $totalOrders,
                'totalVisitors' => $totalVisitors,
                'activeProducts' => $activeProducts,
                'activeLinks' => $activeLinks,
                'pendingOrders' => $pendingOrders,
            ],
            'visitorTrend' => $visitorTrendFormatted,
            'linkTrend' => $linkTrendFormatted,
            'recentUsers' => $recentUsers,
            'recentProducts' => $recentProducts,
        ]);
    }

    public function users()
    {
        return redirect()->route('admin.dashboard');
    }

    public function products()
    {
        return redirect()->route('admin.dashboard');
    }

    public function links()
    {
        return redirect()->route('admin.dashboard');
    }

    public function orders()
    {
        return redirect()->route('admin.dashboard');
    }

    public function statistics()
    {
        return redirect()->route('admin.dashboard');
    }

    public function settings()
    {
        return redirect()->route('admin.dashboard');
    }
}
