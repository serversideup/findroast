<?php

namespace Modules\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Platform\Http\Actions\BrewMethods\StoreBrewMethod;
use Modules\Platform\Http\Actions\BrewMethods\UpdateBrewMethod;
use Modules\Platform\Http\Requests\StoreBrewMethodRequest;
use Modules\Platform\Models\BrewMethod;

class BrewMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request )
    {
        $brewMethods = BrewMethod::all();

        return Inertia::render('Platform/BrewMethods/Index', [
            'brewMethods' => $brewMethods
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create( Request $request )
    {
        return Inertia::render('Platform/BrewMethods/Create');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( Request $request, BrewMethod $brewMethod )
    {
        return Inertia::render('Platform/BrewMethods/Edit', [
            'brewMethod' => $brewMethod
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store( StoreBrewMethodRequest $request )
    {
        ( new StoreBrewMethod() )->execute( $request );

        return redirect()->route('platform.brew-methods.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( Request $request, BrewMethod $brewMethod )
    {
        ( new UpdateBrewMethod() )->execute( $request, $brewMethod );

        return redirect()->route('platform.brew-methods.index');
    }
}
