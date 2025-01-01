<?php

namespace Modules\Offering\Http\Actions\Roasts;

use Modules\Offering\Models\Roast;
use Modules\Offering\Models\Country;
use Illuminate\Support\Str;

class SyncCountries
{
    public static function execute(Roast $roast, array $countries)
    {
        foreach( $countries as $country ){
            $countryRecord = Country::firstOrCreate([
                'slug' => Str::slug($country)
            ], [
                'name' => $country
            ]);

            $roast->countries()->syncWithoutDetaching( $countryRecord->id );
        }
    }
}