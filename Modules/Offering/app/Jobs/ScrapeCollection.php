<?php

namespace Modules\Offering\Jobs;

use Modules\Company\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Offering\Models\OfferingImportMap;
use Modules\Offering\Http\Actions\Roasts\FetchRoastCollection;
use Modules\Offering\Jobs\CleanCollection;

class ScrapeCollection implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected Company $company,
        protected OfferingImportMap $importMap
    ){}
    
    public function handle(): void
    {
        $scrapedRoasts = ( new FetchRoastCollection( 
            $this->company, 
            $this->importMap 
        ) )
        ->execute();

        CleanCollection::dispatch($this->company, $scrapedRoasts);
    }
}