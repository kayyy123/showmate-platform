<?php

namespace App\Http\Controllers;

use App\Models\CatalogVisit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CatalogController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $user = User::where('store_slug', $slug)->firstOrFail();
        $products = $user->products()
            ->where('is_active', true)
            ->latest()
            ->get()
            ->map(fn ($product) => [
                ...$product->toArray(),
                'image_url' => $product->image ? Storage::url($product->image) : null,
            ]);

        CatalogVisit::create([
            'user_id' => $user->id,
            'visitor_ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'visited_at' => now(),
        ]);

        $links = $user->links()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($link) => [...$link->toArray()]);

        return Inertia::render('Catalog/Show', [
            'merchant' => [
                'id' => $user->id,
                'name' => $user->store_name ?: $user->name,
                'description' => $user->store_description ?: 'Katalog produk ' . $user->name,
                'whatsapp_number' => $user->whatsapp_number,
                'instagram_url' => $user->instagram_url,
                'tiktok_url' => $user->tiktok_url,
                'shopee_url' => $user->shopee_url,
                'tokopedia_url' => $user->tokopedia_url,
                'store_logo' => $user->store_logo,
                'store_logo_url' => $user->store_logo ? Storage::url($user->store_logo) : null,
            ],
            'products' => $products,
            'links' => $links,
        ]);
    }
}
