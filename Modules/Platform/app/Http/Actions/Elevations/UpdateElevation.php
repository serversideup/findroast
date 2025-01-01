<?php

namespace Modules\Platform\Http\Actions\Elevations;

use Illuminate\Http\Request;
use Modules\Offering\Models\Elevation;

class UpdateElevation
{
    public function execute(Request $request, Elevation $elevation)
    {
        $elevation->fill([
            'name' => $request->name,
        ])->save();
    }
}