<?php

namespace Modules\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Offering\Models\Variety;
use Modules\Platform\Http\Actions\Varieties\UpdateVariety;

class VarietyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request )
    {
        $varieties = Variety::all();

        return Inertia::render('Platform/Varieties/Index', [
            'varieties' => $varieties
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( Request $request, Variety $variety )
    {
        return Inertia::render('Platform/Varieties/Edit', [
            'variety' => $variety
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
}
