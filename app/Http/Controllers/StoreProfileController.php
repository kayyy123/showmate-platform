<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class StoreProfileController extends Controller
{
    public function edit(Request $request): Response
    {
        $user = $request->user();

        return Inertia::render('Profile/StoreProfile', [
            'store' => [
                'store_name' => $user->store_name,
                'store_description' => $user->store_description,
                'whatsapp_number' => $user->whatsapp_number,
                'instagram_url' => $user->instagram_url,
                'tiktok_url' => $user->tiktok_url,
                'shopee_url' => $user->shopee_url,
                'tokopedia_url' => $user->tokopedia_url,
                'store_logo' => $user->store_logo,
                'store_logo_url' => $user->store_logo ? Storage::url($user->store_logo) : null,
            ],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'store_name' => 'nullable|string|max:255',
            'store_description' => 'nullable|string|max:2000',
            'whatsapp_number' => 'nullable|string|max:20',
            'instagram_url' => 'nullable|string|max:500',
            'tiktok_url' => 'nullable|string|max:500',
            'shopee_url' => 'nullable|string|max:500',
            'tokopedia_url' => 'nullable|string|max:500',
            'store_logo' => 'nullable|image|max:2048',
        ]);

        $user = $request->user();

        if ($request->hasFile('store_logo')) {
            if ($user->store_logo) {
                Storage::disk('public')->delete($user->store_logo);
            }

            $validated['store_logo'] = $request->file('store_logo')->store('store-logos', 'public');
        } else {
            unset($validated['store_logo']);
        }

        $user->update($validated);

        return Redirect::route('store-profile.edit')->with('success', 'Profil toko berhasil diperbarui');
    }
}
