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
        // Update basic fields
        $this->roast->update([
            'name' => $this->request->get('name'),
            'url' => $this->request->get('url'),
            'price' => $this->request->get('price'),
        ]);

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
        try {
            $response = Http::withoutVerifying()
                ->timeout(30)
                ->get( $url );

            if( $response->successful() ){
                $image = $response->body();

                $path = 'companies/'.$this->roast->company->slug.'/roasts/roast-primary-'.$this->roast->id.'.jpg';

                Storage::disk('public')
                    ->put($path, $image);

                $this->roast->primary_image = $path;
                $this->roast->save();
            } else {
                \Log::warning('Failed to download roast image', [
                    'roast_id' => $this->roast->id,
                    'url' => $url,
                    'status' => $response->status()
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error downloading roast image', [
                'roast_id' => $this->roast->id,
                'url' => $url,
                'error' => $e->getMessage()
            ]);
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