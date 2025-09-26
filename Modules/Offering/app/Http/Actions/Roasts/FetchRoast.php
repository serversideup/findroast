<?php

namespace Modules\Offering\Http\Actions\Roasts;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;
use Modules\Offering\Models\OfferingImportMap;

class FetchRoast
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
        protected string $url
    ){}

    public function execute()
    {
        try {
            $this->appendSelectors();

            $html = $this->fetchUrl($this->url);

            if (empty($html)) {
                throw new Exception("Failed to fetch roast URL: {$this->url}");
            }

            $this->extractProduct($html);

            if( empty( $this->productData ) ) {
                throw new Exception("No product data found on page");
            }

            $this->cleanProduct();

            return $this->productData;
        } catch (Exception $e) {
            Log::error('Collection scraping failed', [
                'url' => $this->url,
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
        // Get all text nodes but exclude script and style content
        $textNodes = $xpath->query('.//text()[normalize-space() and not(ancestor::script) and not(ancestor::style)]', $mainContainer);

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

    protected function cleanProduct()
    {
        $this->productData['text'] = array_unique($this->productData['text'], SORT_REGULAR);
        $this->productData['images'] = array_unique($this->productData['images'], SORT_REGULAR);

        foreach ($this->productData['images'] as $image) {
            if (str_starts_with($image['src'], '//')) {
                $image['src'] = 'https:' . $image['src'];
            }

            $this->productData['images'][] = $image;
        }

        $this->productData['text'] = array_values($this->productData['text']);
        $this->productData['images'] = array_values($this->productData['images']);

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