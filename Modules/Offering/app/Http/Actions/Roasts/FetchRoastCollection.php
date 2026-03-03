<?php

namespace Modules\Offering\Http\Actions\Roasts;


use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;
use Modules\Company\Models\Company;
use Modules\Offering\Models\OfferingImportMap;
use Modules\Offering\Models\InvalidRoastUrl;

class FetchRoastCollection
{
    protected array $config = [
        'timeout' => 60,
        'max_retries' => 3,
        'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'follow_redirects' => true,
        'verify_ssl' => false, // Some sites have SSL issues
    ];

    protected array $products = [];
    protected array $processedProducts = [];
    protected array $invalidUrls = [];

    protected array $collectionContainerSelectors = [
        '//div[contains(@class, "collection-products")]',
        '//ul[contains(@class, "products")]',
        '//div[contains(@class, "products")]',
        '//div[contains(@class, "collection")]//div[contains(@class, "grid")]',
        '//div[contains(@class, "product-list")]',
        '//div[contains(@class, "product-grid")]',
        '//div[@id="product-grid"]',
        '//ul[contains(@class, "product-grid")]',
        '//div[contains(@class, "content_collection-list")]',
        '//div[contains(@id, "facets-results")]',
        '//div[contains(@class, "collection-contents")]'
    ];

    protected array $productBlockSelectors = [
        // EXACT MATCHES
        '//*[@class="product-block"]',  // Exact match for class="product-block" only
        '//*[@class="product-item"]',   // Exact match for class="product-item" only
        '//*[@class="product_content"]',
        '//*[@class="product-card"]',
        
        // PARTIAL MATCHES
        '//li[contains(@class, "grid__item")]',
        '//div[contains(@class, "product-tile") and not(ancestor::*[contains(@class, "product-tile")])]',
        '//div[contains(@class, "product-grid-item")]',
        '//*[contains(@class, "product--natural")]',
    ];

    public function __construct(
        protected Company $company,
        protected OfferingImportMap $importMap
    ) {}

    /**
     * Scrape a collection URL to get individual roast URLs
     */
    public function execute(): array
    {
        try {
            // Load invalid URLs for this company
            $this->invalidUrls = InvalidRoastUrl::where('company_id', $this->company->id)
                ->pluck('url')
                ->toArray();

            $this->appendSelectors();

            $html = $this->fetchUrl($this->importMap->collection_url);

            if (empty($html)) {
                throw new Exception("Failed to fetch collection URL: {$this->importMap->collection_url}");
            }

            // Extract only relevant product sections to reduce tokens
            $this->extractProductCollection($html);
            
            if (empty($this->products)) {
                throw new Exception("No relevant product content found on page");
            }

            $this->cleanProducts();

            return $this->products;
        } catch (Exception $e) {
            Log::error('Collection scraping failed', [
                'url' => $this->importMap->collection_url,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'roasts' => []
            ];
        }
    }

    protected function appendSelectors()
    {
        array_unshift( 
            $this->collectionContainerSelectors,
            $this->importMap->container_selector
        );

        array_unshift(
            $this->productBlockSelectors,
            $this->importMap->product_list_item_selector
        );
    }

    /**
     * Extract only relevant product content from collection page
     */
    protected function extractProductCollection(string $html)
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING);
        $xpath = new \DOMXPath($dom);

        
        // Try to find the main product container first
        $mainContainer = null;

        foreach ($this->collectionContainerSelectors as $key => $selector) {
            $containers = $xpath->query($selector);

            if ($containers->length > 0) {
                $mainContainer = $containers->item(0);
                break;
            }
        }

