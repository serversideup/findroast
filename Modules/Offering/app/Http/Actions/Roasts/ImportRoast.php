<?php

namespace Modules\Offering\Http\Actions\Roasts;

use Modules\Offering\Http\Actions\Roasts\SyncCountries;
use Modules\Offering\Http\Actions\Roasts\SyncElevations;
use Modules\Offering\Http\Actions\Roasts\SyncProcesses;
use Modules\Offering\Http\Actions\Roasts\SyncFlavorNotes;
use Modules\Offering\Http\Actions\Roasts\SyncVarieties;
use Modules\Company\Models\Company;
use Modules\Offering\Models\Roast;
use Illuminate\Support\Facades\Storage;

class ImportRoast
{
    public function __construct(
        protected Company $company,
        protected array $roastData
    ){}

    public function execute()
    {
        $roast = $this->saveRoast();

        $this->syncAttributes($roast);

        return $roast;
    }

    protected function saveRoast()
    {
        $roast = Roast::firstOrNew([
            'company_id' => $this->company->id,
            'url' => $this->roastData['url'],
        ]);

        $roast->fill([
            'name' => $this->roastData['name'],
            'price' => $this->formatPrice($this->roastData['price']),
            'currency' => $this->roastData['currency'] ?? $this->company->default_currency ?? 'USD',
            'in_stock' => $this->roastData['in_stock'],
            'last_seen_at' => null,
            'last_synced_at' => now(),
        ]);

        if( !$roast->exists ){
            $roast->first_seen_at = now();
        }

        $roast->save();

        $this->moveImages($roast);

        return $roast;
    }

    protected function syncAttributes($roast)
    {
        SyncFlavorNotes::execute($roast, $this->roastData['flavor_notes']);
        SyncProcesses::execute($roast, $this->roastData['processes']);
        SyncCountries::execute($roast, $this->roastData['countries']);
        SyncVarieties::execute($roast, $this->roastData['varieties']);
        SyncElevations::execute($roast, $this->roastData['elevations']);
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

    protected function moveImages($roast)
    {
        $cachePath = '';

        if( $this->roastData['primary_image'] ){
            $cachePath = $this->roastData['primary_image'];

            $newPath = 'companies/'.$roast->company->slug.'/roasts/roast-primary-'.$roast->id.'.jpg';

            Storage::disk('public')
                ->move($this->roastData['primary_image'], $newPath);
            
            $roast->update([
                'primary_image' => $newPath,
                'primary_image_disk' => 'local'
            ]);
        }

        if( $this->roastData['details_image'] ){
            $cachePath = $this->roastData['details_image'];

            $newPath = 'companies/'.$roast->company->slug.'/roasts/roast-details-'.$roast->id.'.jpg';

            Storage::disk('public')
                ->move($this->roastData['details_image'], $newPath);

            $roast->update([
                'details_image' => $newPath,
                'details_image_disk' => 'local'
            ]);
        }

        if( $cachePath ){
            $directory = dirname($cachePath);

            Storage::disk('public')
                ->deleteDirectory($directory);
        }

    }
}