<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class ApiDocsController extends Controller
{
    public function index()
    {
        return Inertia::render('ApiDocs');
    }
}
