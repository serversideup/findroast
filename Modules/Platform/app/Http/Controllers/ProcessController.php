<?php

namespace Modules\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Offering\Models\Process;
use Modules\Platform\Http\Actions\Processes\UpdateProcess;
use Modules\Platform\Http\Actions\Processes\DeleteProcess;
use Modules\Platform\Http\Actions\Processes\MigrateProcess;

class ProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request )
    {
        $processes = Process::orderBy('name', 'asc')->paginate(25)->withQueryString();

        $migratedProcesses = Process::onlyTrashed()
            ->whereNotNull('migrated_to_id')
            ->with('migratedTo')
            ->get();

        return Inertia::render('Platform/Processes/Index', [
            'processes' => $processes,
            'migratedProcesses' => $migratedProcesses
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
     * Migrate a process to another process.
     */
    public function migrate( Request $request, Process $process )
    {
        $request->validate([
            'target_process_id' => 'required|exists:processes,id'
        ]);

        $targetProcess = Process::findOrFail($request->target_process_id);

        ( new MigrateProcess() )->execute( $process, $targetProcess );

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
