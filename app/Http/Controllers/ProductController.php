<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function create(Request $request): Response
    {
        return Inertia::render('Products/Create', [
            'productCount' => $request->user()->products()->count(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!$request->user()->canCreateProduct()) {
            return redirect()->back()->with('error', 'Paket Gratis hanya mendukung hingga 10 produk. Upgrade ke Pro untuk katalog tanpa batas.');
        }

        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|max:100',
            'tag' => 'nullable|max:100',
            'alt_text' => 'nullable|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'user_id' => $request->user()->id,
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'type' => 'physical',
            'pricing_type' => 'fixed',
            'category' => $validated['category'],
            'tag' => $validated['tag'],
            'alt_text' => $validated['alt_text'],
            'image' => $imagePath,
            'is_active' => true,
        ]);

        return redirect()->route('merchant.manage')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit(Request $request, Product $product): Response
    {
        if ($product->user_id !== $request->user()->id) {
            abort(403);
        }

        return Inertia::render('Products/Edit', [
            'product' => [
                ...$product->toArray(),
                'image_url' => $product->image ? Storage::url($product->image) : null,
            ],
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        if ($product->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|max:100',
            'tag' => 'nullable|max:100',
            'alt_text' => 'nullable|max:255',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        } else {
            unset($validated['image']);
        }

        $product->update($validated);

        return redirect()->route('merchant.manage')->with('success', 'Produk berhasil diperbarui');
    }

    public function toggleVisibility(Request $request, Product $product): RedirectResponse
    {
        if ($product->user_id !== $request->user()->id) {
            abort(403);
        }

        $product->update(['is_active' => !$product->is_active]);

        $status = $product->is_active ? 'ditampilkan' : 'disembunyikan';

        return redirect()->back()->with('success', "Produk berhasil {$status}");
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        if ($product->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('merchant.manage')->with('success', 'Produk berhasil dihapus');
    }
}
