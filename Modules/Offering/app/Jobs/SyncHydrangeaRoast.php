<?php

namespace Modules\Offering\Jobs;

use Modules\Offering\Models\Roast;
use Modules\Offering\Http\Actions\Roasts\SyncCountries;
use Modules\Offering\Http\Actions\Roasts\SyncElevations;
use Modules\Offering\Http\Actions\Roasts\SyncProcesses;
use Modules\Offering\Http\Actions\Roasts\SyncFlavorNotes;
use Modules\Offering\Http\Actions\Roasts\SyncVarieties;
use Modules\Offering\Http\Actions\Roasts\DownloadImage;
use Modules\Offering\Http\Actions\Roasts\ExtractTextData;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;

class SyncHydrangeaRoast implements ShouldQueue
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

        // 4. Extract text data
        $textData = $this->extractTextData( $roastData );

        // 5. Set the text data
        $this->setTextData($textData);

        // 6. Sync the flavor notes
        SyncFlavorNotes::execute($this->roast, $this->flavorNotes);

        // 7. Sync the processes
        SyncProcesses::execute($this->roast, $this->processes);

        // 8. Sync the countries
        SyncCountries::execute($this->roast, $this->countries);

        // 9. Sync the varieties
        SyncVarieties::execute($this->roast, $this->varieties);

        // 10. Sync the elevations
        SyncElevations::execute($this->roast, $this->elevations);

        // 11. Mark the roast as synced
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
        $primaryImage = $roastData->querySelector('.product__media img');

        // Get the source attribute from the image.
        $primaryImage = $primaryImage->getAttribute('src');

        return 'https:'.$primaryImage;
    }

    private function extractTextData($roastData)
    {
        $productForm = $roastData->querySelector('.product__info-container')->innerHTML;
        $html = new \Html2Text\Html2Text($productForm);

        $textData = ( new ExtractTextData($html->getText(), [
            'flavor_notes',
            'processes',
            'countries',
            'varieties',
            'elevations'
        ]))->execute();

        return $textData;
    }

    private function setTextData($textData)
    {
        $this->flavorNotes = isset( $textData['flavor_notes'] ) ? $textData['flavor_notes'] : [];
        $this->processes = isset( $textData['processes'] ) ? $textData['processes'] : [];
        $this->countries = isset( $textData['countries'] ) ? $textData['countries'] : [];
        $this->varieties = isset( $textData['varieties'] ) ? $textData['varieties'] : [];
        $this->elevations = isset( $textData['elevations'] ) ? $textData['elevations'] : [];
    }
}
