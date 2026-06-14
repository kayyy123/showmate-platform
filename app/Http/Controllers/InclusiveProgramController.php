<?php

namespace App\Http\Controllers;

use App\Http\Requests\InclusiveProgramRequest;
use App\Models\InclusiveProgramApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class InclusiveProgramController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $application = InclusiveProgramApplication::where('user_id', $user->id)
            ->with(['reviewer', 'user'])
            ->latest()
            ->first();

        return Inertia::render('InclusiveProgram/Index', [
            'application' => $application,
        ]);
    }

    public function store(InclusiveProgramRequest $request)
    {
        $user = auth()->user();

        $existing = InclusiveProgramApplication::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return redirect()->back()->withErrors(['message' => 'Pengajuan sedang diproses.']);
        }

        $data = $request->validated();

        if ($request->hasFile('supporting_file')) {
            $data['supporting_file'] = $request->file('supporting_file')
                ->store('inclusive-program/supporting', 'public');
        }

        if (!in_array('other', $data['program_types'] ?? [])) {
            $data['other_program_type'] = null;
        }

        $data['user_id'] = $user->id;
        $data['status'] = 'pending';

        InclusiveProgramApplication::create($data);

        return redirect()->route('inclusive-program.index')
            ->with('success', 'Pengajuan Program Inklusif berhasil dikirim dan sedang ditinjau.');
    }
}
