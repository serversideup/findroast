<?php

namespace Modules\Offering\Http\Actions\Roasts;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use OpenAI\Laravel\Facades\OpenAI;

class ExtractImageData
{
    protected $prompt = 'Extract the following information from the image provided:
        - What are the flavor notes of this coffee?
        - What is the process of this coffee?
        - What country is the coffee from?
        - What varieties are in the coffee?
        - What is the elevation the coffee was grown at?
        
        Return the information in the following format:
        {
            "flavor_notes": [],
            "processes": [],
            "countries": [],
            "varieties": [],
            "elevations": []
        }

        If the information is not available, feel free to return an empty string or array.';
    
    public function __construct(
        protected string $image
    ){}

    public function execute()
    {
        $resizedImage = $this->resizeImage();
        $response = $this->sendToOpenAi( $resizedImage );

        return $response;
    }

    protected function resizeImage()
    {
        $manager = new ImageManager(new Driver());

        $image = $manager->read( storage_path( str_replace( '/storage/', '/app/public/', $this->image ) ) );

        // resize image proportionally to 300px width
        $image->scale(width: 1024);

        // Resize the image
        return $image;
    }

    protected function sendToOpenAi( $image )
    {
        $base64 = $image->toJpeg()->toDataUri();

        $result = OpenAI::chat()->create([
            'model' => 'gpt-4o',
            'messages' => [
                [
                    'role' => 'user', 
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $this->prompt
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => $base64,
                                'detail' => 'auto'
                            ]
                        ]
                    ]
                ],
            ],
        ]);
        
        return json_decode( $result->choices[0]->message->content, true );
    }
}