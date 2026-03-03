<?php

namespace Modules\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Offering\Http\Actions\Roasts\FetchShopifyProducts;
use Modules\Company\Models\Company;
use Modules\Offering\Models\OfferingImportMap;
use Modules\Offering\Models\InvalidRoastUrl;
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

        if( $request->input('company_id') ){
            $company = Company::find($request->input('company_id'));
        }else{
            $company = new Company();
            $company->website = $request->input('website');
        }

        $products = ( new FetchShopifyProducts($company, $offeringImportMap) )
            ->execute();

        return response()->json($products);
    }

    public function markInvalid( Request $request )
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'urls' => 'required|array',
            'urls.*.url' => 'required|string',
            'urls.*.reason' => 'nullable|string',
        ]);

        foreach ($validated['urls'] as $urlData) {
            InvalidRoastUrl::updateOrCreate(
                [
                    'company_id' => $validated['company_id'],
                    'url' => $urlData['url']
                ],
                [
                    'reason' => $urlData['reason'] ?? null
                ]
            );
        }

        return response()->json([
            'message' => 'URLs marked as invalid successfully',
            'count' => count($validated['urls'])
        ]);
    }
}