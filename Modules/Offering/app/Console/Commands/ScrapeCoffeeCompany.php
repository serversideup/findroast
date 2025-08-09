<?php

namespace Modules\Offering\App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Modules\Offering\Http\Actions\Roasts\UniversalCoffeeScraper;

class ScrapeCoffeeCompany extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coffee:scrape-company 
                            {url : The collection URL of the coffee company}
                            {--config= : JSON configuration options}
                            {--output= : Output file path for results}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape a coffee company\'s collection and individual roast pages using OpenAI';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = $this->argument('url');
        $configJson = $this->option('config');
        $outputFile = $this->option('output');

        $this->info("Starting to scrape coffee company: {$url}");

        // Parse configuration if provided
        $config = [];
        if ($configJson) {
            try {
                $config = json_decode($configJson, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception("Invalid JSON configuration");
                }
            } catch (Exception $e) {
                $this->error("Invalid configuration JSON: " . $e->getMessage());
                return 1;
            }
        }

        // Initialize scraper
        $scraper = new UniversalCoffeeScraper($config);

        // Show configuration
        $this->info("Configuration:");
        $this->line("  - OpenAI Model: " . ($config['model'] ?? 'gpt-4o'));
        $this->line("  - Timeout: " . ($config['timeout'] ?? 30) . "s");
        $this->line("  - Max Retries: " . ($config['max_retries'] ?? 3));

        // Start scraping
        $this->info("\nStarting collection scraping...");
        $startTime = microtime(true);

        try {
            $result = $scraper->scrapeCompany($url);
            
            $endTime = microtime(true);
            $duration = round($endTime - $startTime, 2);

            if ($result['success']) {
                $this->info("✅ Scraping completed successfully in {$duration}s");
                $this->info("📊 Results:");
                $this->line("  - Company URL: " . $result['company_url']);
                $this->line("  - Total Roasts Found: " . $result['total_roasts']);
                $this->line("  - Successful Scrapes: " . $result['successful_scrapes']);
                $this->line("  - Failed Scrapes: " . $result['failed_scrapes']);

                if (!empty($result['errors'])) {
                    $this->warn("\n⚠️  Some roasts failed to scrape:");
                    foreach ($result['errors'] as $error) {
                        $this->line("  - " . ($error['roast']['name'] ?? 'Unknown') . ": " . $error['error']);
                    }
                }

                // Display sample roast data
                if (!empty($result['roasts'])) {
                    $this->info("\n📝 Sample Roast Data:");
                    $sampleRoast = $result['roasts'][0];
                    $this->line("  Name: " . ($sampleRoast['product_name'] ?? $sampleRoast['name'] ?? 'N/A'));
                    $this->line("  Country: " . ($sampleRoast['country'] ?? 'N/A'));
                    $this->line("  Process: " . ($sampleRoast['process'] ?? 'N/A'));
                    $this->line("  Flavor Notes: " . (is_array($sampleRoast['flavor_notes'] ?? null) ? implode(', ', $sampleRoast['flavor_notes']) : ($sampleRoast['flavor_notes'] ?? 'N/A')));
                }

                // Save to file if requested
                if ($outputFile) {
                    $this->saveResults($result, $outputFile);
                }

            } else {
                $this->error("❌ Scraping failed: " . $result['error']);
                return 1;
            }

        } catch (Exception $e) {
            $this->error("❌ Unexpected error: " . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Save results to a file
     */
    protected function saveResults(array $result, string $outputFile): void
    {
        try {
            $jsonData = json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            
            if (file_put_contents($outputFile, $jsonData) === false) {
                $this->warn("⚠️  Failed to save results to file: {$outputFile}");
                return;
            }

            $this->info("💾 Results saved to: {$outputFile}");
            
        } catch (Exception $e) {
            $this->warn("⚠️  Failed to save results: " . $e->getMessage());
        }
    }
}
