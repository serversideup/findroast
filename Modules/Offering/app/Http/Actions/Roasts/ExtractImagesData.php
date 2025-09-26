<?php

namespace Modules\Offering\Http\Actions\Roasts;

use Illuminate\Support\Facades\Http;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ExtractImagesData
{
    protected $prompt = "";
    protected array $cachedImages = [];

    public function __construct(
        protected array $images,
    ){}

    public function execute()
    {
        $this->cacheImages();

        $resizedImages = $this->resizeImages();

        $response = $this->sendToOpenAi($resizedImages);
        
        $images = $response['images'];
        
        $this->deleteUnusedImages( $images );
        
        return $images;
    }

    protected function cacheImages()
    {
        $cacheDirectory = 'cache/temp/'.Str::random(10);
        
        foreach ($this->images as $key => $image) {
            $response = Http::withoutVerifying()
                ->get($image['src']);

            if( $response->successful() ){
                $cachedImage = $response->body();

                $path = $cacheDirectory.'/'.$key.'.jpg';

                Storage::disk('public')
                    ->put($path, $cachedImage);
            }

            $this->cachedImages[] = $path;
        }
    }

    protected function resizeImages()
    {
        $resizedImages = [];

        foreach ($this->cachedImages as $image) {
            $manager = new ImageManager(new Driver());

            $image = $manager->read( storage_path( '/app/public/'.$image ) );

            // resize image proportionally to 1024px width
            $image->scale(width: 1024);

            // Resize the image
            $resizedImages[] = $image;
        }

        return $resizedImages;
    }

    protected function sendToOpenAi( $images )
    {
        $prompt = $this->buildPrompt();
        $imageContent = $this->buildImageContent($images);

        $chat = $this->buildChat($prompt, $imageContent);

        \Log::info('Sending to OpenAI', [
            'chat' => $chat
        ]);

        $result = OpenAI::chat()->create($chat);
        
        return json_decode( $result->choices[0]->message->content, true );
    }

    protected function buildImageContent( $images )
    {
        $imageContent = [];
        
        foreach ($images as $image) {
            $imageContent[] = [
                'type' => 'image_url',
                'image_url' => [
                    'url' => $image->toJpeg()->toDataUri(),
                    'detail' => 'auto'
                ]
            ];
        }

        return $imageContent;
    }

    protected function buildPrompt()
    {
        $prompt = "The attached images are from a coffee company that sells their own roasts. 
        The JSON below contains the paths of the images that match the attached images:
        ```
        ".json_encode($this->cachedImages)."
        ```
        We are looking for two images:
            - The primary image of the roast
            - The details image for the roast
        The primary image is the main image of the roast preferably is a coffee bag image.
        The details image contains flavor notes, processes, countries, varieties, or elevations of the roast.
        Sometimes the bag of coffee contains the details. If that's the case, the primary image and the details image are the same.
        If there's no details image, return null for the details image.
        If there's no primary image, return null for the primary image.
        Please return the data in the following format:
        ```
        {
            \"images\": {
                \"primary_image\": \"Path of the primary image\",
                \"details_image\": \"Path of the details image\"
            }
        }
        ```";

        return $prompt;
    }

    protected function buildChat( $prompt, $imageContent )
    {
        $chat = [];

        $chat['model'] = 'gpt-4o';
        $chat['messages'] = [];

        $content = [];

        $content[] = [
            'type' => 'text',
            'text' => $prompt
        ];

        foreach ($imageContent as $image) {
            $content[] = $image;
        }

        $chat['messages'][] = [
            'role' => 'user',
            'content' => $content
        ];

        $chat['response_format'] = ['type' => 'json_object'];
        $chat['temperature'] = 0.1; // Low temperature for consistent extraction
        
        return $chat;
    }

    protected function deleteUnusedImages( $images )
    {
        foreach ($this->cachedImages as $image) {
            if( !in_array( $image, $images ) ) {
                Storage::disk('public')->delete($image);
            }
        }
    }
}