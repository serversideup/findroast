<?php

namespace Modules\Offering\Http\Actions\Roasts;

use OpenAI\Laravel\Facades\OpenAI;

class ExtractTextData
{
    protected $prompt = "";
    
    public function __construct(
        protected string $text,
        protected array $details = ['flavor_notes', 'processes', 'countries', 'varieties', 'elevations']
    ){}

    public function execute()
    {
        $this->buildPrompt();
        $response = $this->sendToOpenAi();

        return $response;
    }

    protected function sendToOpenAi()
    {
        $result = OpenAI::chat()->create([
            'model' => 'gpt-4o',
            'messages' => [
                [
                    'role' => 'user', 
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $this->prompt
                        ]
                    ],
                ],
            ],
            'response_format' => ['type' => 'json_object']
        ]);

        return json_decode( $result->choices[0]->message->content, true );
    }

    protected function buildPrompt()
    {
        $this->prompt = 'Below is some text extracted from a coffee product page:';

        $this->prompt .= '\n\n'.$this->text;

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