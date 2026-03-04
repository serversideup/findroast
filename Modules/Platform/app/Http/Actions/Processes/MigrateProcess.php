<?php

namespace Modules\Platform\Http\Actions\Processes;

use Modules\Offering\Models\Process;
use Illuminate\Support\Facades\DB;

class MigrateProcess
{
    public function execute(Process $fromProcess, Process $toProcess)
    {
        DB::transaction(function () use ($fromProcess, $toProcess) {
            // Get all roast IDs associated with the "from" process
            $roastIds = DB::table('roast_processes')
                ->where('process_id', $fromProcess->id)
                ->pluck('roast_id')
                ->toArray();

            // Remove the old associations
            DB::table('roast_processes')
                ->where('process_id', $fromProcess->id)
                ->delete();

            // Add associations to the target process
            foreach ($roastIds as $roastId) {
                DB::table('roast_processes')->updateOrInsert([
                    'roast_id' => $roastId,
                    'process_id' => $toProcess->id
                ]);
            }

            // Mark the migration
            $fromProcess->migrated_to_id = $toProcess->id;
            $fromProcess->save();

            // Soft delete the "from" process
            $fromProcess->delete();
        });
    }
}
