<?php

namespace Modules\Offering\Http\Actions\Roasts;

use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;
use Modules\Offering\Models\OfferingImportMap;
use Modules\Offering\Models\Roast;

class ScrapeRoast
{
    protected array $config = [
        'timeout' => 30,
        'max_retries' => 3,
        'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'follow_redirects' => true,
        'verify_ssl' => false, // Some sites have SSL issues
    ];

    protected array $productSelectors = [

    ];

    // AI settings
    protected string $openaiModel = 'gpt-4o';

    protected array $productData = [];

    public function __construct(
        protected OfferingImportMap $importMap,
        protected Roast $roast
    ){}

    public function execute()
    {
        try {
            $this->appendSelectors();

            $html = $this->fetchUrl($this->roast->url);

            if (empty($html)) {
                throw new Exception("Failed to fetch roast URL: {$this->roast->url}");
            }

            $this->extractProduct($html);

            if( empty( $this->productData ) ) {
                throw new Exception("No product data found on page");
            }

            $prompt = $this->buildPrompt();
            $response = $this->sendToOpenAI($prompt);

            return $response;

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
            $this->productSelectors,
            $this->importMap->product_selector
        );
    }

    protected function extractProduct(string $html)
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING);
        $xpath = new \DOMXPath($dom);
        
        $mainContainer = null;

        foreach ($this->productSelectors as $key => $selector) {
            $containers = $xpath->query($selector);

            if ($containers->length > 0) {
                $mainContainer = $containers->item(0);
                break;
            }
        }

        if ($mainContainer) {
            $this->extractProductData($mainContainer, $xpath);
        }
    }

    protected function extractProductData(\DOMNode $mainContainer, \DOMXPath $xpath): array
    {
        $this->productData = [
            'text' => [],
            'images' => [],
        ];

        // Remove all class attributes from elements within this product
        $elements = $xpath->query('.//*[@class]', $mainContainer);
        foreach ($elements as $element) {
            $element->removeAttribute('class');
        }

        // Step 1: Extract all text nodes
        $textNodes = $xpath->query('.//text()[normalize-space()]', $mainContainer);
        foreach ($textNodes as $node) {
            $text = trim($node->nodeValue);
                
            if (!empty($text)) {
                $this->productData['text'][] = $this->cleanText($text);
            }
        }

        // Step 2: Extract all images (src attributes)
        $images = $xpath->query('.//img', $mainContainer);
        foreach ($images as $img) {
            $imageData = [];

            $src = $img->getAttribute('src');
            
            if ($src) {
                $imageData['src'] = $src;
            }

            $this->productData['images'][] = $imageData;
        }

        return $this->productData;
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

    protected function buildPrompt()
    {
        $prompt = "The provided data is the scrape of an individual coffee product from a coffee company that sells their own roasts.
I've collected all the text & images from the product page and sent them to you in the JSON provided:
```
".json_encode($this->productData)."
```

From the JSON provided, extract the following data:
- The flavor notes of the roast
- The processes of the roast
- The countries of the roast
- The varieties of the roast
- The elevations of the roast
- The roast level of the roast

Please return the data in the following format:
```
{
    \"flavor_notes\": (array)\"Flavor notes of the roast\",
    \"processes\": (array)\"Processes of the roast\",
    \"countries\": (array)\"Countries of the roast\",
    \"varieties\": (array)\"Varieties of the roast\",
    \"elevations\": (array)\"Elevations of the roast\",
    \"roast_level\": (string)\"Roast level of the roast\"
}
```

Return null, empty string, or empty array for each JSON value if it's not found.";

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
}