<?php

namespace Modules\Offering\Http\Actions\Roasts;

use Modules\Offering\Models\Roast;
use Modules\Offering\Models\Variety;
use Illuminate\Support\Str;

class SyncVarieties
{
    public static function execute(Roast $roast, array $varieties)
    {
        foreach( $varieties as $variety ){
            $varietyRecord = Variety::firstOrCreate([
                'slug' => Str::slug($variety)
            ], [
                'name' => $variety
            ]);

            $roast->varieties()->syncWithoutDetaching( $varietyRecord->id );
        }
    }
}