<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class TransparencyController extends Controller
{
    public function index()
    {
        return Inertia::render('Transparency');
    }
}
