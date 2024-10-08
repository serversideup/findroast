<?php

namespace Modules\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlatformController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request )
    {
        return Inertia::render('Platform/Index');
    }
}
