<?php

namespace Modules\Offering\Jobs;

use Modules\Company\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Offering\Models\Roast;
use Modules\Offering\Models\Batch;

class MarkMissingRoasts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected Company $company,
        protected Batch $batch
    ){}

    public function handle(): void
    {
        $roasts = $this->batch->roasts;

        Roast::where('company_id', $this->company->id)
            ->whereNotIn('url', $roasts->pluck('url'))
            ->update([
                'in_stock' => 0,
                'last_seen_at' => now(),
            ]);
    }
}