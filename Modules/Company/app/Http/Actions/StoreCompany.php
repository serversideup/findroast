<?php

namespace Modules\Company\Http\Actions;

use Modules\Company\Http\Requests\StoreCompanyRequest;
use Modules\Company\Models\Company;

class StoreCompany
{
    public function execute(StoreCompanyRequest $request)
    {
        $company = $this->persistCompany($request);

        $this->setLogo( $request, $company );
        $this->setHeaderImage( $request, $company );
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
}