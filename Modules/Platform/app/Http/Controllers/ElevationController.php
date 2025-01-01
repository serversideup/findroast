<?php

namespace Modules\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Offering\Models\Elevation;
use Modules\Platform\Http\Actions\Elevations\UpdateElevation;

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
     * Show the form for editing the specified resource.
     */
    public function edit( Request $request, Elevation $elevation )
    {
        return Inertia::render('Platform/Elevations/Edit', [
            'elevation' => $elevation
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
}
