<?php

namespace Modules\Offering\Http\Actions\Roasts;

use Modules\Company\Models\Company;
use Modules\Offering\Collections\RoastCollection;
use Modules\Offering\Jobs\SyncRoast;
use Modules\Offering\Models\Roast;

class PersistCollection
{
    public function __construct(
        protected Company $company
    ){}

    public function execute( $offerings )
    {
        $this->syncCurrentRoasts( $offerings );
        $this->markMissingRoasts( $offerings );
    }

    private function syncCurrentRoasts( RoastCollection $offerings )
    {
        foreach( $offerings as $index => $offering ){
            $roast = Roast::firstOrNew([
                'company_id' => $this->company->id,
                'url' => $offering->url,
            ]);

            $roast->fill([
                'in_stock' => 1,
                'last_seen_at' => null,
                'price' => $offering->price,
                'name' => $offering->name,
                'last_synced_at' => now(),
            ]);

            if( !$roast->exists ){
                $roast->first_seen_at = now();
            }
            
            $roast->save();

            if( $roast->wasRecentlyCreated ){
                SyncRoast::dispatch($roast)
                    ->delay(now()->addMinutes($index));;
            }
        }
    }

    private function markMissingRoasts( $offerings )
    {
        Roast::where('company_id', $this->company->id)
            ->whereNotIn('url', collect($offerings)->pluck('url'))
            ->update([
                'in_stock' => 0,
                'last_seen_at' => now(),
            ]);
    }
}