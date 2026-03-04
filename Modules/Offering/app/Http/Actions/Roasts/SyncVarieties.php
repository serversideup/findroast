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
            $slug = Str::slug($variety);

            // Check if this variety was previously migrated
            $migratedVariety = Variety::withTrashed()
                ->where('slug', $slug)
                ->whereNotNull('migrated_to_id')
                ->first();

            if ($migratedVariety && $migratedVariety->migratedTo) {
                // Use the target variety instead
                $varietyRecord = $migratedVariety->migratedTo;
            } else {
                // Create or find the variety as usual
                $varietyRecord = Variety::firstOrCreate([
                    'slug' => $slug
                ], [
                    'name' => $variety
                ]);
            }

            $roast->varieties()->syncWithoutDetaching( $varietyRecord->id );
        }
    }
}