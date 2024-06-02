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
use Modules\Company\Models\Company;
use Modules\Platform\Models\Amenity;
use Modules\Platform\Models\BrewMethod;
use Modules\Platform\Models\DrinkOption;

class CafesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request, Company $company )
    {
        $cafes = ( new IndexCafes( 
            $request, 
            $company 
        ) )->execute();

        return Inertia::render('Platform/Cafes/Index', [
            'company' => $company,
            'cafes' => $cafes,
        ]);
    }

    public function create( Request $request, Company $company )
    {
        $brewMethods = BrewMethod::all();
        $drinkOptions = DrinkOption::all();
        $amenities = Amenity::all();

        return Inertia::render('Platform/Cafes/Create', [
            'company' => $company,
            'brewMethods' => $brewMethods,
            'drinkOptions' => $drinkOptions,
            'amenities' => $amenities,
        ]);
    }

    public function edit( Request $request, Company $company, Cafe $cafe )
    {
        $cafe = ( new ShowCafe( 
            $cafe 
        ) )->execute();

        $brewMethods = BrewMethod::all();
        $drinkOptions = DrinkOption::all();
        $amenities = Amenity::all();

        return Inertia::render('Platform/Cafes/Edit', [
            'company' => $company,
            'cafe' => $cafe,
            'brewMethods' => $brewMethods,
            'drinkOptions' => $drinkOptions,
            'amenities' => $amenities,
        ]);
    }

    public function show( Request $request, Company $company, Cafe $cafe )
    {
        $cafe = ( new ShowCafe( 
            $cafe 
        ) )->execute();

        return Inertia::render('Platform/Cafes/Show', [
            'company' => $company,
            'cafe' => $cafe,
        ]);
    }

    public function store( StoreCafeRequest $request, Company $company )
    {
        ( new StoreCafe() )->execute( $request, $company );

        return redirect()->route('platform.companies.cafes.index', $company);
    }

    public function update( Request $request, Company $company, Cafe $cafe )
    {
        ( new UpdateCafe() )->execute( $request, $cafe );

        return redirect()->route('platform.companies.cafes.show', [
            'company' => $company,
            'cafe' => $cafe
        ]);
    }
}