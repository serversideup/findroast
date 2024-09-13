<?php

namespace Modules\Offering\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Offering\Jobs\SyncCompanyOfferings;
use Modules\Offering\Models\OfferingImportMap;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

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
            ->get();

        foreach( $importMaps as $importMap ) {
            SyncCompanyOfferings::dispatch( $importMap );
        }
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
