<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Services\Admin\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService) {}

    public function index(Request $request)
    {
        if ($request->has('search')) {
            $categories = $this->categoryService->search($request->search);
        } else {
            $categories = $this->categoryService->paginate();
        }

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(CategoryRequest $request)
    {
        $this->categoryService->create($request->validated());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dibuat.');
    }

    public function edit(int $id)
    {
        $category = $this->categoryService->find($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, int $id)
    {
        $this->categoryService->update($id, $request->validated());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $this->categoryService->delete($id);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }

    public function restore(int $id)
    {
        $this->categoryService->restore($id);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dipulihkan.');
    }

    public function forceDelete(int $id)
    {
        $this->categoryService->forceDelete($id);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus permanen.');
    }

    public function trashed()
    {
        $categories = $this->categoryService->paginateTrashed();
        return view('admin.categories.index', compact('categories'));
    }
}
