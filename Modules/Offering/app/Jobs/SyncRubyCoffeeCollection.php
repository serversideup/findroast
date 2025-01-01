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

class SyncRubyCoffeeCollection implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $baseUrl = 'https://rubycoffeeroasters.com';
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
            $name = $coffee->querySelector('.product__title.h4')->textContent;
            $price = $this->formatPrice($coffee->querySelector('.product__price-price span')->textContent);
            $productUrl = $this->baseUrl.$coffee->querySelector('a.product-link')->getAttribute('href');

            array_push($this->roastUrls, $productUrl);

            // Save the roast and return the Roast object.
            $roast = $this->saveRoast($name, $price, $productUrl);

            // If the roast was recently created, dispatch the SyncRoast job.
            if( $roast->wasRecentlyCreated ){
                // We delay the sync out of respect for Ruby Coffee Roasters
                SyncRubyCoffeeRoast::dispatch($roast)
                    ->delay(now()->addMinutes($index));
            }
        }

        // Mark the roasts that are no longer in the collection.
        $this->markMissingRoasts();
    }

    /**
     * Load the coffee products from the Ruby Coffee Roasters collection page.
     * 
     * @return \DOMNodeList
     */
    protected function loadCoffeeProducts()
    {
        $collectionUrl = $this->baseUrl.'/collections/coffee';

        $response = Http::get($collectionUrl);

        $dom = new \IvoPetkov\HTML5DOMDocument();
        $dom->loadHTML($response->body(), \IvoPetkov\HTML5DOMDocument::ALLOW_DUPLICATE_IDS);

        $coffees = $dom->querySelectorAll('.collection-products .product');

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
     * Format the price from the Ruby Coffee Roasters website.
     * 
     * @param string $price
     * @return string
     */
    protected function formatPrice($price)
    {
        $price = trim($price);
        $price = str_replace('from', '', $price);
        $price = trim($price);

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
