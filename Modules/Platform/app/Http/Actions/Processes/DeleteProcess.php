<?php

namespace Modules\Platform\Http\Actions\Processes;

use Modules\Offering\Models\Process;
use Illuminate\Support\Facades\DB;

class DeleteProcess
{
    public static function execute(Process $process)
    {
        DB::table('roast_processes')
            ->where('process_id', $process->id)
            ->delete();

        $process->delete();
    }
}