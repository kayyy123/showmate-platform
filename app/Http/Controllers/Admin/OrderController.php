<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    public function index(Request $request)
    {
        if ($request->has('search')) {
            $orders = $this->orderService->search($request->search);
        } elseif ($request->has('trashed')) {
            $orders = $this->orderService->paginateTrashed();
        } else {
            $orders = $this->orderService->paginate();
        }

        return view('admin.orders.index', compact('orders'));
    }

    public function show(int $id)
    {
        $order = $this->orderService->find($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate(['status' => 'required|in:pending,completed,cancelled']);

        $this->orderService->updateStatus($id, $request->status);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $this->orderService->delete($id);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil dihapus.');
    }

    public function restore(int $id)
    {
        $this->orderService->restore($id);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil dipulihkan.');
    }

    public function forceDelete(int $id)
    {
        $this->orderService->forceDelete($id);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil dihapus permanen.');
    }
}
