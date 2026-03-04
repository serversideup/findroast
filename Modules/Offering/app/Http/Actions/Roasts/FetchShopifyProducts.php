<?php

namespace Modules\Offering\Http\Actions\Roasts;

use Modules\Company\Models\Company;
use Modules\Offering\Models\OfferingImportMap;
use Modules\Offering\Models\InvalidRoastUrl;
use Illuminate\Support\Facades\Http;

class FetchShopifyProducts
{
    protected $pages = 4;
    protected array $products = [];
    protected string $url;
    protected array $productTypes;
    protected array $tagsInclude;
    protected array $tagsExclude;
    protected array $invalidUrls = [];

    public function __construct(
        protected Company $company,
        protected OfferingImportMap $importMap
    ){}

    public function execute(): array
    {
        $this->productTypes = $this->importMap->shopify_product_types ? explode(',', $this->importMap->shopify_product_types) : [];
        $this->tagsInclude = $this->importMap->shopify_tags_include ? explode(',', $this->importMap->shopify_tags_include) : [];
        $this->tagsExclude = $this->importMap->shopify_tags_exclude ? explode(',', $this->importMap->shopify_tags_exclude) : [];

        // Load invalid URLs for this company
        $this->invalidUrls = InvalidRoastUrl::where('company_id', $this->company->id)->pluck('url')->toArray();


        $this->buildUrl();

        for($i = 1; $i <= $this->pages; $i++){
            $pagedUrl = $this->url . '&page='.$i;

            $fetchedProducts = $this->fetchProducts($pagedUrl);
            $filteredProducts = $this->filterProducts($fetchedProducts);

            $this->products = array_merge($this->products, $filteredProducts);
        }

        return $this->products;
    }

    protected function buildUrl()
    {
        if( $this->importMap->shopify_collection_url != '' ){
            $this->url = $this->importMap->shopify_collection_url.'?limit=250';
        }else{
            $this->url = $this->company->website.'products.json?limit=250';
        }
    }

    protected function fetchProducts(string $url)
    {
        $response = Http::get($url);
        $data = $response->json();

        return $data['products'];
    }

    protected function filterProducts(array $products)
    {
        $filteredProducts = [];

        foreach( $products as $product ){
            $instock = false;
            $validType = false;
            $validIncludeTags = false;
            $validExcludeTags = true;

            foreach( $product['variants'] as $variant ){
                if( $variant['available'] ){
                    $instock = true;
                }
            }

            if( count($this->productTypes) > 0 && in_array($product['product_type'], $this->productTypes) ){
                $validType = true;
            }else if( count($this->productTypes) == 0 ){
                $validType = true;
            }

            if( count($product['tags']) > 0 ){
                foreach( $product['tags'] as $tag ){
                    if( count($this->tagsInclude) > 0 && in_array($tag, $this->tagsInclude) ){
                        $validIncludeTags = true;
                    }else if( count($this->tagsInclude) == 0 ){
                        $validIncludeTags = true;
                    }
                }
            }else{
                $validIncludeTags = true;
            }

            if( count($product['tags']) > 0 ){
                foreach( $product['tags'] as $tag ){
                    if( count($this->tagsExclude) > 0 && in_array($tag, $this->tagsExclude) ){
                        $validExcludeTags = false;
                    }else if( count($this->tagsExclude) == 0 ){
                        $validExcludeTags = true;
                    }
                }
            }else{
                $validExcludeTags = true;
            }

            if( $instock && $validType && $validIncludeTags && $validExcludeTags ){
                $imageUrls = [];

                foreach( $product['images'] as $image ){
                    $imageUrls[] = [
                        'src' => $image['src'],
                    ];
                }

                $productUrl = $this->company->website.'products/'.$product['handle'];

                // Skip if URL is marked as invalid
                if( in_array($productUrl, $this->invalidUrls) ){
                    continue;
                }

                $shopifyProduct = [
                    'url' => $productUrl,
                    'price' => isset($product['variants'][0]['price']) ? $product['variants'][0]['price'] : '',
                    'currency' => isset($product['variants'][0]['currency_code']) ? $product['variants'][0]['currency_code'] : null,
                    'name' => $product['title'],
                    'images' => $imageUrls,
                    'rawText' => strip_tags($product['body_html']),
                    'in_stock' => $instock,
                ];

                $filteredProducts[] = $shopifyProduct;
            }
        }

        return $filteredProducts;
    }
}