<?php

namespace Modules\Platform\Http\Actions\FlavorNotes;

use Modules\Offering\Models\FlavorNote;
use Illuminate\Support\Facades\DB;

class DeleteFlavorNote
{
    public static function execute(FlavorNote $flavorNote)
    {
        DB::table('roast_flavor_notes')
            ->where('flavor_note_id', $flavorNote->id)
            ->delete();

        $flavorNote->delete();
    }
}