        // If we found a main container, look for individual products within it
        if ($mainContainer) {
            $this->extractProducts($mainContainer, $xpath);
        }
    }

    protected function extractProducts(\DOMNode $mainContainer, \DOMXPath $xpath): array
    {
        foreach ($this->productBlockSelectors as $selector) {
            $productNodes = $xpath->query($selector, $mainContainer);
            
            if ($productNodes->length > 0) {
                break;
            }
        }

        if ($productNodes->length > 0) {
            foreach ($productNodes as $product) {
                $productData = [
                    'links' => [],
                    'text' => [],
                    'images' => []
                ];
                
                // Remove all class attributes from elements within this product
                $elements = $xpath->query('.//*[@class]', $product);
                foreach ($elements as $element) {
                    $element->removeAttribute('class');
                }
                
                // Step 1: Extract all text nodes
                $textNodes = $xpath->query('.//text()[normalize-space()]', $product);
                foreach ($textNodes as $node) {
                    $text = trim($node->nodeValue);
                        
                    if (!empty($text)) {
                        $productData['text'][] = $this->cleanText($text);
                    }

                }

                // Step 2: Extract all images (src attributes)
                $images = $xpath->query('.//img', $product);
                foreach ($images as $img) {
                    $imageData = [];

                    $src = $img->getAttribute('src');
                    
                    if ($src) {
                        $imageData['src'] = $src;
                    }

                    $productData['images'][] = $imageData;
                }

                // Step 3: Extract all links (href and text)
                $links = $xpath->query('.//a', $product);
                foreach ($links as $link) {
                    // Get the href attribute
                    $href = $link->getAttribute('href');
                    $linkText = trim($link->textContent);

                    // If the href is not a '#', add it to the product data
                    if ($href && $href != '#') {
                        $productData['links'][] = $href;

                        if ($linkText) {
                            $productData['text'][] = $this->cleanText($linkText);
                        }
                    }
                }

                $this->products[] = $productData;
            }
        }

        return $this->products;
    }

    protected function cleanProducts()
    {
        foreach ($this->products as $key => $product) {
            // Remove duplicates from array
            $text = array_unique($product['text'], SORT_REGULAR);
            $images = array_unique($product['images'], SORT_REGULAR);
            $links = array_unique($product['links'], SORT_REGULAR);

            // Convert // to https://
            foreach ( $images as $imageKey => $image ) {
                if (str_starts_with($image['src'], '//')) {
                    $image['src'] = 'https:' . $image['src'];
                }

                $images[$imageKey] = $image;
            }

            foreach ( $links as $linkKey => $link ) {
                if( trim( $link ) == '' ) {
                    unset( $links[$linkKey] );
                } else {
                    if (str_starts_with( $link, '/')) {
                        $link = $this->company->website . ltrim( $link, "/" );
                    }

                    $links[$linkKey] = $link;
                }
            }

            $product['text'] = $text;
            $product['images'] = $images;
            $product['links'] = array_values($links);

            // Check if any link in this product is marked as invalid
            $hasInvalidUrl = false;
            foreach ($product['links'] as $link) {
                if (in_array($link, $this->invalidUrls)) {
                    $hasInvalidUrl = true;
                    break;
                }
            }

            // Skip this product if it contains an invalid URL
            if ($hasInvalidUrl) {
                unset($this->products[$key]);
                continue;
            }

            $this->products[$key] = $product;
        }

        // Re-index array after unsetting products
        $this->products = array_values($this->products);

        return true;
    }

    /**
     * Clean and normalize text content
     */
    protected function cleanText(string $text): string
    {
        // Remove extra whitespace and normalize
        $text = preg_replace('/\s+/', ' ', trim($text));
        
        // Remove common HTML entities
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        
        // Remove any remaining HTML tags
        $text = strip_tags($text);
        
        return trim($text);
    }

    /**
     * Fetch URL content with retry logic and error handling
     */
    protected function fetchUrl(string $url): string
    {
        $attempts = 0;
        
        while ($attempts < $this->config['max_retries']) {
            try {
                $response = Http::timeout(60)
                    ->withHeaders([
                        'User-Agent' => $this->config['user_agent'],
                        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                        'Accept-Language' => 'en-US,en;q=0.5',
                        'Accept-Encoding' => 'gzip, deflate',
                        'Connection' => 'keep-alive',
                        'Upgrade-Insecure-Requests' => '1',
                    ])
                    ->withOptions([
                        'verify' => $this->config['verify_ssl'],
                        'follow_redirects' => $this->config['follow_redirects'],
                    ])
                    ->get($url);

                if ($response->successful()) {
                    return $response->body();
                }

                Log::warning("HTTP request failed", [
                    'url' => $url,
                    'status' => $response->status(),
                    'attempt' => $attempts + 1
                ]);

            } catch (Exception $e) {
                Log::warning("Request exception", [
                    'url' => $url,
                    'error' => $e->getMessage(),
                    'attempt' => $attempts + 1
                ]);
            }

            $attempts++;
            
            if ($attempts < $this->config['max_retries']) {
                sleep(pow(2, $attempts)); // Exponential backoff
            }
        }

        throw new Exception("Failed to fetch URL after {$this->config['max_retries']} attempts: {$url}");
    }
}
