<?php

namespace Modules\Platform\Http\Actions\Processes;

use Illuminate\Http\Request;
use Modules\Offering\Models\Process;

class UpdateProcess
{
    public function execute(Request $request, Process $process)
    {
        $process->fill([
            'name' => $request->name,
            'slug' => $request->slug
        ])->save();
    }
}