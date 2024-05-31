<?php

namespace Modules\Platform\Http\Actions\Amenities;

use Modules\Platform\Http\Requests\StoreAmenityRequest;
use Modules\Platform\Models\Amenity;

class StoreAmenity
{
    public function execute(StoreAmenityRequest $request)
    {
        $amenity = Amenity::create([
            'name' => $request->name
        ]);

        if( $request->hasFile('icon') ) {
            $path = $request
                ->file('icon')
                ->storePubliclyAs(
                    'amenities', 
                    $request->file('icon')->getClientOriginalName(),
                    'public'
                );

            $amenity->fill([
                'icon' => '/storage/'.$path
            ])->save();
        }
    }
}