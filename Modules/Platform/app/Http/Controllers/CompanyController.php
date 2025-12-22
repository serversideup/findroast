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
        $companies = ( new IndexCompanies( $request ) )->execute([
            'offeringImportMap',
        ]);

        return Inertia::render('Platform/Companies/Index', [
            'companies' => $companies,
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

        return redirect()->route('platform.companies.index');
    }
}
