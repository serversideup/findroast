<?php

namespace Modules\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Offering\Http\Actions\Roasts\IndexRoasts;

class RoastController extends Controller
{
    public function index( Request $request )
    {
        return Inertia::render('Platform/Roasts/Index', [
            'roasts' => Inertia::lazy(fn() => ( new IndexRoasts($request) )->execute()),
        ]);
    }
}