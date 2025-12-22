<?php

namespace Modules\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Offering\Models\Elevation;
use Modules\Platform\Http\Actions\Elevations\UpdateElevation;
use Modules\Platform\Http\Actions\Elevations\DeleteElevation;

class ElevationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request )
    {
        $elevations = Elevation::all();

        return Inertia::render('Platform/Elevations/Index', [
            'elevations' => $elevations
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( Request $request, Elevation $elevation )
    {
        ( new UpdateElevation() )->execute( $request, $elevation );

        return redirect()->route('platform.elevations.index');
    }

    /**
     * Delete the specified resource in storage.
     */
    public function delete( Request $request, Elevation $elevation )
    {
        ( new DeleteElevation() )->execute( $elevation );

        return redirect()->route('platform.elevations.index');
    }
}
