<?php

namespace Modules\Offering\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Modules\Offering\Data\RoastData;
use Modules\Offering\Data\RoastDetailsData;
use Modules\Offering\Http\Actions\Roasts\ExtractImageData;
use Modules\Offering\Http\Actions\Roasts\ExtractRawTextData;
use Modules\Offering\Models\Country;
use Modules\Offering\Models\Elevation;
use Modules\Offering\Models\FlavorNote;
use Modules\Offering\Models\Process;
use Modules\Offering\Models\Roast;
use Modules\Offering\Models\Variety;

class SyncRoast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $primaryImageData = [];
    protected $detailsCardData = [];
    protected $rawTextData = [];

    protected $processedPrimaryImage = false;
    protected $processedDetailsCard = false;
    protected $processedRawText = false;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Roast $roast
    ){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->roast->load('company');
        $this->roast->load('company.offeringImportMap');

        $response = Http::withoutVerifying()
            ->get(config('services.offerings_api.url').$this->roast->company->offeringImportMap->api_url.'/roast/', [
                'url' => $this->roast->url
            ]);

        if( $response->successful() ){
            $roastData = RoastData::fromSingleResponse( $response->json() );
            
            if( $this->roast->details_processed_at !== null ){
                $this->roast->fill([
                    'in_stock' => $roastData->inStock ? 1 : 0,
                    'last_synced_at' => now()
                ]);
            }else{
                $this->roast->fill([
                    'primary_image' => $roastData->image,
                    'primary_image_disk' => 'remote',
                    'details_card' => $roastData->detailsCard,
                    'details_card_disk' => 'remote',
                    'type' => $roastData->type,
                    'in_stock' => $roastData->inStock ? 1 : 0,
                    'last_synced_at' => now()
                ]);
    
                $this->roast->save();

                $this->syncImages();
                $roastDetailsData = $this->extractDetails( $roastData );

                $this->syncFlavorNotes( $roastDetailsData->flavorNotes );
                $this->syncElevations( $roastDetailsData->elevations );
                $this->syncCountries( $roastDetailsData->countries );
                $this->syncProcesses( $roastDetailsData->processes );
                $this->syncVarieties( $roastDetailsData->varieties );

                $this->roast->fill([
                    'details_processed_at' => now()
                ]);

                $this->roast->save();
            }
        }
    }

    private function syncImages()
    {
        // Download images
        if( $this->roast->primary_image != '' && $this->roast->primary_image_disk === 'remote' ){
            $this->roast->primary_image = $this->downloadImage( 'primary');
            $this->roast->primary_image_disk = 'local';
        }

        if( $this->roast->details_card != '' && $this->roast->details_card_disk === 'remote' ){
            $this->roast->details_card = $this->downloadImage( 'details');
            $this->roast->details_card_disk = 'local';
        }

        $this->roast->save();
    }

    private function downloadImage( $type )
    {
        $response = Http::withoutVerifying()
            ->get($this->roast->primary_image);

        if( $response->successful() ){
            $image = $response->body();
            $path = 'companies/'.$this->roast->company->slug.'/roasts/roast-'.$type.'-'.$this->roast->id.'.jpg';

            Storage::disk('public')->put($path, $image );
        }

        return '/storage/'.$path;
    }

    private function extractDetails( RoastData $roastData ): RoastDetailsData
    {
        $roastDetailsData = new RoastDetailsData(
            flavorNotes: $roastData->flavorNotes,
            elevations: $roastData->elevations,
            countries: $roastData->countries,
            processes: $roastData->processes,
            varieties: $roastData->varieties
        );

        foreach( $roastData->detailsMap as $key => $value ){
            if( $value == 'primary-image' && !$this->processedPrimaryImage ){
                $this->processPrimaryImage();
                $this->processedPrimaryImage = true;
            }elseif( $value == 'details-card' && !$this->processedDetailsCard ){
                $this->processDetailsCard();
                $this->processedDetailsCard = true;
            }elseif( $value == 'raw-text' && !$this->processedRawText ){
                $this->processRawText( $roastData->rawText );
                $this->processedRawText = true;
            }
        }

        if( $roastData->detailsMap['flavor_notes'] != 'scrape' ){
            $roastDetailsData->flavorNotes = $this->mergeResults( 'flavor_notes', $roastData );
        }
        
        if( $roastData->detailsMap['elevations'] != 'scrape' ){
            $roastDetailsData->elevations = $this->mergeResults( 'elevations', $roastData );
        }

        if( $roastData->detailsMap['countries'] != 'scrape' ){
            $roastDetailsData->countries = $this->mergeResults( 'countries', $roastData );
        }

        if( $roastData->detailsMap['processes'] != 'scrape' ){
            $roastDetailsData->processes = $this->mergeResults( 'processes', $roastData );
        }

        if( $roastData->detailsMap['varieties'] != 'scrape' ){
            $roastDetailsData->varieties = $this->mergeResults( 'varieties', $roastData );
        }
        
        return $roastDetailsData;
    }

    private function mergeResults( $key, $roastData )
    {
        switch( $roastData->detailsMap[ $key ] ){
            case 'primary-image':
                return $this->primaryImageData[ $key ];
            case 'details-card':
                return $this->detailsCardData[ $key ];
            case 'raw-text':
                return $this->rawTextData[ $key ];
            default:
                return [];
        }
    }

    private function processPrimaryImage()
    {
        $extractImageData = new ExtractImageData( $this->roast->primary_image );
        $this->primaryImageData = $extractImageData->execute();
    }

    private function processDetailsCard()
    {
        $extractImageData = new ExtractImageData( $this->roast->details_card );
        $this->detailsCardData = $extractImageData->execute();
    }

    private function processRawText( $html )
    {
        $extractRawTextData = new ExtractRawTextData( $html );
        $this->rawTextData = $extractRawTextData->execute();
    }

    private function syncFlavorNotes( $flavorNotes )
    {
        foreach( $flavorNotes as $flavorNote ){
            $flavorNoteRecord = FlavorNote::firstOrCreate([
                'name' => $flavorNote
            ]);

            $this->roast->flavorNotes()->syncWithoutDetaching( $flavorNoteRecord->id );
        }
    }

    private function syncElevations( $elevations )
    {
        foreach( $elevations as $elevation ){
            $elevationRecord = Elevation::firstOrCreate([
                'name' => $elevation
            ]);

            $this->roast->elevations()->syncWithoutDetaching( $elevationRecord->id );
        }
    }

    private function syncCountries( $countries )
    {
        foreach( $countries as $country ){
            $countryRecord = Country::firstOrCreate([
                'name' => $country
            ]);

            $this->roast->countries()->syncWithoutDetaching( $countryRecord->id );
        }
    }

    private function syncProcesses( $processes )
    {
        foreach( $processes as $process ){
            $processRecord = Process::firstOrCreate([
                'name' => $process
            ]);

            $this->roast->processes()->syncWithoutDetaching( $processRecord->id );
        }
    }

    private function syncVarieties( $varieties )
    {
        foreach( $varieties as $variety ){
            $varietyRecord = Variety::firstOrCreate([
                'name' => $variety
            ]);

            $this->roast->varieties()->syncWithoutDetaching( $varietyRecord->id );
        }
    }
}
