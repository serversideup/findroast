<?php

namespace Modules\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Platform\Http\Actions\Amenities\StoreAmenity;
use Modules\Platform\Http\Actions\Amenities\UpdateAmenity;
use Modules\Platform\Http\Requests\StoreAmenityRequest;
use Modules\Platform\Models\Amenity;

class AmenityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request )
    {
        $amenities = Amenity::all();

        return Inertia::render('Platform/Amenities/Index', [
            'amenities' => $amenities
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create( Request $request )
    {
        return Inertia::render('Platform/Amenities/Create');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( Request $request, Amenity $amenity )
    {
        return Inertia::render('Platform/Amenities/Edit', [
            'amenity' => $amenity
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store( StoreAmenityRequest $request )
    {
        ( new StoreAmenity() )->execute( $request );

        return redirect()->route('platform.amenities.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( Request $request, Amenity $amenity )
    {
        ( new UpdateAmenity() )->execute( $request, $amenity );

        return redirect()->route('platform.amenities.index');
    }
}
