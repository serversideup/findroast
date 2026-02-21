<?php

namespace Modules\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Platform\Models\ChangelogEntry;

class ChangelogController extends Controller
{
    public function index()
    {
        $entries = ChangelogEntry::orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Platform/Changelog/Index', [
            'entries' => $entries,
        ]);
    }

    public function create()
    {
        return Inertia::render('Platform/Changelog/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'version' => 'required|string|max:50',
            'date' => 'required|date',
            'description' => 'required|string',
        ]);

        ChangelogEntry::create($validated);

        return redirect()->route('platform.changelog.index')
            ->with('success', 'Changelog entry created.');
    }

    public function edit(ChangelogEntry $changelog)
    {
        return Inertia::render('Platform/Changelog/Edit', [
            'entry' => $changelog,
        ]);
    }

    public function update(Request $request, ChangelogEntry $changelog)
    {
        $validated = $request->validate([
            'version' => 'required|string|max:50',
            'date' => 'required|date',
            'description' => 'required|string',
        ]);

        $changelog->update($validated);

        return redirect()->route('platform.changelog.index')
            ->with('success', 'Changelog entry updated.');
    }

    public function publish(ChangelogEntry $changelog)
    {
        $changelog->update([
            'published_at' => $changelog->isPublished() ? null : now(),
        ]);

        return redirect()->route('platform.changelog.index')
            ->with('success', $changelog->isPublished() ? 'Entry unpublished.' : 'Entry published.');
    }

    public function destroy(ChangelogEntry $changelog)
    {
        $changelog->delete();

        return redirect()->route('platform.changelog.index')
            ->with('success', 'Changelog entry deleted.');
    }
}
