# Universal Coffee Scraper

A Laravel-based universal coffee scraping solution that uses OpenAI's API to extract coffee roast information from any website without manual selectors.

## Features

- **Universal Compatibility**: Works with any coffee company website (Shopify, WooCommerce, custom sites)
- **AI-Powered Extraction**: Uses OpenAI's GPT-4o to intelligently parse HTML content
- **Two-Phase Scraping**: 
  1. Collection page scraping to find individual roast URLs
  2. Individual roast page scraping for detailed information
- **Comprehensive Data Extraction**: Captures flavor notes, countries, varieties, elevations, processes, and more
- **Respectful Scraping**: Built-in delays and retry logic to be respectful to websites
- **Flexible Configuration**: Customizable timeouts, retries, user agents, and OpenAI models
- **Error Handling**: Robust error handling with detailed logging

## Requirements

- Laravel 11+
- PHP 8.2+
- OpenAI API key configured in your `.env` file
- `openai-php/laravel` package (already installed in your project)

## Setup

1. **Environment Variables**: Ensure your OpenAI API key is set in `.env`:
   ```env
   OPENAI_API_KEY=your_api_key_here
   ```

2. **Configuration**: The scraper is ready to use out of the box with sensible defaults.

## Usage

### Basic Usage

```php
use Modules\Offering\Http\Actions\Roasts\UniversalCoffeeScraper;

// Initialize scraper
$scraper = new UniversalCoffeeScraper();

// Scrape an entire company (recommended)
$result = $scraper->scrapeCompany('https://coffee-company.com/collections/coffee');

// Or scrape individual components
$collectionData = $scraper->scrapeCollection('https://coffee-company.com/collections/coffee');
$roastData = $scraper->scrapeRoast('https://coffee-company.com/products/ethiopian-yirgacheffe');
```

### Command Line Usage

Use the included Artisan command for easy scraping:

```bash
# Basic scraping
php artisan coffee:scrape-company "https://coffee-company.com/collections/coffee"

# With custom configuration
php artisan coffee:scrape-company "https://coffee-company.com/collections/coffee" \
  --config='{"timeout": 60, "max_retries": 5}'

# Save results to file
php artisan coffee:scrape-company "https://coffee-company.com/collections/coffee" \
  --output="results.json"
```

### Custom Configuration

```php
$config = [
    'timeout' => 60,           // HTTP timeout in seconds
    'max_retries' => 5,        // Maximum retry attempts
    'user_agent' => 'Custom Bot', // Custom user agent
    'verify_ssl' => true,      // SSL verification
    'follow_redirects' => true // Follow HTTP redirects
];

$scraper = new UniversalCoffeeScraper($config);

// Or set options after creation
$scraper->setModel('gpt-4o-mini')
        ->setTimeout(90)
        ->setMaxRetries(3);
```

## Data Structure

### Collection Response

```json
{
  "success": true,
  "roasts": [
    {
      "name": "Ethiopian Yirgacheffe",
      "url": "https://coffee-company.com/products/ethiopian-yirgacheffe",
      "description": "Light roasted single origin coffee",
      "price": "18.99",
      "image_url": "https://coffee-company.com/image.jpg"
    }
  ]
}
```

### Individual Roast Response

```json
{
  "success": true,
  "data": {
    "product_name": "Ethiopian Yirgacheffe",
    "brand": "Coffee Company",
    "price": "18.99",
    "weight": "12 oz",
    "roast_level": "Light",
    "country": "Ethiopia",
    "region": "Yirgacheffe",
    "farm": "Guji Highlands",
    "farmer": "Local Farmers",
    "varieties": ["Heirloom"],
    "elevation": "1800-2200m",
    "process": "Washed",
    "flavor_notes": ["Jasmine", "Bergamot", "Lemon"],
    "acidity": "Bright",
    "body": "Medium",
    "roast_date": "2024-01-15",
    "best_before": "2025-01-15",
    "certifications": ["Organic"],
    "description": "Complex and floral coffee..."
  }
}
```

