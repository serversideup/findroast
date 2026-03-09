<?php

namespace Modules\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Offering\Models\Variety;
use Modules\Platform\Http\Actions\Varieties\UpdateVariety;
use Modules\Platform\Http\Actions\Varieties\DeleteVariety;
use Modules\Platform\Http\Actions\Varieties\MigrateVariety;

class VarietyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request )
    {
        $varieties = Variety::orderBy('name', 'asc')->paginate(25)->withQueryString();

        $migratedVarieties = Variety::onlyTrashed()
            ->whereNotNull('migrated_to_id')
            ->with('migratedTo')
            ->get();

        return Inertia::render('Platform/Varieties/Index', [
            'varieties' => $varieties,
            'migratedVarieties' => $migratedVarieties
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( Request $request, Variety $variety )
    {
        ( new UpdateVariety() )->execute( $request, $variety );

        return redirect()->route('platform.varieties.index');
    }

    /**
     * Migrate a variety to another variety.
     */
    public function migrate( Request $request, Variety $variety )
    {
        $request->validate([
            'target_variety_id' => 'required|exists:varieties,id'
        ]);

        $targetVariety = Variety::findOrFail($request->target_variety_id);

        ( new MigrateVariety() )->execute( $variety, $targetVariety );

        return redirect()->route('platform.varieties.index');
    }

    /**
     * Delete the specified resource in storage.
     */
    public function delete( Request $request, Variety $variety )
    {
        ( new DeleteVariety() )->execute( $variety );

        return redirect()->route('platform.varieties.index');
    }
}
