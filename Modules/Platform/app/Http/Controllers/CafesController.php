<?php

namespace Modules\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Company\Http\Requests\StoreCafeRequest;
use Modules\Company\Http\Actions\IndexCafes;
use Modules\Company\Http\Actions\ShowCafe;
use Modules\Company\Http\Actions\StoreCafe;
use Modules\Company\Http\Actions\UpdateCafe;
use Modules\Company\Models\Cafe;
use Modules\Platform\Models\Amenity;
use Modules\Platform\Models\BrewMethod;
use Modules\Platform\Models\DrinkOption;

class CafesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request )
    {
        $cafes = ( new IndexCafes( 
            $request, 
        ) )->execute();

        return Inertia::render('Platform/Cafes/Index', [
            'cafes' => $cafes,
        ]);
    }

    public function create( Request $request )
    {
        $companies = \Modules\Company\Models\Company::orderBy('name')->get();
        $brewMethods = BrewMethod::all();
        $drinkOptions = DrinkOption::all();
        $amenities = Amenity::all();

        return Inertia::render('Platform/Cafes/Create', [
            'companies' => $companies,
            'brewMethods' => $brewMethods,
            'drinkOptions' => $drinkOptions,
            'amenities' => $amenities,
        ]);
    }

    public function edit( Request $request, Cafe $cafe )
    {
        $cafe = ( new ShowCafe(
            $cafe
        ) )->execute();

        $companies = \Modules\Company\Models\Company::orderBy('name')->get();
        $brewMethods = BrewMethod::all();
        $drinkOptions = DrinkOption::all();
        $amenities = Amenity::all();

        return Inertia::render('Platform/Cafes/Edit', [
            'cafe' => $cafe,
            'companies' => $companies,
            'brewMethods' => $brewMethods,
            'drinkOptions' => $drinkOptions,
            'amenities' => $amenities,
        ]);
    }

    public function show( Request $request, Cafe $cafe )
    {
        $cafe = ( new ShowCafe( 
            $cafe 
        ) )->execute();

        return Inertia::render('Platform/Cafes/Show', [
            'cafe' => $cafe,
        ]);
    }

    public function store( StoreCafeRequest $request )
    {
        ( new StoreCafe() )->execute( $request );

        return redirect()->route('platform.cafes.index');
    }

    public function update( Request $request, Cafe $cafe )
    {
        ( new UpdateCafe() )->execute( $request, $cafe );

        return redirect()->route('platform.cafes.show', [
            'cafe' => $cafe
        ]);
    }

    public function destroy( Request $request, Cafe $cafe )
    {
        $cafe->delete();

        return redirect()->route('platform.cafes.index');
    }
}