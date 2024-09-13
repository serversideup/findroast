<?php

namespace Modules\Offering\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;
use Modules\Company\Models\Company;
use Modules\Offering\Collections\RoastCollection;
use Modules\Offering\Data\RoastData;
use Modules\Offering\Http\Actions\Roasts\PersistCollection;
use Modules\Offering\Models\OfferingImportMap;

class SyncCompanyOfferings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct( 
        protected OfferingImportMap $importMap
    ){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $response = Http::withoutVerifying()
            ->get(config('services.offerings_api.url').$this->importMap->api_url.'/collection');

        if ($response->successful()) {
            $roasts = collect( $response->json() )
                ->map( fn( $roast ) => RoastData::fromCollectionResponse( $roast ) );
            
            $offerings = RoastCollection::make($roasts);

            $company = Company::find($this->importMap->company_id);

            ( new PersistCollection(
                $company
            ) )->execute($offerings);

            $this->importMap->fill([
                'last_synced_at' => date('Y-m-d H:i:s')
            ]);

            $this->importMap->save();
        }
    }
}
