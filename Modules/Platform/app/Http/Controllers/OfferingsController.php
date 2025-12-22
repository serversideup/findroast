<?php

namespace Modules\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Offering\Http\Actions\Roasts\FetchShopifyProducts;
use Modules\Company\Models\Company;
use Modules\Offering\Models\OfferingImportMap;
use Modules\Offering\Jobs\Shopify\LoadShopifyProducts;
use Modules\Offering\Jobs\Global\LoadGlobalProducts;

class OfferingsController extends Controller
{
    public function sync( Request $request, Company $company ): RedirectResponse
    {
        $importMap = OfferingImportMap::where('company_id', $company->id)
            ->first();

        if( $importMap ){

            if( $importMap->is_shopify ){
                LoadShopifyProducts::dispatch($company, $importMap);
            }else{
                LoadGlobalProducts::dispatch($company, $importMap);
            }

            $importMap->update([
                'last_synced_at' => Carbon::now()
            ]);
        }

        return redirect()->back();
    }

    public function preview( Request $request )
    {
        $offeringImportMap = new OfferingImportMap();
        $offeringImportMap->fill($request->all());

        $company = new Company();
        $company->website = $request->input('website');

        $products = ( new FetchShopifyProducts($company, $offeringImportMap) )
            ->execute();

        return response()->json($products);
    }
}