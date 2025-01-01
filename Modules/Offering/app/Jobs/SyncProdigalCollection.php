<?php

namespace Modules\Offering\Jobs;

use Modules\Company\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;
use Modules\Offering\Models\Roast;

class SyncProdigalCollection implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $baseUrl = 'https://getprodigal.com';
    protected $roastUrls = [];

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
        // Load the coffee products provided.
        $coffees = $this->loadCoffeeProducts();

        // Loop through the coffee products and save them.
        foreach ($coffees as $index => $coffee) {
            $nameElement = $coffee->querySelector('a.full-unstyled-link');

            $name = '';
            $price = '';
            $productUrl = '';

            if( $nameElement ){
                $name = trim( $nameElement->textContent );

                $priceElement = $coffee->querySelector('span.price-item--regular');

                if( $priceElement ){
                    $price = $this->formatPrice($priceElement->textContent);
                }

                $productUrl = $this->baseUrl.$nameElement->getAttribute('href');

                array_push($this->roastUrls, $productUrl);

                // Save the roast and return the Roast object.
                $roast = $this->saveRoast($name, $price, $productUrl);

                // If the roast was recently created, dispatch the SyncRoast job.
                if( $roast->wasRecentlyCreated ){
                    // We delay the sync out of respect for Prodigal
                    SyncProdigalRoast::dispatch($roast)
                        ->delay(now()->addMinutes($index));
                }
            }
        }

        // Mark the roasts that are no longer in the collection.
        $this->markMissingRoasts();
    }

    /**
     * Load the coffee products from the The Boy and the Bear collection page.
     * 
     * @return \DOMNodeList
     */
    protected function loadCoffeeProducts()
    {
        $collectionUrl = $this->baseUrl.'/collections/roasted-coffee';

        $response = Http::get($collectionUrl);

        $dom = new \IvoPetkov\HTML5DOMDocument();
        $dom->loadHTML($response->body(), \IvoPetkov\HTML5DOMDocument::ALLOW_DUPLICATE_IDS);

        $coffees = $dom->querySelectorAll('.grid__item');

        return $coffees;
    }

    /**
     * Save the roast to the database.
     * 
     * @param string $name
     * @param string $price
     * @param string $productUrl
     * @return Roast
     */
    protected function saveRoast($name, $price, $productUrl)
    {
        $roast = Roast::firstOrNew([
            'company_id' => $this->company->id,
            'url' => $productUrl,
        ]);

        $roast->fill([
            'name' => $name,
            'price' => $price,
            'in_stock' => 1,
            'last_seen_at' => null,
            'last_synced_at' => now(),
        ]);

        if( !$roast->exists ){
            $roast->first_seen_at = now();
        }

        $roast->save();
        
        return $roast;
    }

    /**
     * Format the price from the The Boy and the Bear website.
     * 
     * @param string $price
     * @return string
     */
    protected function formatPrice($price)
    {
        // Replace all alpha characters with empty strings.
        $price = preg_replace('/[^\d.]/', '', $price);

        return $price;
    }

    /**
     * Mark the roasts that are no longer in the collection.
     */
    protected function markMissingRoasts()
    {
        Roast::where('company_id', $this->company->id)
            ->whereNotIn('url', $this->roastUrls)
            ->update([
                'in_stock' => 0,
                'last_seen_at' => now(),
            ]);
    }
}
