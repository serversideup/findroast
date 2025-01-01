<?php

namespace Modules\Platform\Http\Actions\Varieties;

use Illuminate\Http\Request;
use Modules\Offering\Models\Variety;

class UpdateVariety
{
    public function execute(Request $request, Variety $variety)
    {
        $variety->fill([
            'name' => $request->name,
            'slug' => $request->slug
        ])->save();
    }
}