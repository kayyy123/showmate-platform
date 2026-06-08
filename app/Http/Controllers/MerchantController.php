<?php

namespace App\Http\Controllers;

use App\Models\CatalogVisit;
use App\Models\Checkout;
use App\Models\Product;
use Illuminate\Http\Request;
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

        $totalProducts = Product::where('user_id', $user->id)->count();
        $activeProducts = Product::where('user_id', $user->id)->where('is_active', true)->count();
        $totalCheckouts = Checkout::where('user_id', $user->id)->count();
        $totalVisits = CatalogVisit::where('user_id', $user->id)->count();

        return Inertia::render('Merchant/Stats', [
            'stats' => [
                'userName' => $user->name,
                'activeProducts' => $activeProducts,
                'totalProducts' => $totalProducts,
                'totalCheckouts' => $totalCheckouts,
                'totalVisits' => $totalVisits,
            ],
        ]);
    }
}
