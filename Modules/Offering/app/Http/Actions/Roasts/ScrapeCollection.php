<?php

namespace Modules\Offering\Http\Actions\Roasts;

use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;
use Modules\Company\Models\Company;
use Modules\Offering\Models\OfferingImportMap;

class ScrapeCollection
{
    protected array $config = [
        'timeout' => 30,
        'max_retries' => 3,
        'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'follow_redirects' => true,
        'verify_ssl' => false, // Some sites have SSL issues
    ];

    protected array $products = [];

    // AI settings
    protected string $openaiModel = 'gpt-4o';

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

            $prompt = $this->buildPrompt();
            $response = $this->sendToOpenAI($prompt);

            $validated = $this->validateCollectionResponse($response);

            if( !$validated['success'] ){
                throw new Exception($validated['error']);
            }

            return $validated['roasts'];

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
                    $linkData = [];

                    // Get the href attribute
                    $href = $link->getAttribute('href');
                    $linkText = trim($link->textContent);

                    // If the href is not a '#', add it to the product data
                    if ($href && $href != '#') {
                        $linkData['href'] = $href;

                        if ($linkText) {
                            $linkData['text'] = $this->cleanText($linkText);
                        }
                    }

                    $productData['links'][] = $linkData;
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
                if (str_starts_with( $link['href'], '/')) {
                    $link['href'] = $this->company->website . ltrim( $link['href'], "/" );
                }

                $links[$linkKey] = $link;
            }

            $product['text'] = $text;
            $product['images'] = $images;
            $product['links'] = $links;

            $this->products[$key] = $product;
        }

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
                $response = Http::timeout($this->config['timeout'])
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

    /**
     * Build prompt for collection scraping
     */
    protected function buildPrompt(): string
    {
        $prompt = "The JSON is an array of data from a coffee company that sells their own roasts. Each item in the array is a roast.
I've collected all the text, images, and links from the product listing in the JSON provided:
```
".json_encode($this->products)."
```

From the JSON provided, extract the following data from EACH roast in the array:
- The name of the roast
- The URL of the roast
- The Price of the roast (if found)
- Whether the roast is in stock or not
- The product image of the roast 
- The flavor notes of the roast
- The processes of the roast
- The countries of the roast

Please return the data in the following format:
```
{
    \"roasts\": [
        {
            \"name\": (string)\"Product Name\",
            \"url\": (string)\"Product URL\",
            \"price\": (float)\"Product price\",
            \"in_stock\": (boolean)\"Whether the product is in stock or not.\",
            \"image\": (string)\"URL of the product image\",
            \"flavor_notes\": (array)\"Flavor notes of the roast\",
            \"processes\": (array)\"Processes of the roast\",
            \"countries\": (array)\"Countries of the roast\"
        }
    ]
}
```
Return null, empty string, or empty array for each JSON value if it's not found.

IMPORTANT: Process ALL items in the input array and return an array containing ALL of the roasts found.";

        return $prompt;
    }

    /**
     * Send prompt to OpenAI API with retry logic and token management
     */
    protected function sendToOpenAI(string $prompt): array
    {
        $attempts = 0;
        
        while ($attempts < $this->config['max_retries']) {
            try {                
                $result = OpenAI::chat()->create([
                    'model' => $this->openaiModel,
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'response_format' => ['type' => 'json_object'],
                    'temperature' => 0.1, // Low temperature for consistent extraction
                ]);

                $content = $result->choices[0]->message->content;
                $decoded = json_decode($content, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception("Invalid JSON response from OpenAI: " . json_last_error_msg());
                }

                return $decoded;

            } catch (Exception $e) {
                $attempts++;
                
                // If it's a token limit error, try with shorter content
                if (strpos($e->getMessage(), 'Request too large') !== false && $attempts < $this->config['max_retries']) {
                    Log::warning("Token limit exceeded, retrying with shorter content", [
                        'attempt' => $attempts,
                        'prompt_length' => strlen($prompt)
                    ]);
                    
                    // Truncate prompt further
                    $prompt = substr($prompt, 0, 2000);
                    continue;
                }
                
                if ($attempts >= $this->config['max_retries']) {
                    Log::error('OpenAI API call failed after retries', [
                        'error' => $e->getMessage(),
                        'prompt_length' => strlen($prompt),
                        'attempts' => $attempts
                    ]);
                    
                    throw new Exception("OpenAI API call failed after {$attempts} attempts: " . $e->getMessage());
                }
                
                // Wait before retry
                sleep(pow(2, $attempts));
            }
        }

        throw new Exception("Failed to get response from OpenAI after {$this->config['max_retries']} attempts");
    }

    /**
     * Validate collection response
     */
    protected function validateCollectionResponse(array $response): array
    {
        if (!isset($response['roasts']) || !is_array($response['roasts'])) {
            return [
                'success' => false,
                'error' => 'Invalid response format from OpenAI',
                'roasts' => []
            ];
        }

        return [
            'success' => true,
            'roasts' => $response['roasts']
        ];
    }
}
