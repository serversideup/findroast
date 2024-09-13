<?php

namespace Modules\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Company\Models\Company;
use Modules\Offering\Jobs\SyncCompanyOfferings;
use Modules\Offering\Models\OfferingImportMap;

class OfferingsController extends Controller
{
    public function sync( Request $request, Company $company ): RedirectResponse
    {
        $offringImportMap = OfferingImportMap::where('company_id', $company->id)
            ->first();

        if( $offringImportMap ){
            SyncCompanyOfferings::dispatch($offringImportMap);
        }

        return redirect()->back();
    }
}