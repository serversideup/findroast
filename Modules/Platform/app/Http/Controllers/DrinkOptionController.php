<?php

namespace Modules\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Platform\Http\Actions\DrinkOptions\StoreDrinkOption;
use Modules\Platform\Http\Actions\DrinkOptions\UpdateDrinkOption;
use Modules\Platform\Http\Requests\StoreDrinkOptionRequest;
use Modules\Platform\Models\DrinkOption;

class DrinkOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request )
    {
        $drinkOptions = DrinkOption::all();

        return Inertia::render('Platform/DrinkOptions/Index', [
            'drinkOptions' => $drinkOptions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create( Request $request )
    {
        return Inertia::render('Platform/DrinkOptions/Create');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( Request $request, DrinkOption $drinkOption )
    {
        return Inertia::render('Platform/DrinkOptions/Edit', [
            'drinkOption' => $drinkOption
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store( StoreDrinkOptionRequest $request )
    {
        ( new StoreDrinkOption() )->execute( $request );

        return redirect()->route('platform.drink-options.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( Request $request, DrinkOption $drinkOption)
    {
        ( new UpdateDrinkOption() )->execute( $request, $drinkOption );

        return redirect()->route('platform.drink-options.index');
    }
}
