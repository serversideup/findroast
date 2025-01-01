<?php

namespace Modules\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Offering\Models\Country;
use Modules\Platform\Http\Actions\Countries\UpdateCountry;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request )
    {
        $countries = Country::orderBy('name', 'DESC')->get();

        return Inertia::render('Platform/Countries/Index', [
            'countries' => $countries
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( Request $request, Country $country )
    {
        return Inertia::render('Platform/Countries/Edit', [
            'country' => $country
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( Request $request, Country $country )
    {
        ( new UpdateCountry() )->execute( $request, $country );

        return redirect()->route('platform.countries.index');
    }
}
