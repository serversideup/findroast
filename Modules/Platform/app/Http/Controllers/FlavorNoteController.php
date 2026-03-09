<?php

namespace Modules\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Offering\Models\FlavorNote;
use Modules\Platform\Http\Actions\FlavorNotes\UpdateFlavorNote;
use Modules\Platform\Http\Actions\FlavorNotes\DeleteFlavorNote;
use Modules\Platform\Http\Actions\FlavorNotes\MigrateFlavorNote;

class FlavorNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request )
    {
        $flavorNotes = FlavorNote::orderBy('name', 'asc')->paginate(25)->withQueryString();

        $migratedFlavorNotes = FlavorNote::onlyTrashed()
            ->whereNotNull('migrated_to_id')
            ->with('migratedTo')
            ->get();

        return Inertia::render('Platform/FlavorNotes/Index', [
            'flavorNotes' => $flavorNotes,
            'migratedFlavorNotes' => $migratedFlavorNotes
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
     * Migrate a flavor note to another flavor note.
     */
    public function migrate( Request $request, FlavorNote $flavorNote )
    {
        $request->validate([
            'target_flavor_note_id' => 'required|exists:flavor_notes,id'
        ]);

        $targetFlavorNote = FlavorNote::findOrFail($request->target_flavor_note_id);

        ( new MigrateFlavorNote() )->execute( $flavorNote, $targetFlavorNote );

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
