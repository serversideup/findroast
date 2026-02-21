<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Platform\Models\ChangelogEntry;

class ChangelogController extends Controller
{
    public function index()
    {
        $entries = ChangelogEntry::published()
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Changelog', [
            'entries' => $entries,
        ]);
    }
}
