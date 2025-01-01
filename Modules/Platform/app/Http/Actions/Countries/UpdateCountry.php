<?php

namespace Modules\Platform\Http\Actions\Countries;

use Illuminate\Http\Request;
use Modules\Offering\Models\Country;

class UpdateCountry
{
    public function execute(Request $request, Country $country)
    {
        $country->fill([
            'name' => $request->name,
            'slug' => $request->slug
        ])->save();
    }
}