<?php 

namespace Modules\Offering\Jobs\Global;

use Modules\Company\Models\Company;
use Modules\Offering\Models\OfferingImportMap;
use Modules\Offering\Models\Batch;
use Modules\Offering\Http\Actions\Roasts\FetchRoastCollection;
use Modules\Offering\Jobs\SyncRoast;
use Modules\Offering\Jobs\MarkMissingRoasts;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Str;

class LoadGlobalProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected Company $company,
        protected OfferingImportMap $importMap
    ){}
    
    public function handle(): void
    {
        $roastsCollection = ( new FetchRoastCollection(
            $this->company, 
            $this->importMap
        ) )->execute();

        $batch = Batch::create([
            'uuid' => Str::uuid(),
            'company_id' => $this->company->id,
            'scraped_data' => json_encode($roastsCollection)
        ]);
        
        $delay = 30;
        
        foreach ($roastsCollection as $roastCollection) {
            SyncRoast::dispatch(
                $this->company, 
                $this->importMap, 
                $batch, 
                $roastCollection
            )->delay(now()->addSeconds($delay));

            $delay += 30;
        }

        $delay += 100;

        MarkMissingRoasts::dispatch($this->company, $batch)
            ->delay(now()->addSeconds($delay));

        $this->importMap->update([
            'last_synced_at' => now()
        ]);
    }
}