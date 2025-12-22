<?php

namespace Modules\Offering\Console;

use Illuminate\Console\Command;
use Modules\Company\Models\Company;
use Modules\Offering\Models\OfferingImportMap;
use Modules\Offering\Models\Batch;
use Illuminate\Support\Str;
use Modules\Offering\Jobs\SyncRoast;
use Modules\Offering\Jobs\MarkMissingRoasts;
use Modules\Offering\Jobs\Shopify\LoadShopifyProducts;
use Modules\Offering\Jobs\Global\LoadGlobalProducts;

class SyncOfferings extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'roast:sync-offerings';

    /**
     * The console command description.
     */
    protected $description = 'Syncs all the current offerings for the coffee company.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $importMaps = OfferingImportMap::where( 'enabled', 1 )
            ->with('company')
            ->get();

        foreach( $importMaps as $importMap ) {
            if( $importMap->is_shopify ){
                LoadShopifyProducts::dispatch($importMap->company, $importMap);
            }else{
                LoadGlobalProducts::dispatch($importMap->company, $importMap);
            }
        }
    }
}
