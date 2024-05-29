<?php

namespace Modules\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\BrewMethod\Models\BrewMethod;

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
}
