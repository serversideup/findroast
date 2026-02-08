<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Company\Models\Cafe;
use Modules\Platform\Models\Amenity;
use Modules\Platform\Models\BrewMethod;
use Modules\Platform\Models\DrinkOption;

class MapController extends Controller
{
    public function index(Request $request)
    {
        $cafes = Cafe::where('status', 'active')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with(['company', 'amenities', 'brewMethods', 'drinkOptions'])
            ->orderBy('name')
            ->get();

        $brewMethods = BrewMethod::orderBy('name')->get();
        $amenities = Amenity::orderBy('name')->get();
        $drinkOptions = DrinkOption::orderBy('name')->get();

        return Inertia::render('Map/Index', [
            'cafes' => $cafes,
            'brewMethods' => $brewMethods,
            'amenities' => $amenities,
            'drinkOptions' => $drinkOptions,
        ]);
    }
}
