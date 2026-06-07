<?php

namespace App\Http\Controllers;

use App\Models\CatalogVisit;
use App\Models\Checkout;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $user = $request->user();

        $totalProducts = Product::where('user_id', $user->id)->count();
        $activeProducts = Product::where('user_id', $user->id)->where('is_active', true)->count();
        $totalCheckouts = Checkout::where('user_id', $user->id)->count();
        $totalVisits = CatalogVisit::where('user_id', $user->id)->count();

        $recentProducts = Product::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get(['id', 'name', 'price', 'is_active', 'created_at', 'image', 'category', 'tag', 'alt_text'])
            ->map(fn ($product) => [
                ...$product->toArray(),
                'image_url' => $product->image ? Storage::url($product->image) : null,
            ]);

        return Inertia::render('Dashboard', [
            'stats' => [
                'userName' => $user->name,
                'activeProducts' => $activeProducts,
                'totalProducts' => $totalProducts,
                'totalCheckouts' => $totalCheckouts,
                'totalVisits' => $totalVisits,
            ],
            'recentProducts' => $recentProducts,
        ]);
    }
}
