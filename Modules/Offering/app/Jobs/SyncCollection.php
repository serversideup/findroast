<?php

namespace Modules\Offering\Jobs;

use Modules\Company\Models\Company;
use Modules\Offering\Models\Roast;
use Modules\Offering\Http\Actions\Roasts\DownloadImage;
use Modules\Offering\Http\Actions\Roasts\ScrapeCollection;
use Modules\Offering\Http\Actions\Roasts\SyncCountries;
use Modules\Offering\Http\Actions\Roasts\SyncElevations;
use Modules\Offering\Http\Actions\Roasts\SyncProcesses;
use Modules\Offering\Http\Actions\Roasts\SyncFlavorNotes;
use Modules\Offering\Http\Actions\Roasts\SyncVarieties;
use Modules\Offering\Models\OfferingImportMap;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SyncCollection implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $roastUrls = [];

    public function __construct(
        protected Company $company
    ){}

    public function handle(): void
    {
        $roastCollection = $this->loadRoasts();

        foreach ($roastCollection as $index => $item) {
            $roast = $this->saveRoast($item);

            array_push($this->roastUrls, $item['url']);

            // If the roast was recently created, dispatch the SyncRoast job.
            // Otherwise, we don't need to sync the roast again.
            if( $roast->wasRecentlyCreated ){
                SyncRoast::dispatch($this->company, $roast)
                    ->delay(now()->addMinutes($index));
            }
        }

        $this->markMissingRoasts();
    }

    protected function loadRoasts()
    {
        $offeringImportMap = OfferingImportMap::where('company_id', $this->company->id)
            ->first();

        $roasts = ( new ScrapeCollection( $this->company, $offeringImportMap ) )
            ->execute();

        return $roasts;
    }

    protected function saveRoast($item)
    {
        $roast = Roast::firstOrNew([
            'company_id' => $this->company->id,
            'url' => $item['url'],
        ]);

        $roast->fill([
            'name' => $item['name'],
            'price' => $this->formatPrice($item['price']),
            'currency' => 'USD',
            'in_stock' => $item['in_stock'],
            'last_seen_at' => null,
            'last_synced_at' => now(),
        ]);

        if( !$roast->exists ){
            $roast->first_seen_at = now();
        }

        $roast->save();

        $this->downloadImage($item['image'], $roast);

        SyncFlavorNotes::execute($roast, $item['flavor_notes']);
        SyncProcesses::execute($roast, $item['processes']);
        SyncCountries::execute($roast, $item['countries']);

        return $roast;
    }

    /**
     * Format the price from the Ruby Coffee Roasters website.
     * 
     * @param string $price
     * @return string
     */
    protected function formatPrice($price)
    {
        // replace any non-numeric characters with an empty string
        $price = preg_replace('/[^0-9.]/', '', $price);

        return $price;
    }

    protected function downloadImage($imageUrl, $roast)
    {
        if( $imageUrl ){
            $localImageUrl = (new DownloadImage($imageUrl, $roast, 'primary'))
                ->execute();

            $roast->update([
                'primary_image' => $localImageUrl,
                'primary_image_disk' => 'local'
            ]);
        }
    }


    protected function markMissingRoasts()
    {
        Roast::where('company_id', $this->company->id)
            ->whereNotIn('url', $this->roastUrls)
            ->update([
                'in_stock' => 0,
                'last_seen_at' => now(),
            ]);
    }
}