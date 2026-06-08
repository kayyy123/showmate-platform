<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Store;
use App\Models\User;
use App\Services\Admin\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService) {}

    public function index(Request $request)
    {
        if ($request->has('search')) {
            $products = $this->productService->search($request->search);
        } else {
            $products = $this->productService->paginate();
        }

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $merchants = User::where('role', 'merchant')->orderBy('name')->get();
        $stores = Store::active()->orderBy('name')->get();
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.products.create', compact('merchants', 'stores', 'categories'));
    }

    public function store(ProductRequest $request)
    {
        $this->productService->create($request->validated());

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dibuat.');
    }

    public function edit(int $id)
    {
        $product = $this->productService->find($id);
        $merchants = User::where('role', 'merchant')->orderBy('name')->get();
        $stores = Store::active()->orderBy('name')->get();
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'merchants', 'stores', 'categories'));
    }

    public function update(ProductRequest $request, int $id)
    {
        $this->productService->update($id, $request->validated());

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $this->productService->delete($id);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    public function toggleVisibility(int $id)
    {
        $this->productService->toggleVisibility($id);

        return redirect()->route('admin.products.index')
            ->with('success', 'Visibilitas produk berhasil diubah.');
    }

    public function restore(int $id)
    {
        $this->productService->restore($id);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dipulihkan.');
    }

    public function forceDelete(int $id)
    {
        $this->productService->forceDelete($id);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus permanen.');
    }

    public function trashed()
    {
        $products = $this->productService->paginateTrashed();
        return view('admin.products.index', compact('products'));
    }
}
