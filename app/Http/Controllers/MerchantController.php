<?php

namespace App\Http\Controllers;

use App\Models\CatalogVisit;
use App\Models\Checkout;
use App\Models\Link;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MerchantController extends Controller
{
    public function catalog(Request $request)
    {
        $products = Product::where('user_id', $request->user()->id)
            ->latest()
            ->get(['id', 'name', 'price', 'is_active', 'created_at', 'image', 'category', 'tag', 'alt_text', 'description'])
            ->map(fn ($product) => [
                ...$product->toArray(),
                'image_url' => $product->image ? Storage::url($product->image) : null,
            ]);

        return Inertia::render('Merchant/Catalog', [
            'products' => $products,
        ]);
    }

    public function manage(Request $request)
    {
        $user = $request->user();

        $products = Product::where('user_id', $user->id)
            ->latest()
            ->get(['id', 'name', 'price', 'is_active', 'created_at', 'image', 'category', 'tag', 'alt_text'])
            ->map(fn ($product) => [
                ...$product->toArray(),
                'image_url' => $product->image ? Storage::url($product->image) : null,
            ]);

        return Inertia::render('Merchant/Manage', [
            'products' => $products,
        ]);
    }

    public function stats(Request $request)
    {
        $user = $request->user();
        $now = Carbon::now();
        $sevenDaysAgo = $now->copy()->subDays(6)->startOfDay();

        $totalProducts = Product::where('user_id', $user->id)->count();
        $activeProducts = Product::where('user_id', $user->id)->where('is_active', true)->count();
        $totalCheckouts = Checkout::where('user_id', $user->id)->count();
        $totalVisits = CatalogVisit::where('user_id', $user->id)->count();
        $totalLinks = Link::where('user_id', $user->id)->count();
        $activeLinks = Link::where('user_id', $user->id)->where('is_active', true)->count();

        $totalRevenue = Checkout::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed', 'completed'])
            ->sum('total_price');

        $visitTrend = CatalogVisit::where('user_id', $user->id)
            ->where('visited_at', '>=', $sevenDaysAgo)
            ->select(DB::raw('DATE(visited_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $checkoutTrend = Checkout::where('user_id', $user->id)
            ->where('created_at', '>=', $sevenDaysAgo)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $dates = [];
        for ($i = 6; $i >= 0; $i--) {
            $dates[] = $now->copy()->subDays($i)->format('Y-m-d');
        }

        $visitTrendFormatted = [];
        $checkoutTrendFormatted = [];
        $peakVisit = 1;
        $peakCheckout = 1;
        foreach ($dates as $d) {
            $vc = (int) ($visitTrend[$d] ?? 0);
            $cc = (int) ($checkoutTrend[$d] ?? 0);
            $visitTrendFormatted[] = ['date' => $d, 'count' => $vc];
            $checkoutTrendFormatted[] = ['date' => $d, 'count' => $cc];
            if ($vc > $peakVisit) $peakVisit = $vc;
            if ($cc > $peakCheckout) $peakCheckout = $cc;
        }

        $recentCheckouts = Checkout::where('user_id', $user->id)
            ->with('product:id,name')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($c) => [
                'id' => $c->id,
                'product_name' => $c->product?->name ?? 'Produk dihapus',
                'buyer_name' => $c->buyer_name,
                'quantity' => $c->quantity,
                'total_price' => $c->total_price,
                'status' => $c->status,
                'created_at' => $c->created_at->format('d M Y H:i'),
            ]);

        $productStats = Product::where('user_id', $user->id)
            ->withCount('checkouts')
            ->orderByDesc('checkouts_count')
            ->get(['id', 'name', 'price', 'is_active'])
            ->map(fn ($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'price' => $p->price,
                'is_active' => $p->is_active,
                'checkouts_count' => $p->checkouts_count,
            ]);

        return Inertia::render('Merchant/Stats', [
            'stats' => [
                'userName' => $user->name,
                'totalProducts' => $totalProducts,
                'activeProducts' => $activeProducts,
                'totalCheckouts' => $totalCheckouts,
                'totalVisits' => $totalVisits,
                'totalLinks' => $totalLinks,
                'activeLinks' => $activeLinks,
                'totalRevenue' => (float) $totalRevenue,
                'visitTrend' => $visitTrendFormatted,
                'checkoutTrend' => $checkoutTrendFormatted,
                'peakVisit' => $peakVisit,
                'peakCheckout' => $peakCheckout,
                'recentCheckouts' => $recentCheckouts,
                'productStats' => $productStats,
            ],
        ]);
    }
}
