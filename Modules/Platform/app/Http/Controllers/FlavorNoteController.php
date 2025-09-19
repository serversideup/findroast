<?php

namespace Modules\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Offering\Models\FlavorNote;
use Modules\Platform\Http\Actions\FlavorNotes\UpdateFlavorNote;
use Modules\Platform\Http\Actions\FlavorNotes\DeleteFlavorNote;

class FlavorNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request )
    {
        $flavorNotes = FlavorNote::all();

        return Inertia::render('Platform/FlavorNotes/Index', [
            'flavorNotes' => $flavorNotes
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( Request $request, FlavorNote $flavorNote )
    {
        return Inertia::render('Platform/FlavorNotes/Edit', [
            'flavorNote' => $flavorNote
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( Request $request, FlavorNote $flavorNote )
    {
        ( new UpdateFlavorNote() )->execute( $request, $flavorNote );

        return redirect()->route('platform.flavor-notes.index');
    }

    /**
     * Delete the specified resource in storage.
     */
    public function delete( Request $request, FlavorNote $flavorNote )
    {
        ( new DeleteFlavorNote() )->execute( $flavorNote );

        return redirect()->route('platform.flavor-notes.index');
    }
}
