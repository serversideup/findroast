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
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;

class SyncRubyCoffeeRoast implements ShouldQueue
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

        // 4. Extract image data
        $imageData = $this->extractImageData();

        // 5. Set the image data
        $this->setImageData($imageData);

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
        $primaryImage = $roastData->querySelector('meta[property="og:image"]')->getAttribute('content');

        return $primaryImage;
    }

    private function extractImageData()
    {
        $imageData = ( new ExtractImageData( $this->roast->primary_image ) )->execute();
        return $imageData;
    }

    private function setImageData($imageData)
    {
        $this->flavorNotes = isset( $imageData['flavor_notes'] ) ? $imageData['flavor_notes'] : [];
        $this->processes = isset( $imageData['processes'] ) ? $imageData['processes'] : [];
        $this->countries = isset( $imageData['countries'] ) ? $imageData['countries'] : [];
        $this->varieties = isset( $imageData['varieties'] ) ? $imageData['varieties'] : [];
        $this->elevations = isset( $imageData['elevations'] ) ? $imageData['elevations'] : [];
    }
}
