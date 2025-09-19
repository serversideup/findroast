<?php

namespace Modules\Offering\Jobs;

use Modules\Company\Models\Company;
use Modules\Offering\Models\Roast;
use Modules\Offering\Http\Actions\Roasts\SyncCountries;
use Modules\Offering\Http\Actions\Roasts\SyncElevations;
use Modules\Offering\Http\Actions\Roasts\SyncProcesses;
use Modules\Offering\Http\Actions\Roasts\SyncFlavorNotes;
use Modules\Offering\Http\Actions\Roasts\SyncVarieties;
use Modules\Offering\Models\OfferingImportMap;
use Modules\Offering\Http\Actions\Roasts\ScrapeRoast;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SyncRoast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected Company $company,
        protected Roast $roast
    ){}
    
    public function handle(): void
    {
        $roastData = $this->loadRoastData();

        SyncFlavorNotes::execute($this->roast, $roastData['flavor_notes']);
        SyncProcesses::execute($this->roast, $roastData['processes']);
        SyncCountries::execute($this->roast, $roastData['countries']);
        SyncVarieties::execute($this->roast, $roastData['varieties']);
        SyncElevations::execute($this->roast, $roastData['elevations']);
    }

    protected function loadRoastData()
    {
        $offeringImportMap = OfferingImportMap::where('company_id', $this->company->id)
            ->first();

        $roastData = ( new ScrapeRoast($offeringImportMap, $this->roast) )
            ->execute();

        return $roastData;
    }
}