### Company Scraping Response

```json
{
  "success": true,
  "company_url": "https://coffee-company.com/collections/coffee",
  "total_roasts": 15,
  "successful_scrapes": 14,
  "failed_scrapes": 1,
  "roasts": [...],
  "errors": [
    {
      "roast": {"name": "Failed Roast", "url": "..."},
      "error": "Error message"
    }
  ]
}
```

## OpenAI Model Recommendations

- **GPT-4o** (default): Best for complex HTML parsing and accurate extraction
- **GPT-4o-mini**: Faster and cheaper, good for simpler sites
- **GPT-3.5-turbo**: Most economical, suitable for basic extraction

## Best Practices

### 1. Rate Limiting
- The scraper includes built-in delays (0.5s between roasts)
- Consider adding longer delays between different companies
- Respect robots.txt files when available

### 2. Error Handling
- Always check the `success` field in responses
- Handle failed scrapes gracefully
- Log errors for debugging

### 3. Configuration
- Adjust timeouts based on website responsiveness
- Use appropriate retry counts for unreliable sites
- Consider using custom user agents for different sites

### 4. Data Validation
- Validate extracted data before storing
- Handle missing or incomplete information
- Normalize data formats (e.g., elevation units, country names)

## Example Workflows

### Single Company Scraping
```php
$scraper = new UniversalCoffeeScraper();
$result = $scraper->scrapeCompany('https://coffee-company.com/collections/coffee');

if ($result['success']) {
    foreach ($result['roasts'] as $roast) {
        // Store roast data in your database
        $this->storeRoast($roast);
    }
}
```

### Batch Processing
```php
$companies = [
    'https://company1.com/collections/coffee',
    'https://company2.com/collections/coffee',
    'https://company3.com/collections/coffee'
];

$scraper = new UniversalCoffeeScraper();
$results = [];

foreach ($companies as $companyUrl) {
    $result = $scraper->scrapeCompany($companyUrl);
    $results[] = $result;
    
    // Wait between companies
    sleep(5);
}
```

### Custom Data Extraction
```php
// If you need to extract specific fields only
$scraper = new UniversalCoffeeScraper();
$roastData = $scraper->scrapeRoast('https://coffee-company.com/products/coffee');

if ($roastData['success']) {
    $data = $roastData['data'];
    
    // Extract only what you need
    $flavorNotes = $data['flavor_notes'] ?? [];
    $country = $data['country'] ?? null;
    $process = $data['process'] ?? null;
}
```

## Troubleshooting

### Common Issues

1. **OpenAI API Errors**
   - Check your API key and billing
   - Verify the model name is correct
   - Check token limits for very long HTML

2. **HTTP Request Failures**
   - Increase timeout values
   - Check if the site is blocking bots
   - Verify SSL settings

3. **Data Extraction Issues**
   - Try different OpenAI models
   - Check if the HTML structure is unusual
   - Verify the prompts are clear

### Debugging

Enable detailed logging in your Laravel configuration:

```php
// In config/logging.php
'channels' => [
    'daily' => [
        'driver' => 'daily',
        'path' => storage_path('logs/laravel.log'),
        'level' => 'debug',
        'days' => 14,
    ],
],
```

The scraper logs all HTTP requests, OpenAI API calls, and errors for debugging.

## Ethical Considerations

- **Respect robots.txt**: Check if scraping is allowed
- **Rate limiting**: Don't overwhelm websites with requests
- **Terms of service**: Ensure compliance with website terms
- **Data usage**: Only collect publicly available information
- **Attribution**: Consider giving credit to data sources

## Performance Tips

- Use `gpt-4o-mini` for faster processing
- Implement caching for repeated requests
- Process companies in parallel (with rate limiting)
- Store results incrementally to avoid data loss

## Contributing

This scraper is designed to be extensible. You can:

- Customize prompts for specific data extraction needs
- Add new data fields to the extraction
- Implement custom validation logic
- Add support for different content types (images, PDFs)

## License

This scraper is part of your Laravel project and follows the same license terms.
