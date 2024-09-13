<?php

namespace Modules\Offering\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Company\Models\Company;
use Modules\Offering\Models\Roast;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class SyncRoastImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Company $company
    ){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $roasts = Roast::where('company_id', $this->company->id)
            ->where(function( $query ){
                $query->where('primary_image_disk', 'remote')
                    ->orWhere('details_card_disk', 'remote');
            })->get();

        foreach( $roasts as $roast ){
            if( $roast->primary_image != '' && $roast->primary_image_disk === 'remote' ){
                $roast->primary_image = $this->downloadImage( $roast->primary_image, 'primary', $roast );
                $roast->primary_image_disk = 'local';
            }

            if( $roast->details_card != '' && $roast->details_card_disk === 'remote' ){
                $roast->details_card = $this->downloadImage( $roast->details_card, 'details', $roast );
                $roast->details_card_disk = 'local';
            }

            $roast->save();
        }
    }

    private function downloadImage( $url, $type, $roast )
    {
        $response = Http::withoutVerifying()->get($url);

        if( $response->successful() ){
            $image = $response->body();
            $path = 'companies/'.$this->company->slug.'/roasts/roast-'.$type.'-'.$roast->id.'.jpg';

            Storage::disk('public')->put($path, $image );

            return '/storage/'.$path;
        }

        return '';
    }
}
