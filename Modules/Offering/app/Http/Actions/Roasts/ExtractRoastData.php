<?php

namespace Modules\Offering\Http\Actions\Roasts;

use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use Exception;

class ExtractRoastData
{
    protected string $openaiModel = 'gpt-4o';

    protected array $config = [
        'max_retries' => 3,
    ];
    
    public function __construct(
        public array $roast,
        public array $images = []
    ){}

    public function execute()
    {
        $prompt = $this->buildPrompt();
        $response = $this->sendToOpenAi($prompt);

        $processedRoast = $response['roast'];

        $processedRoast = $this->mergeImages($processedRoast);

        return $processedRoast;
    }

    /**
     * Build prompt for collection scraping
     */
    protected function buildPrompt(): string
    {
        $prompt = "The JSON is an array of data from a coffee company that sells their own roasts. 
I've collected all the text, images, and links from the product listing in the JSON provided:
```
".json_encode($this->roast)."
```

If I've attached an image to the prompt, it's a details image of the roast. Use the attached image to help extract the flavor notes, processes, countries, varieties, and elevations of the roast.
If the data is in the image, use it as priority since it's more accurate than the text.

From the JSON provided and image if provided, extract the following data from the roast:
- The name of the roast
- The URL of the roast
- The Price of the roast (if found)
- Whether the roast is in stock or not. Default to true if not found.
- The flavor notes of the roast
- The processes of the roast
- The countries of the roast
- The varieties of the roast
- The elevations of the roast

Please return the data in the following format:
```
{
    \"roast\": {
        \"name\": (string)\"Product Name\",
        \"url\": (string)\"Product URL\",
        \"price\": (float)\"Product price\",
        \"in_stock\": (boolean)\"Whether the product is in stock or not. Default to true if not found.\",
        \"flavor_notes\": (array)\"Flavor notes of the roast\",
        \"processes\": (array)\"Processes of the roast\",
        \"countries\": (array)\"Countries of the roast\",
        \"varieties\": (array)\"Varieties of the roast\",
        \"elevations\": (array)\"Elevations of the roast\"
    }
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
                $chat = $this->buildChat($prompt);

                $result = OpenAI::chat()->create($chat);

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

    protected function buildChat(string $prompt)
    {
        $chat = [];

        $chat['model'] = 'gpt-4o';
        $chat['messages'] = [];

        $content = [];

        $content[] = [
            'type' => 'text',
            'text' => $prompt
        ];

        if( !empty( $this->images ) && isset( $this->images['details_image'] ) ) {
            $imageUrl = $this->getImageUrl( $this->images['details_image'] );

            $content[] = [
                'type' => 'image_url',
                'image_url' => [
                    'url' => $imageUrl,
                    'detail' => 'auto'
                ]
            ];
        }

        $chat['messages'][] = [
            'role' => 'user',
            'content' => $content
        ];

        $chat['response_format'] = ['type' => 'json_object'];
        $chat['temperature'] = 0.1; // Low temperature for consistent extraction
        
        return $chat;
    }

    protected function mergeImages(array $processedRoast)
    {
        if( empty( $this->images ) ) {
            return $processedRoast;
        }

        $processedRoast['primary_image'] = isset( $this->images['primary_image'] ) ? $this->images['primary_image'] : '';
        $processedRoast['details_image'] = isset( $this->images['details_image'] ) ? $this->images['details_image'] : '';

        return $processedRoast;
    }

    protected function getImageUrl(string $image)
    {
        $manager = new ImageManager(new Driver());

        $image = $manager->read( storage_path( '/app/public/'.$image ) );

        $image->scale(width: 1024);

        return $image->toJpeg()->toDataUri();
    }
}