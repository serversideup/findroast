<?php

namespace Modules\Platform\Http\Actions\BrewMethods;

use Modules\Platform\Http\Requests\StoreBrewMethodRequest;
use Modules\Platform\Models\BrewMethod;

class StoreBrewMethod
{
    public function execute(StoreBrewMethodRequest $request)
    {
        
        $brewMethod = BrewMethod::create([
            'name' => $request->name
        ]);

        if( $request->hasFile('icon') ) {
            $path = $request
                ->file('icon')
                ->storePubliclyAs(
                    'brew-methods', 
                    $request->file('icon')->getClientOriginalName(),
                    'public'
                );

            $brewMethod->fill([
                'icon' => '/storage/'.$path
            ])->save();
        }
    }
}