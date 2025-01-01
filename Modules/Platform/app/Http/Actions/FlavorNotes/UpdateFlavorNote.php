<?php

namespace Modules\Platform\Http\Actions\FlavorNotes;

use Illuminate\Http\Request;
use Modules\Offering\Models\FlavorNote;

class UpdateFlavorNote
{
    public function execute(Request $request, FlavorNote $flavorNote)
    {
        $flavorNote->fill([
            'name' => $request->name,
            'slug' => $request->slug
        ])->save();
    }
}