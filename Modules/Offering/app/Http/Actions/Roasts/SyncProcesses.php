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
            $processRecord = Process::firstOrCreate([
                'slug' => Str::slug($process)
            ], [
                'name' => $process
            ]);

            $roast->processes()->syncWithoutDetaching( $processRecord->id );
        }
    }
}