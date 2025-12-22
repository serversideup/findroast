<?php

namespace Modules\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Offering\Models\Country;
use Modules\Platform\Http\Actions\Countries\DeleteCountry;
use Modules\Platform\Http\Actions\Countries\UpdateCountry;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request )
    {
        $countries = Country::orderBy('name', 'ASC')->get();

        return Inertia::render('Platform/Countries/Index', [
            'countries' => $countries
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

    /**
     * Delete the specified resource in storage.
     */
    public function delete( Request $request, Country $country )
    {
        ( new DeleteCountry() )->execute( $country );

        return redirect()->route('platform.countries.index');
    }
}
