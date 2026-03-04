<?php

namespace Modules\Offering\Http\Actions\Roasts;

use Modules\Offering\Models\Roast;
use Modules\Offering\Models\Process;
use Illuminate\Support\Str;

class SyncProcesses
{
    public static function execute(Roast $roast, array $processes)
    {
        foreach( $processes as $process ){
            $slug = Str::slug($process);

            // Check if this process was previously migrated
            $migratedProcess = Process::withTrashed()
                ->where('slug', $slug)
                ->whereNotNull('migrated_to_id')
                ->first();

            if ($migratedProcess && $migratedProcess->migratedTo) {
                // Use the target process instead
                $processRecord = $migratedProcess->migratedTo;
            } else {
                // Create or find the process as usual
                $processRecord = Process::firstOrCreate([
                    'slug' => $slug
                ], [
                    'name' => $process
                ]);
            }

            $roast->processes()->syncWithoutDetaching( $processRecord->id );
        }
    }
}