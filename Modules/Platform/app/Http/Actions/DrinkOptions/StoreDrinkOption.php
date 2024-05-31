<?php

namespace Modules\Platform\Http\Actions\DrinkOptions;

use Modules\Platform\Http\Requests\StoreDrinkOptionRequest;
use Modules\Platform\Models\DrinkOption;

class StoreDrinkOption
{
    public function execute(StoreDrinkOptionRequest $request)
    {
        $drinkOption = DrinkOption::create([
            'name' => $request->name
        ]);

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