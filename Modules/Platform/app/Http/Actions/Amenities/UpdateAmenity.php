<?php

namespace Modules\Platform\Http\Actions\Amenities;

use Illuminate\Http\Request;
use Modules\Platform\Models\Amenity;

class UpdateAmenity
{
    public function execute(Request $request, Amenity $amenity)
    {
        $amenity->fill([
            'name' => $request->name,
        ])->save();
        
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