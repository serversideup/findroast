<?php

namespace Modules\Company\Http\Actions;

use Modules\Company\Http\Requests\StoreCafeRequest;
use Modules\Company\Models\Cafe;
use Modules\Company\Models\Company;

class StoreCafe
{
    public function execute( StoreCafeRequest $request, Company $company )
    {
        $cafe = $this->persistCafe( $request, $company );

        $this->setPrimaryImage( $request, $cafe );

        $this->bindBrewMethods( $request, $cafe );
        $this->bindDrinkOptions( $request, $cafe );
        $this->bindAmenities( $request, $cafe );
    }

    private function persistCafe( $request, $company )
    {
        $cafe = Cafe::create([
            'company_id' => $company->id,
            'name' => $request->input('name'),
            'status' => $request->input('status'),
            'google_place_id' => $request->input('google_place_id'),
            'yelp_url' => $request->input('yelp_url'),
            'description' => $request->input('description'),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'province' => $request->input('province'),
            'territory' => $request->input('territory'),
            'country' => $request->input('country'),
            'zip' => $request->input('zip'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
        ]);

        return $cafe;
    }

    private function setPrimaryImage( $request, $cafe )
    {
        if( $request->hasFile('primary_image') ) {
            $path = $request
                ->file('primary_image')
                ->storePubliclyAs(
                    'companies/'.$cafe->company->slug.'/cafes/'.$cafe->slug.'/images', 
                    $request->file('primary_image')->getClientOriginalName(),
                    'public'
                );

            $cafe->update([
                'primary_image' => '/storage/'.$path,
            ]);
        }
    }

    private function bindBrewMethods( $request, $cafe )
    {
        $cafe->brewMethods()->sync( $request->input('brew_methods') );
    }

    private function bindDrinkOptions( $request, $cafe )
    {
        $cafe->drinkOptions()->sync( $request->input('drink_options') );
    }

    private function bindAmenities( $request, $cafe )
    {
        $cafe->amenities()->sync( $request->input('amenities') );
    }
}