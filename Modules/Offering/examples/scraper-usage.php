<?php

/**
 * Example usage of the UniversalCoffeeScraper class
 * 
 * This file demonstrates how to use the scraper in different scenarios:
 * 1. Scrape just a collection page
 * 2. Scrape just an individual roast page
 * 3. Scrape an entire company (collection + all roasts)
 * 4. Custom configuration options
 */

require_once __DIR__ . '/../../../../vendor/autoload.php';

use Modules\Offering\Http\Actions\Roasts\UniversalCoffeeScraper;

// Example 1: Scrape just a collection page
function scrapeCollectionExample()
{
    echo "=== Example 1: Scraping Collection Page ===\n";
    
    $scraper = new UniversalCoffeeScraper();
    $collectionUrl = 'https://example-coffee.com/collections/coffee';
    
    try {
        $result = $scraper->scrapeCollection($collectionUrl);
        
        if ($result['success']) {
            echo "✅ Found " . count($result['roasts']) . " roasts\n";
            foreach ($result['roasts'] as $roast) {
                echo "  - " . $roast['name'] . " (" . $roast['url'] . ")\n";
            }
        } else {
            echo "❌ Failed: " . $result['error'] . "\n";
        }
    } catch (Exception $e) {
        echo "❌ Exception: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
}

// Example 2: Scrape just an individual roast page
function scrapeRoastExample()
{
    echo "=== Example 2: Scraping Individual Roast Page ===\n";
    
    $scraper = new UniversalCoffeeScraper();
    $roastUrl = 'https://example-coffee.com/products/ethiopian-yirgacheffe';
    
    try {
        $result = $scraper->scrapeRoast($roastUrl);
        
        if ($result['success']) {
            $data = $result['data'];
            echo "✅ Roast Details:\n";
            echo "  Name: " . ($data['product_name'] ?? 'N/A') . "\n";
            echo "  Country: " . ($data['country'] ?? 'N/A') . "\n";
            echo "  Process: " . ($data['process'] ?? 'N/A') . "\n";
            echo "  Flavor Notes: " . (is_array($data['flavor_notes'] ?? null) ? implode(', ', $data['flavor_notes']) : ($data['flavor_notes'] ?? 'N/A')) . "\n";
        } else {
            echo "❌ Failed: " . $result['error'] . "\n";
        }
    } catch (Exception $e) {
        echo "❌ Exception: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
}

// Example 3: Scrape entire company (collection + all roasts)
function scrapeCompanyExample()
{
    echo "=== Example 3: Scraping Entire Company ===\n";
    
    $scraper = new UniversalCoffeeScraper();
    $collectionUrl = 'https://example-coffee.com/collections/coffee';
    
    try {
        echo "Starting company scrape...\n";
        $result = $scraper->scrapeCompany($collectionUrl);
        
        if ($result['success']) {
            echo "✅ Company Scraping Complete!\n";
            echo "  Total Roasts: " . $result['total_roasts'] . "\n";
            echo "  Successful: " . $result['successful_scrapes'] . "\n";
            echo "  Failed: " . $result['failed_scrapes'] . "\n";
            
            // Show first roast details
            if (!empty($result['roasts'])) {
                $firstRoast = $result['roasts'][0];
                echo "\n📝 First Roast Sample:\n";
                echo "  Name: " . ($firstRoast['product_name'] ?? $firstRoast['name'] ?? 'N/A') . "\n";
                echo "  Country: " . ($firstRoast['country'] ?? 'N/A') . "\n";
                echo "  Process: " . ($firstRoast['process'] ?? 'N/A') . "\n";
            }
        } else {
            echo "❌ Failed: " . $result['error'] . "\n";
        }
    } catch (Exception $e) {
        echo "❌ Exception: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
}

// Example 4: Custom configuration
function customConfigExample()
{
    echo "=== Example 4: Custom Configuration ===\n";
    
    $config = [
        'timeout' => 60,           // 60 second timeout
        'max_retries' => 5,        // 5 retry attempts
        'user_agent' => 'Mozilla/5.0 (Custom Coffee Bot)', // Custom user agent
        'verify_ssl' => true,      // Verify SSL certificates
    ];
    
    $scraper = new UniversalCoffeeScraper($config);
    
    // You can also set options after creation
    $scraper->setModel('gpt-4o-mini')  // Use a different model
            ->setTimeout(90);           // Override timeout
    
    echo "✅ Scraper configured with custom settings\n";
    echo "  Timeout: 90s\n";
    echo "  Max Retries: 5\n";
    echo "  Model: gpt-4o-mini\n";
    echo "  Custom User Agent: Yes\n";
    
    echo "\n";
}

// Example 5: Batch processing multiple companies
function batchProcessingExample()
{
    echo "=== Example 5: Batch Processing Multiple Companies ===\n";
    
    $companies = [
        'https://company1.com/collections/coffee',
        'https://company2.com/collections/coffee',
        'https://company3.com/collections/coffee',
    ];
    
    $scraper = new UniversalCoffeeScraper();
    $results = [];
    
    foreach ($companies as $index => $companyUrl) {
        echo "Processing company " . ($index + 1) . "/" . count($companies) . ": " . $companyUrl . "\n";
        
        try {
            $result = $scraper->scrapeCompany($companyUrl);
            $results[] = [
                'url' => $companyUrl,
                'result' => $result
            ];
            
            if ($result['success']) {
                echo "  ✅ Success: " . $result['total_roasts'] . " roasts found\n";
            } else {
                echo "  ❌ Failed: " . $result['error'] . "\n";
            }
            
            // Be respectful - wait between companies
            sleep(2);
            
        } catch (Exception $e) {
            echo "  ❌ Exception: " . $e->getMessage() . "\n";
            $results[] = [
                'url' => $companyUrl,
                'result' => ['success' => false, 'error' => $e->getMessage()]
            ];
        }
    }
    
    echo "\n📊 Batch Processing Summary:\n";
    $successful = count(array_filter($results, fn($r) => $r['result']['success']));
    echo "  Successful: " . $successful . "/" . count($companies) . "\n";
    
    echo "\n";
}

// Run examples
if (php_sapi_name() === 'cli') {
    echo "Universal Coffee Scraper Examples\n";
    echo "================================\n\n";
    
    scrapeCollectionExample();
    scrapeRoastExample();
    scrapeCompanyExample();
    customConfigExample();
    batchProcessingExample();
    
    echo "Examples completed!\n";
} else {
    echo "This file is designed to be run from the command line.\n";
    echo "Run: php examples/scraper-usage.php\n";
}
