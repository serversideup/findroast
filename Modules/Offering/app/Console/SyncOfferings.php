<?php

namespace Modules\Offering\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Company\Models\Company;
use Modules\Offering\Http\Actions\Roasts\FetchRoastCollection;
use Modules\Offering\Models\OfferingImportMap;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Modules\Offering\Models\Batch;
use Illuminate\Support\Str;
use Modules\Offering\Jobs\SyncRoast;
use Modules\Offering\Jobs\MarkMissingRoasts;

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
        $day = Carbon::now()->dayName;

        $importMaps = OfferingImportMap::where( 'enabled', 1 )
            ->where( 'day', strtolower( $day ) )
            ->with('company')
            ->get();

        foreach( $importMaps as $importMap ) {
            $this->fetchRoastCollection($importMap->company, $importMap);
        }
    }

    protected function fetchRoastCollection(Company $company, OfferingImportMap $importMap)
    {
        $roastsCollection = ( new FetchRoastCollection($company, $importMap) )
                ->execute();

        $batch = Batch::create([
            'uuid' => Str::uuid(),
            'company_id' => $company->id,
            'scraped_data' => json_encode($roastsCollection)
        ]);
        
        $delay = 30;
        
        foreach ($roastsCollection as $roastCollection) {
            SyncRoast::dispatch(
                $company, 
                $importMap, 
                $batch, 
                $roastCollection
            )->delay(now()->addSeconds($delay));

            $delay += 30;
        }

        $delay += 100;

        MarkMissingRoasts::dispatch($company, $batch)
            ->delay(now()->addSeconds($delay));

        $importMap->update([
            'last_synced_at' => now()
        ]);
    }

    /**
     * Get the console command arguments.
     */
    protected function getArguments(): array
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     */
    protected function getOptions(): array
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
