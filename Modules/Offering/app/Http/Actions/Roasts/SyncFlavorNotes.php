<?php

namespace Modules\Offering\Http\Actions\Roasts;

use Modules\Offering\Models\Roast;
use Modules\Offering\Models\FlavorNote;
use Illuminate\Support\Str;

class SyncFlavorNotes
{
    public static function execute(Roast $roast, array $flavorNotes)
    {
        foreach( $flavorNotes as $flavorNote ){
            $slug = Str::slug($flavorNote);

            // Check if this flavor note was previously migrated
            $migratedFlavorNote = FlavorNote::withTrashed()
                ->where('slug', $slug)
                ->whereNotNull('migrated_to_id')
                ->first();

            if ($migratedFlavorNote && $migratedFlavorNote->migratedTo) {
                // Use the canonical flavor note instead
                $flavorNoteRecord = $migratedFlavorNote->migratedTo;
            } else {
                // Create or find the flavor note as usual
                $flavorNoteRecord = FlavorNote::firstOrCreate([
                    'slug' => $slug
                ], [
                    'name' => $flavorNote
                ]);
            }

            $roast->flavorNotes()->syncWithoutDetaching( $flavorNoteRecord->id );
        }
    }
}