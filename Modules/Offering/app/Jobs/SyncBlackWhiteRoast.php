<?php

namespace Modules\Offering\Jobs;

use Modules\Offering\Models\Roast;
use Modules\Offering\Http\Actions\Roasts\SyncCountries;
use Modules\Offering\Http\Actions\Roasts\SyncElevations;
use Modules\Offering\Http\Actions\Roasts\SyncProcesses;
use Modules\Offering\Http\Actions\Roasts\SyncFlavorNotes;
use Modules\Offering\Http\Actions\Roasts\SyncVarieties;
use Modules\Offering\Http\Actions\Roasts\DownloadImage;
use Modules\Offering\Http\Actions\Roasts\ExtractImageData;
use Modules\Offering\Http\Actions\Roasts\ExtractTextData;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;

class SyncBlackWhiteRoast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $flavorNotes = [];
    protected $processes = [];
    protected $countries = [];
    protected $varieties = [];
    protected $elevations = [];

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
        $this->loadRelations();

        $roastData = $this->loadRoastData();

        // 1. Extract the primary image
        $imageUrl = $this->extractPrimaryImage($roastData);

        // 2. Download the image
        $localImageUrl = (new DownloadImage($imageUrl, $this->roast, 'primary'))->execute();

        // 3. Update the roast with the local image URL
        $this->roast->update([
            'primary_image' => $localImageUrl,
            'primary_image_disk' => 'local'
        ]);
        
        // 4. Extract details card
        $detailsCard = $this->extractDetailsCard($roastData);

        // 5. Download the details card
        $localDetailsCardUrl = (new DownloadImage($detailsCard, $this->roast, 'details_card'))->execute();

        // 6. Update the roast with the local details card URL
        $this->roast->update([
            'details_card' => $localDetailsCardUrl,
            'details_card_disk' => 'local'
        ]);

        // 7. Extract image data
        $imageData = $this->extractImageData();

        // 8. Extract text data
        $textData = $this->extractTextData( $roastData );

        // 9. Set the image data
        $this->setImageData($imageData);

        // 10. Set the text data
        $this->setTextData($textData);

        // 11. Sync the flavor notes
        SyncFlavorNotes::execute($this->roast, $this->flavorNotes);

        // 12. Sync the processes
        SyncProcesses::execute($this->roast, $this->processes);

        // 13. Sync the countries
        SyncCountries::execute($this->roast, $this->countries);

        // 14. Sync the varieties
        SyncVarieties::execute($this->roast, $this->varieties);

        // 15. Sync the elevations
        SyncElevations::execute($this->roast, $this->elevations);

        // 16. Mark the roast as synced
        $this->roast->update([
            'details_processed_at' => now()
        ]);
    }

    private function loadRelations()
    {
        $this->roast->load('company');
        $this->roast->load('company.offeringImportMap');
    }

    private function loadRoastData()
    {
        $response = Http::get($this->roast->url);

        $dom = new \IvoPetkov\HTML5DOMDocument();
        $dom->loadHTML($response->body(), \IvoPetkov\HTML5DOMDocument::ALLOW_DUPLICATE_IDS);

        return $dom;
    }

    private function extractPrimaryImage($roastData)
    {
        // Query the second image in the product-media--image class.
        $primaryImage = $roastData->querySelectorAll('.product-media--image img');

        if( $primaryImage->length > 1 ){
            $primaryImage = $primaryImage[1];
        } else {
            $primaryImage = $primaryImage[0];
        }

        // Get the source attribute from the image.
        $primaryImage = $primaryImage->getAttribute('src');

        return 'https:'.$primaryImage;
    }
    
    
    private function extractDetailsCard($roastData)
    {
        // Query the first image in the product-media--image class.
        $detailsCard = $roastData->querySelector('.product-media--image img');

        // Get the source attribute from the image.
        $detailsCard = $detailsCard->getAttribute('src');

        return 'https:'.$detailsCard;
    }

    private function extractImageData()
    {
        $imageData = ( new ExtractImageData( $this->roast->details_card, [
            'flavor_notes'
        ] ) )->execute();

        return $imageData;
    }

    private function extractTextData($roastData)
    {
        $productForm = $roastData->querySelector('.product-form')->innerHTML;
        $html = new \Html2Text\Html2Text($productForm);

        $textData = ( new ExtractTextData($html->getText(), [
            'processes',
            'countries',
            'varieties',
            'elevations'
        ]))->execute();

        return $textData;
    }

    private function setImageData($imageData)
    {
        $this->flavorNotes = isset( $imageData['flavor_notes'] ) ? $imageData['flavor_notes'] : [];
    }

    private function setTextData($textData)
    {
        $this->processes = isset( $textData['processes'] ) ? $textData['processes'] : [];
        $this->countries = isset( $textData['countries'] ) ? $textData['countries'] : [];
        $this->varieties = isset( $textData['varieties'] ) ? $textData['varieties'] : [];
        $this->elevations = isset( $textData['elevations'] ) ? $textData['elevations'] : [];
    }
}
