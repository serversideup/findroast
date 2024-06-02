<?php

namespace Modules\Company\Http\Actions;

use Illuminate\Http\Request;
use Modules\Company\Models\Cafe;

class UpdateCafe
{
    public function execute(Request $request, Cafe $cafe)
    {
        $this->updateCafe($request, $cafe);
        $this->setPrimaryImage($request, $cafe);
    }

    private function updateCafe($request, $cafe)
    {
        $cafe->fill([
            'name' => $request->input('name'),
            'status' => $request->input('status'),
            'description' => $request->input('description'),
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
        ])->save();

        $this->bindBrewMethods( $request, $cafe );
        $this->bindDrinkOptions( $request, $cafe );
        $this->bindAmenities( $request, $cafe );
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