<?php

namespace Modules\Offering\Jobs\Shopify;

use Modules\Company\Models\Company;
use Modules\Offering\Models\OfferingImportMap;
use Modules\Offering\Models\Batch;
use Modules\Offering\Models\Roast;
use Modules\Offering\Http\Actions\Roasts\ExtractImagesData;
use Modules\Offering\Http\Actions\Roasts\ExtractRoastData;
use Modules\Offering\Http\Actions\Roasts\ImportRoast;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SyncShopifyRoast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $timeout = 300;
    
    public function __construct(
        protected Company $company,
        protected OfferingImportMap $importMap,
        protected Batch $batch,
        protected array $shopifyRoast
    ){}
    
    public function handle(): void
    {
        $url = $this->shopifyRoast['url'];

        $roast = Roast::where('url', $url)
            ->where('in_stock', 1)
            ->first();

        if( $roast ){
            $this->batch->roasts()->attach([$roast->id => [
                'company_id' => $this->company->id,
            ]]);
        }else{

            $images = ( new ExtractImagesData(
                $this->shopifyRoast['images']
            ) )->execute();

            unset($this->shopifyRoast['images']);

            $roastData = ( new ExtractRoastData(
                $this->shopifyRoast,
                $images
            ) )->execute();

            $roast = ( new ImportRoast(
                $this->company,
                $roastData
            ) )->execute();

            $this->batch->roasts()->attach([$roast->id => [
                'company_id' => $this->company->id,
            ]]);
        }
    }
}