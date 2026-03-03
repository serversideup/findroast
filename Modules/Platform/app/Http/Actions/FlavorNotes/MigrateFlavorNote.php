<?php

namespace Modules\Platform\Http\Actions\FlavorNotes;

use Modules\Offering\Models\FlavorNote;
use Illuminate\Support\Facades\DB;

class MigrateFlavorNote
{
    public function execute(FlavorNote $fromFlavorNote, FlavorNote $toFlavorNote)
    {
        DB::transaction(function () use ($fromFlavorNote, $toFlavorNote) {
            // Get all roast IDs associated with the "from" flavor note
            $roastIds = DB::table('roast_flavor_notes')
                ->where('flavor_note_id', $fromFlavorNote->id)
                ->pluck('roast_id')
                ->toArray();

            // Remove the old associations
            DB::table('roast_flavor_notes')
                ->where('flavor_note_id', $fromFlavorNote->id)
                ->delete();

            // Add associations to the target flavor note
            foreach ($roastIds as $roastId) {
                DB::table('roast_flavor_notes')->updateOrInsert([
                    'roast_id' => $roastId,
                    'flavor_note_id' => $toFlavorNote->id
                ]);
            }

            // Mark the migration
            $fromFlavorNote->migrated_to_id = $toFlavorNote->id;
            $fromFlavorNote->save();

            // Soft delete the "from" flavor note
            $fromFlavorNote->delete();
        });
    }
}
