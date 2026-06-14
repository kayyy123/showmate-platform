<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InclusiveApplicationReviewRequest;
use App\Models\InclusiveProgramApplication;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InclusiveProgramController extends Controller
{
    public function index(Request $request)
    {
        $query = InclusiveProgramApplication::with('user');

        if ($request->has('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->whereHas('user', fn($qq) => $qq->where('name', 'like', "%{$s}%")
                    ->orWhere('store_name', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%"));
            });
        }

        $applications = $query->latest()->paginate(15);

        return view('admin.inclusive-program.index', compact('applications'));
    }

    public function show(int $id)
    {
        $application = InclusiveProgramApplication::with('user', 'reviewer')->findOrFail($id);
        return view('admin.inclusive-program.show', compact('application'));
    }

    public function approve(InclusiveApplicationReviewRequest $request, int $id)
    {
        $application = InclusiveProgramApplication::findOrFail($id);

        if ($application->status !== 'pending') {
            return redirect()->route('admin.inclusive-program.index')
                ->with('error', 'Pengajuan sudah diproses sebelumnya.');
        }

        $application->update([
            'status' => 'approved',
            'rejection_reason' => null,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => Carbon::now(),
        ]);

        return redirect()->route('admin.inclusive-program.index')
            ->with('success', 'Pengajuan Program Inklusif telah disetujui.');
    }

    public function reject(InclusiveApplicationReviewRequest $request, int $id)
    {
        $application = InclusiveProgramApplication::findOrFail($id);

        if ($application->status !== 'pending') {
            return redirect()->route('admin.inclusive-program.index')
                ->with('error', 'Pengajuan sudah diproses sebelumnya.');
        }

        $application->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => Carbon::now(),
        ]);

        return redirect()->route('admin.inclusive-program.index')
            ->with('success', 'Pengajuan Program Inklusif ditolak.');
    }
}
