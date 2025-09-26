<?php

namespace Modules\Offering\Jobs;

use Modules\Company\Models\Company;
use Modules\Offering\Models\OfferingImportMap;
use Modules\Offering\Models\Batch;
use Modules\Offering\Models\Roast;
use Modules\Offering\Http\Actions\Roasts\FetchRoast;
use Modules\Offering\Http\Actions\Roasts\MergeFetchedData;
use Modules\Offering\Http\Actions\Roasts\ExtractImagesData;
use Modules\Offering\Http\Actions\Roasts\ExtractRoastData;
use Modules\Offering\Http\Actions\Roasts\ImportRoast;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SyncRoast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $timeout = 300;
    
    public function __construct(
        protected Company $company,
        protected OfferingImportMap $importMap,
        protected Batch $batch,
        protected array $collectionRoast
    ){}
    
    public function handle(): void
    {
        $url = $this->collectionRoast['links'][0];

        $roast = Roast::where('url', $url)->first();

        if( $roast ){
            $this->batch->roasts()->attach([$roast->id => [
                'company_id' => $this->company->id,
            ]]);
        }else{
            $singleRoast = ( new FetchRoast(
                $this->importMap, 
                $url
            ) )->execute();

            $mergedRoast = ( new MergeFetchedData(
                $this->collectionRoast, 
                $singleRoast
            ) )->execute();

            $images = ( new ExtractImagesData(
                $mergedRoast['images']
            ) )->execute();

            $roastData = ( new ExtractRoastData(
                $mergedRoast,
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