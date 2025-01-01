<?php

namespace Modules\Offering\Http\Actions\Roasts;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use OpenAI\Laravel\Facades\OpenAI;

class ExtractImageData
{
    protected $prompt = "";
    
    public function __construct(
        protected string $image,
        protected array $details = ['flavor_notes', 'processes', 'countries', 'varieties', 'elevations']
    ){}

    public function execute()
    {
        $this->buildPrompt();
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
            'response_format' => ['type' => 'json_object']
        ]);
        
        return json_decode( $result->choices[0]->message->content, true );
    }

    protected function buildPrompt()
    {
        $this->prompt = 'Extract the following information from the image provided:';

        if( in_array( 'flavor_notes', $this->details ) )
        {
            $this->prompt .= ' - What are the flavor notes of this coffee?';
        }

        if( in_array( 'processes', $this->details ) )
        {
            $this->prompt .= ' - What is the process of this coffee?';
        }

        if( in_array( 'countries', $this->details ) )
        {
            $this->prompt .= ' - What country is the coffee from?';
        }

        if( in_array( 'varieties', $this->details ) )
        {
            $this->prompt .= ' - What varieties are in the coffee?';
        }

        if( in_array( 'elevations', $this->details ) )
        {
            $this->prompt .= ' - What is the elevation the coffee was grown at?';
        }

        $this->prompt .= 'Return the information in the following JSON format:';

        $this->prompt .= '{';

        if( in_array( 'flavor_notes', $this->details ) )    
        {
            $this->prompt .= '"flavor_notes": [],';
        }

        if( in_array( 'processes', $this->details ) )
        {
            $this->prompt .= '"processes": [],';
        }

        if( in_array( 'countries', $this->details ) )
        {
            $this->prompt .= '"countries": [],';
        }

        if( in_array( 'varieties', $this->details ) )
        {
            $this->prompt .= '"varieties": [],';
        }

        if( in_array( 'elevations', $this->details ) )
        {
            $this->prompt .= '"elevations": [],';
        }

        $this->prompt .= '}';

        $this->prompt .= 'If the information is not available, feel free to return an empty string or array.';
    }
}