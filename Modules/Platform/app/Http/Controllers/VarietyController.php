<?php

namespace Modules\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Offering\Models\Variety;
use Modules\Platform\Http\Actions\Varieties\UpdateVariety;
use Modules\Platform\Http\Actions\Varieties\DeleteVariety;

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
     * Update the specified resource in storage.
     */
    public function update( Request $request, Variety $variety )
    {
        ( new UpdateVariety() )->execute( $request, $variety );

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
