<?php

namespace Modules\Platform\Http\Actions\Roasts;

use Modules\Offering\Models\Roast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class UpdateRoast
{
    public function __construct( 
        protected Request $request, 
        protected Roast $roast 
    ){ }

    public function update()
    {
        if( $this->request->has('new_primary_image') && $this->request->get('new_primary_image') != '' ){
            $this->setPrimaryImage( $this->request->get('new_primary_image') );
        }

        $this->setVarieties( $this->request->get('varieties') );
        $this->setProcesses( $this->request->get('processes') );
        $this->setCountries( $this->request->get('countries') );
        $this->setElevations( $this->request->get('elevations') );
        $this->setFlavorNotes( $this->request->get('flavor_notes') );
    }

    public function setPrimaryImage( $url )
    {
        $response = Http::withoutVerifying()
            ->get( $url );

        if( $response->successful() ){
            $image = $response->body();

            $path = 'companies/'.$this->roast->company->slug.'/roasts/roast-primary-'.$this->roast->id.'.jpg';

            Storage::disk('public')
                ->put($path, $image);

            $this->roast->primary_image = $path;
            $this->roast->save();
        }
    }

    public function setVarieties( $varieties )
    {
        $this->roast->varieties()->detach();

        foreach( $varieties as $variety ){
            $this->roast->varieties()->syncWithoutDetaching( $variety['id'] );
        }
    }

    public function setProcesses( $processes )
    {
        $this->roast->processes()->detach();

        foreach( $processes as $process ){
            $this->roast->processes()->syncWithoutDetaching( $process['id'] );
        }
    }
    
    public function setCountries( $countries )
    {
        $this->roast->countries()->detach();

        foreach( $countries as $country ){
            $this->roast->countries()->syncWithoutDetaching( $country['id'] );
        }
    }

    public function setElevations( $elevations )
    {
        $this->roast->elevations()->detach();

        foreach( $elevations as $elevation ){
            $this->roast->elevations()->syncWithoutDetaching( $elevation['id'] );
        }
    }
    
    public function setFlavorNotes( $flavorNotes )
    {
        $this->roast->flavorNotes()->detach();

        foreach( $flavorNotes as $flavorNote ){
            $this->roast->flavorNotes()->syncWithoutDetaching( $flavorNote['id'] );
        }
    }
}