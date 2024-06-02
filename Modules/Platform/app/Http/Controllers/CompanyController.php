<?php

namespace Modules\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Company\Http\Actions\IndexCompanies;
use Modules\Company\Http\Actions\ShowCompany;
use Modules\Company\Http\Actions\StoreCompany;
use Modules\Company\Http\Actions\UpdateCompany;
use Modules\Company\Http\Requests\StoreCompanyRequest;
use Modules\Company\Models\Company;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request )
    {
        $companies = ( new IndexCompanies( $request ) )->execute();

        return Inertia::render('Platform/Companies/Index', [
            'companies' => $companies,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create( Request $request )
    {
        return Inertia::render('Platform/Companies/Create');
    }

    public function edit( Request $request, Company $company )
    {
        $company = ( new ShowCompany( $company ) )
            ->execute(true);

        return Inertia::render('Platform/Companies/Edit', [
            'company' => $company,
        ]);
    }

    public function show( Request $request, Company $company )
    {
        $company = ( new ShowCompany( $company ) )
            ->execute(true);

        return Inertia::render('Platform/Companies/Show', [
            'company' => $company,
        ]);
    }

    public function store( StoreCompanyRequest $request )
    {
        ( new StoreCompany() )->execute( $request );

        return redirect()->route('platform.companies.index');
    }

    public function update( Request $request, Company $company )
    {
        ( new UpdateCompany() )->execute( $request, $company );

        return redirect()->route('platform.companies.show', [ 
            'company' => $company
        ]);
    }
}
