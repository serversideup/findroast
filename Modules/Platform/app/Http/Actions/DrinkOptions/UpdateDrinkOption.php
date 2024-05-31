<?php

namespace Modules\Platform\Http\Actions\DrinkOptions;

use Illuminate\Http\Request;
use Modules\Platform\Models\DrinkOption;

class UpdateDrinkOption
{
    public function execute(Request $request, DrinkOption $drinkOption)
    {
        $drinkOption->fill([
            'name' => $request->name,
        ])->save();
        
        if( $request->hasFile('icon') ) {
            $path = $request
                ->file('icon')
                ->storePubliclyAs(
                    'drink-options', 
                    $request->file('icon')->getClientOriginalName(),
                    'public'
                );

            $drinkOption->fill([
                'icon' => '/storage/'.$path
            ])->save();
        }
    }
}