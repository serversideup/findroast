<?php

namespace Modules\Platform\Http\Actions\Countries;

use Modules\Offering\Models\Country;
use Illuminate\Support\Facades\DB;

class DeleteCountry
{
    public static function execute(Country $country)
    {
        DB::table('roast_countries')
            ->where('country_id', $country->id)
            ->delete();

        $country->delete();
    }
}