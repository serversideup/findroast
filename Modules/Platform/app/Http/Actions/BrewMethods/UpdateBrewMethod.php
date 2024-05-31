<?php

namespace Modules\Platform\Http\Actions\BrewMethods;

use Illuminate\Http\Request;
use Modules\Platform\Models\BrewMethod;

class UpdateBrewMethod
{
    public function execute(Request $request, BrewMethod $brewMethod)
    {
        $brewMethod->fill([
            'name' => $request->name,
        ])->save();
        
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