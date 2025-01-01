<?php

namespace Modules\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Company\Models\Company;
use Modules\Offering\Models\OfferingImportMap;

class OfferingsController extends Controller
{
    public function sync( Request $request, Company $company ): RedirectResponse
    {
        $importMap = OfferingImportMap::where('company_id', $company->id)
            ->first();

        if( $importMap ){
            $jobClass = 'Modules\Offering\Jobs\\'.$importMap->collection_job_class;
            $jobClass::dispatch($company);

            $importMap->update([
                'last_synced_at' => Carbon::now()
            ]);
        }

        return redirect()->back();
    }
}