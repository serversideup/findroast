<?php

namespace Modules\Platform\Http\Actions\Varieties;

use Modules\Offering\Models\Variety;
use Illuminate\Support\Facades\DB;

class MigrateVariety
{
    public function execute(Variety $fromVariety, Variety $toVariety)
    {
        DB::transaction(function () use ($fromVariety, $toVariety) {
            // Get all roast IDs associated with the "from" variety
            $roastIds = DB::table('roast_varieties')
                ->where('variety_id', $fromVariety->id)
                ->pluck('roast_id')
                ->toArray();

            // Remove the old associations
            DB::table('roast_varieties')
                ->where('variety_id', $fromVariety->id)
                ->delete();

            // Add associations to the target variety
            foreach ($roastIds as $roastId) {
                DB::table('roast_varieties')->updateOrInsert([
                    'roast_id' => $roastId,
                    'variety_id' => $toVariety->id
                ]);
            }

            // Mark the migration
            $fromVariety->migrated_to_id = $toVariety->id;
            $fromVariety->save();

            // Soft delete the "from" variety
            $fromVariety->delete();
        });
    }
}
