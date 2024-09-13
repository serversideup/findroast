<?php

namespace Modules\Offering\Http\Actions\Roasts;

use OpenAI\Laravel\Facades\OpenAI;

class ExtractRawTextData
{
    protected $prompt = 'Below is some HTML code:
        {{html}}

        Extract the following information from the html provided:

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

        If the information is not available, feel free to return an empty string or array. Please return just the JSON formatted response.';

    public function __construct(
        protected string $html
    ){}

    public function execute()
    {
        $this->buildPrompt();
        $response = $this->sendToOpenAi();

        return $response;
    }

    private function buildPrompt()
    {
        $this->prompt = str_replace('{{html}}', $this->html, $this->prompt);
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
        ]);

        $jsonResponse = trim( $result->choices[0]->message->content, '```json' );
        $jsonResponse = trim( $jsonResponse, '```' );
        $jsonResponse = trim( $jsonResponse );

        return json_decode( $jsonResponse, true );
    }
}