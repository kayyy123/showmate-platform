<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InclusiveApplicationReviewRequest;
use App\Models\InclusiveApplication;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InclusiveApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = InclusiveApplication::with('store.user');

        if ($request->has('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->whereHas('store', fn($qq) => $qq->where('name', 'like', "%{$s}%"))
                  ->orWhereHas('store.user', fn($qq) => $qq->where('name', 'like', "%{$s}%"));
            });
        }

        $applications = $query->latest()->paginate(15);

        return view('admin.inclusive-applications.index', compact('applications'));
    }

    public function show(int $id)
    {
        $application = InclusiveApplication::with('store.user')->findOrFail($id);
        return view('admin.inclusive-applications.show', compact('application'));
    }

    public function approve(InclusiveApplicationReviewRequest $request, int $id)
    {
        $application = InclusiveApplication::findOrFail($id);
        $application->update([
            'status' => 'approved',
            'admin_note' => $request->admin_note,
            'reviewed_at' => Carbon::now(),
        ]);

        return redirect()->route('admin.inclusive-applications.index')
            ->with('success', 'Pengajuan Program Inklusif telah disetujui.');
    }

    public function reject(InclusiveApplicationReviewRequest $request, int $id)
    {
        $application = InclusiveApplication::findOrFail($id);
        $application->update([
            'status' => 'rejected',
            'admin_note' => $request->admin_note,
            'reviewed_at' => Carbon::now(),
        ]);

        return redirect()->route('admin.inclusive-applications.index')
            ->with('success', 'Pengajuan Program Inklusif ditolak.');
    }
}
