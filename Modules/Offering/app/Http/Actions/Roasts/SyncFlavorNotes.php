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
            $flavorNoteRecord = FlavorNote::firstOrCreate([
                'slug' => Str::slug($flavorNote)
            ], [
                'name' => $flavorNote
            ]);

            $roast->flavorNotes()->syncWithoutDetaching( $flavorNoteRecord->id );
        }
    }
}