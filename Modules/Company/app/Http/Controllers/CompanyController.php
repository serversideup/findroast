<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Company\Models\Company;
use Modules\Company\Http\Actions\IndexCompanies;
use Modules\Company\Http\Actions\ShowCompany;
class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request )
    {
        $companies = ( new IndexCompanies( $request) )->execute();

        return Inertia::render('Companies/Index', [
            'companies' => fn() => $companies
        ]);
    }

    /**
     * Show the specified resource.
     */
    public function show( $company )
    {
        $company = Company::where('slug', $company)
            ->with('cafes')
            ->with([
                'roasts' => function($query) {
                    $query->with('flavorNotes');
                    $query->with('varieties');
                    $query->with('processes');
                    $query->with('countries');
                    $query->with('elevations');
                    $query->where('in_stock', 1);
                }
            ])
            ->first();

        return Inertia::render('Companies/Show', [
            'company' => $company
        ]);
    }
}
