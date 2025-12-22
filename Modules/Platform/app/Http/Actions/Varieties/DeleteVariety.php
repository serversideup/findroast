<?php

namespace Modules\Platform\Http\Actions\Varieties;

use Modules\Offering\Models\Variety;
use Illuminate\Support\Facades\DB;

class DeleteVariety
{
    public static function execute(Variety $variety)
    {
        DB::table('roast_varieties')
            ->where('variety_id', $variety->id)
            ->delete();

        $variety->delete();
    }
}