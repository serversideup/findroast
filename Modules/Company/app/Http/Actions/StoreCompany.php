<?php

namespace Modules\Company\Http\Actions;

use Modules\Company\Http\Requests\StoreCompanyRequest;
use Modules\Company\Models\Company;
use Modules\Offering\Models\OfferingImportMap;

class StoreCompany
{
    public function execute(StoreCompanyRequest $request)
    {
        $company = $this->persistCompany($request);

        $this->setLogo( $request, $company );
        $this->setHeaderImage( $request, $company );
        $this->addOfferingImportMap( $request, $company );
    }

    private function persistCompany( $request )
    {
        $company = Company::create([
            'name' => $request->input('name'),
            'status' => $request->input('status'),
            'roaster' => $request->input('roaster'),
            'subscription' => $request->input('subscription'),
            'description' => $request->input('description'),
            'website' => $request->input('website'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'province' => $request->input('province'),
            'territory' => $request->input('territory'),
            'country' => $request->input('country'),
            'default_currency' => $request->input('default_currency', 'USD'),
            'facebook_url' => $request->input('facebook_url'),
            'twitter_url' => $request->input('twitter_url'),
            'instagram_url' => $request->input('instagram_url'),
            'added_by' => $request->user()->id,
        ]);

        return $company;
    }

    private function setLogo( $request, $company )
    {
        if( $request->hasFile('logo') ) {
            $path = $request
                ->file('logo')
                ->storePubliclyAs(
                    'companies/'.$company->slug.'/logos', 
                    $request->file('logo')->getClientOriginalName(),
                    'public'
                );

            $company->update([
                'logo' => '/storage/'.$path,
            ]);
        }
    }

    private function setHeaderImage( $request, $company )
    {
        if( $request->hasFile('header_image') ) {
            $path = $request
                ->file('header_image')
                ->storePubliclyAs(
                    'companies/'.$company->slug.'/header-images', 
                    $request->file('header_image')->getClientOriginalName(),
                    'public'
                );

            $company->update([
                'logo' => '/storage/'.$path,
            ]);
        }
    }

    private function addOfferingImportMap( $request, $company )
    {
        OfferingImportMap::create([
            'company_id' => $company->id,
            'enabled' => $request->input('offerings.enabled'),
            'collection_url' => $request->input('offerings.collection_url'),
            'container_selector' => $request->input('offerings.container_selector'),
            'product_list_item_selector' => $request->input('offerings.product_list_item_selector'),
            'product_selector' => $request->input('offerings.product_selector'),
            'is_shopify' => $request->input('offerings.is_shopify', false),
            'shopify_product_types' => $request->input('offerings.shopify_product_types'),
            'shopify_tags_include' => $request->input('offerings.shopify_tags_include'),
            'shopify_tags_exclude' => $request->input('offerings.shopify_tags_exclude'),
            'shopify_collection_url' => $request->input('offerings.shopify_collection_url'),
            'last_synced_at' => null,
        ]);
    }
}