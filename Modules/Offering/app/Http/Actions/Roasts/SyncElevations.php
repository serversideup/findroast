<?php

namespace Modules\Offering\Http\Actions\Roasts;

use Modules\Offering\Models\Roast;
use Modules\Offering\Models\Elevation;

class SyncElevations
{
    public static function execute(Roast $roast, array $elevations)
    {
        foreach( $elevations as $elevation ){
            $elevationRecord = Elevation::firstOrCreate([
                'name' => $elevation
            ]);

            $roast->elevations()->syncWithoutDetaching( $elevationRecord->id );
        }
    }
}