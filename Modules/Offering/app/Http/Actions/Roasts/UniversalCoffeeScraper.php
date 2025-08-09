<?php

namespace Modules\Offering\Http\Actions\Roasts;

use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class UniversalCoffeeScraper
{
    protected string $openaiModel = 'gpt-4o';
    protected int $maxRetries = 3;
    protected int $timeout = 30;
    protected int $maxTokensPerRequest = 3000; // Reduced token limit
    
    // User agent to avoid being blocked
    protected string $userAgent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';

    public function __construct(
        protected array $config = []
    ) {
        $this->config = array_merge([
            'timeout' => $this->timeout,
            'max_retries' => $this->maxRetries,
            'user_agent' => $this->userAgent,
            'follow_redirects' => true,
            'verify_ssl' => false, // Some sites have SSL issues
        ], $config);
    }

    /**
     * Scrape a collection URL to get individual roast URLs
     */
    public function scrapeCollection(string $collectionUrl): array
    {
        try {
            $html = $this->fetchUrl($collectionUrl);
            
            if (empty($html)) {
                throw new Exception("Failed to fetch collection URL: {$collectionUrl}");
            }

            // Extract only relevant product sections to reduce tokens
            $relevantContent = $this->extractRelevantProductContent($html);
            
            if (empty($relevantContent)) {
                throw new Exception("No relevant product content found on page");
            }

            $prompt = $this->buildCollectionPrompt($relevantContent, $collectionUrl);
            $response = $this->sendToOpenAI($prompt);

            return $this->validateCollectionResponse($response);

        } catch (Exception $e) {
            Log::error('Collection scraping failed', [
                'url' => $collectionUrl,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'roasts' => []
            ];
        }
    }

    /**
     * Scrape an individual roast URL to get detailed information
     */
    public function scrapeRoast(string $roastUrl): array
    {
        try {
            $html = $this->fetchUrl($roastUrl);
            
            if (empty($html)) {
                throw new Exception("Failed to fetch roast URL: {$roastUrl}");
            }

            // Extract only relevant content sections
            $relevantContent = $this->extractRelevantRoastContent($html);
            
            if (empty($relevantContent)) {
                throw new Exception("No relevant roast content found on page");
            }

            $prompt = $this->buildRoastPrompt($relevantContent, $roastUrl);
            $response = $this->sendToOpenAI($prompt);

            return $this->validateRoastResponse($response);

        } catch (Exception $e) {
            Log::error('Roast scraping failed', [
                'url' => $roastUrl,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Extract only relevant product content from collection page
     */
    protected function extractRelevantProductContent(string $html): string
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING);
        $xpath = new \DOMXPath($dom);
        
        $relevantContent = [];
        
        // Look for common product container patterns
        $productSelectors = [
            '//div[contains(@class, "product")]',
            '//div[contains(@class, "item")]',
            '//div[contains(@class, "card")]',
            '//article[contains(@class, "product")]',
            '//li[contains(@class, "product")]',
            '//div[contains(@class, "grid")]//div[contains(@class, "item")]'
        ];
        
        foreach ($productSelectors as $selector) {
            $elements = $xpath->query($selector);
            if ($elements->length > 0) {
                foreach ($elements as $element) {
                    $text = $this->cleanText($element->textContent);
                    if (strlen($text) > 20) { // Only meaningful content
                        $relevantContent[] = $text;
                    }
                }
            }
        }
        
        // If no specific product containers found, try to extract general product info
        if (empty($relevantContent)) {
            $relevantContent = $this->extractGeneralProductInfo($xpath);
        }
        
        // Limit content to prevent token overflow
        $combinedContent = implode("\n\n", array_slice($relevantContent, 0, 10));
        
        // Ensure we don't exceed token limits
        if (strlen($combinedContent) > 2000) {
            $combinedContent = substr($combinedContent, 0, 2000);
        }
        
        return $combinedContent;
    }

    /**
     * Extract general product information when specific containers aren't found
     */
    protected function extractGeneralProductInfo(\DOMXPath $xpath): array
    {
        $content = [];
        
        // Look for headings that might contain product names
        $headings = $xpath->query('//h1 | //h2 | //h3 | //h4');
        foreach ($headings as $heading) {
            $text = $this->cleanText($heading->textContent);
            if (strlen($text) > 5 && strlen($text) < 100) {
                $content[] = "Heading: " . $text;
            }
        }
        
        // Look for paragraphs that might contain product descriptions
        $paragraphs = $xpath->query('//p');
        foreach ($paragraphs as $paragraph) {
            $text = $this->cleanText($paragraph->textContent);
            if (strlen($text) > 30 && strlen($text) < 200) {
                $content[] = $text;
            }
        }
        
        return array_slice($content, 0, 5); // Limit to 5 items
    }

    /**
     * Extract only relevant content from individual roast page
     */
    protected function extractRelevantRoastContent(string $html): string
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING);
        $xpath = new \DOMXPath($dom);
        
        $relevantContent = [];
        
        // Extract product title
        $titleSelectors = [
            '//h1[contains(@class, "product")]',
            '//h1[contains(@class, "title")]',
            '//h1',
            '//title'
        ];
        
        foreach ($titleSelectors as $selector) {
            $elements = $xpath->query($selector);
            if ($elements->length > 0) {
                $text = $this->cleanText($elements->item(0)->textContent);
                if (!empty($text)) {
                    $relevantContent[] = "Title: " . $text;
                    break;
                }
            }
        }
        
        // Extract product descriptions
        $descSelectors = [
            '//div[contains(@class, "description")]',
            '//div[contains(@class, "details")]',
            '//div[contains(@class, "content")]',
            '//p[contains(@class, "description")]',
            '//p'
        ];
        
        foreach ($descSelectors as $selector) {
            $elements = $xpath->query($selector);
            foreach ($elements as $element) {
                $text = $this->cleanText($element->textContent);
                if (strlen($text) > 20 && strlen($text) < 300) {
                    $relevantContent[] = $text;
                }
            }
        }
        
        // Extract product specifications
        $specSelectors = [
            '//div[contains(@class, "specs")]',
            '//div[contains(@class, "attributes")]',
            '//ul[contains(@class, "features")]',
            '//table[contains(@class, "specs")]'
        ];
        
        foreach ($specSelectors as $selector) {
            $elements = $xpath->query($selector);
            foreach ($elements as $element) {
                $text = $this->cleanText($element->textContent);
                if (strlen($text) > 20) {
                    $relevantContent[] = "Specs: " . $text;
                }
            }
        }
        
        // Limit content to prevent token overflow
        $combinedContent = implode("\n\n", array_slice($relevantContent, 0, 8));
        
        // Ensure we don't exceed token limits
        if (strlen($combinedContent) > 2500) {
            $combinedContent = substr($combinedContent, 0, 2500);
        }
        
        return $combinedContent;
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
        
        return $text;
    }

    /**
     * Scrape both collection and individual roasts in sequence
     */
    public function scrapeCompany(string $collectionUrl): array
    {
        try {
            // First, scrape the collection to get roast URLs
            $collectionData = $this->scrapeCollection($collectionUrl);
            
            if (!$collectionData['success']) {
                return $collectionData;
            }

            $roasts = [];
            $errors = [];

            // Then scrape each individual roast
            foreach ($collectionData['roasts'] as $roast) {
                if (empty($roast['url'])) {
                    continue;
                }

                $roastData = $this->scrapeRoast($roast['url']);
                
                if ($roastData['success']) {
                    $roasts[] = array_merge($roast, $roastData['data']);
                } else {
                    $errors[] = [
                        'roast' => $roast,
                        'error' => $roastData['error']
                    ];
                }

                // Be respectful - add a small delay between requests
                usleep(500000); // 0.5 seconds
            }

            return [
                'success' => true,
                'company_url' => $collectionUrl,
                'total_roasts' => count($roasts),
                'successful_scrapes' => count($roasts),
                'failed_scrapes' => count($errors),
                'roasts' => $roasts,
                'errors' => $errors
            ];

        } catch (Exception $e) {
            Log::error('Company scraping failed', [
                'url' => $collectionUrl,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'roasts' => []
            ];
        }
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
    protected function buildCollectionPrompt(string $content, string $url): string
    {
        return "Extract coffee product information from this content:

URL: {$url}

Content:
{$content}

Extract each coffee product with:
- name: Product name
- url: Product URL (if found)
- price: Price (if found)
- description: Brief description (if found)

Return as JSON:
{
  \"roasts\": [
    {
      \"name\": \"Product Name\",
      \"url\": \"https://example.com/product-url\",
      \"price\": \"XX.XX\",
      \"description\": \"Product description\"
    }
  ]
}

If information is missing, use null. Focus on finding all coffee products.";
    }

    /**
     * Build prompt for individual roast scraping
     */
    protected function buildRoastPrompt(string $content, string $url): string
    {
        return "Extract coffee details from this content:

URL: {$url}

Content:
{$content}

Extract:
- product_name: Coffee name
- price: Price
- weight: Weight/quantity
- roast_level: Roast level
- country: Origin country
- region: Region
- varieties: Coffee varieties
- elevation: Elevation
- process: Processing method
- flavor_notes: Flavor notes
- description: Full description

Return as JSON with these fields. Use null if not found.";
    }

    /**
     * Send prompt to OpenAI API with retry logic and token management
     */
    protected function sendToOpenAI(string $prompt): array
    {
        $attempts = 0;
        
        while ($attempts < $this->maxRetries) {
            try {
                // Check if prompt is too long and truncate if necessary
                if (strlen($prompt) > 3000) {
                    $prompt = substr($prompt, 0, 3000);
                }
                
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
                    'max_tokens' => 2000, // Reduced token limit
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
                if (strpos($e->getMessage(), 'Request too large') !== false && $attempts < $this->maxRetries) {
                    Log::warning("Token limit exceeded, retrying with shorter content", [
                        'attempt' => $attempts,
                        'prompt_length' => strlen($prompt)
                    ]);
                    
                    // Truncate prompt further
                    $prompt = substr($prompt, 0, 2000);
                    continue;
                }
                
                if ($attempts >= $this->maxRetries) {
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

        throw new Exception("Failed to get response from OpenAI after {$this->maxRetries} attempts");
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

    /**
     * Validate roast response
     */
    protected function validateRoastResponse(array $response): array
    {
        if (empty($response)) {
            return [
                'success' => false,
                'error' => 'Empty response from OpenAI',
                'data' => []
            ];
        }

        return [
            'success' => true,
            'data' => $response
        ];
    }

    /**
     * Set configuration options
     */
    public function setConfig(array $config): self
    {
        $this->config = array_merge($this->config, $config);
        return $this;
    }

    /**
     * Set OpenAI model
     */
    public function setModel(string $model): self
    {
        $this->openaiModel = $model;
        return $this;
    }

    /**
     * Set timeout
     */
    public function setTimeout(int $timeout): self
    {
        $this->config['timeout'] = $timeout;
        return $this;
    }

    /**
     * Set max retries
     */
    public function setMaxRetries(int $maxRetries): self
    {
        $this->config['max_retries'] = $maxRetries;
        return $this;
    }
}
