<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PlanController extends Controller
{
    public function upgradePage(): Response
    {
        return Inertia::render('Upgrade');
    }

    public function upgrade(Request $request): RedirectResponse
    {
        $request->user()->update(['plan' => 'pro']);

        return redirect()->route('merchant.manage')->with('success', 'Akun Pro berhasil diaktifkan! Nikmati semua fitur tanpa batas.');
    }
}
