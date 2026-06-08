<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WithdrawalRequest;
use App\Services\Admin\WithdrawalService;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function __construct(private WithdrawalService $withdrawalService) {}

    public function index(Request $request)
    {
        if ($request->has('trashed')) {
            $withdrawals = $this->withdrawalService->paginateTrashed();
        } else {
            $withdrawals = $this->withdrawalService->paginate();
        }

        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function show(int $id)
    {
        $withdrawal = $this->withdrawalService->find($id);
        return view('admin.withdrawals.show', compact('withdrawal'));
    }

    public function approve(int $id)
    {
        $this->withdrawalService->approve($id);

        return redirect()->route('admin.withdrawals.index')
            ->with('success', 'Penarikan dana berhasil disetujui.');
    }

    public function reject(Request $request, int $id)
    {
        $request->validate(['notes' => 'nullable|string|max:500']);

        $this->withdrawalService->reject($id, $request->notes);

        return redirect()->route('admin.withdrawals.index')
            ->with('success', 'Penarikan dana ditolak.');
    }

    public function destroy(int $id)
    {
        $this->withdrawalService->delete($id);

        return redirect()->route('admin.withdrawals.index')
            ->with('success', 'Penarikan berhasil dihapus.');
    }

    public function restore(int $id)
    {
        $this->withdrawalService->restore($id);

        return redirect()->route('admin.withdrawals.index')
            ->with('success', 'Penarikan berhasil dipulihkan.');
    }

    public function forceDelete(int $id)
    {
        $this->withdrawalService->forceDelete($id);

        return redirect()->route('admin.withdrawals.index')
            ->with('success', 'Penarikan berhasil dihapus permanen.');
    }
}
