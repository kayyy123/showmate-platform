<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LinkController extends Controller
{
    public function index(Request $request): Response
    {
        $links = $request->user()->links()
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($link) => [
                ...$link->toArray(),
            ]);

        return Inertia::render('Links/Index', [
            'links' => $links,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:2000',
            'icon' => 'nullable|string|max:50',
        ]);

        $maxSort = $request->user()->links()->max('sort_order') ?? 0;

        Link::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'url' => $validated['url'],
            'icon' => $validated['icon'] ?? null,
            'sort_order' => $maxSort + 1,
            'is_active' => true,
        ]);

        return redirect()->route('links.index')->with('success', 'Tautan berhasil ditambahkan');
    }

    public function update(Request $request, Link $link): RedirectResponse
    {
        if ($link->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:2000',
            'icon' => 'nullable|string|max:50',
        ]);

        $link->update($validated);

        return redirect()->route('links.index')->with('success', 'Tautan berhasil diperbarui');
    }

    public function destroy(Request $request, Link $link): RedirectResponse
    {
        if ($link->user_id !== $request->user()->id) {
            abort(403);
        }

        $link->delete();

        return redirect()->route('links.index')->with('success', 'Tautan berhasil dihapus');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'links' => 'required|array',
            'links.*.id' => 'required|exists:links,id',
            'links.*.sort_order' => 'required|integer|min:0',
        ]);

        $userLinkIds = $request->user()->links()->pluck('id')->toArray();

        foreach ($validated['links'] as $item) {
            if (in_array($item['id'], $userLinkIds)) {
                Link::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
            }
        }

        return redirect()->route('links.index')->with('success', 'Urutan tautan berhasil diperbarui');
    }

    public function toggleActive(Request $request, Link $link): RedirectResponse
    {
        if ($link->user_id !== $request->user()->id) {
            abort(403);
        }

        $link->update(['is_active' => !$link->is_active]);

        $status = $link->is_active ? 'ditampilkan' : 'disembunyikan';

        return redirect()->back()->with('success', "Tautan berhasil {$status}");
    }
}
