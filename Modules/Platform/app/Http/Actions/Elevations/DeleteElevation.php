<?php

namespace Modules\Platform\Http\Actions\Elevations;

use Modules\Offering\Models\Elevation;
use Illuminate\Support\Facades\DB;

class DeleteElevation
{
    public static function execute(Elevation $elevation)
    {
        DB::table('roast_elevations')
            ->where('elevation_id', $elevation->id)
            ->delete();

        $elevation->delete();
    }
}