<?php

namespace Modules\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Offering\Models\Process;
use Modules\Platform\Http\Actions\Processes\UpdateProcess;
use Modules\Platform\Http\Actions\Processes\DeleteProcess;

class ProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request )
    {
        $processes = Process::all();

        return Inertia::render('Platform/Processes/Index', [
            'processes' => $processes
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( Request $request, Process $process )
    {
        ( new UpdateProcess() )->execute( $request, $process );

        return redirect()->route('platform.processes.index');
    }

    /**
     * Delete the specified resource in storage.
     */
    public function delete( Request $request, Process $process )
    {
        ( new DeleteProcess() )->execute( $process );

        return redirect()->route('platform.processes.index');
    }
}
