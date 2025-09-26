<?php

namespace Modules\Offering\Http\Actions\Roasts;

use Modules\Offering\Models\Roast;

class ProcessRoast
{
    public function __construct(
        protected Roast $roast,
        protected array $scrapedRoast
    ){}

    public function execute()
    {
        $processedRoast = $this->processRoast();

        return $processedRoast;
    }

    protected function processRoast()
    {
        $images = [];

        if( $this->roast->primary_image != '' ){
            $images['primary_image'] = $this->roast->primary_image;
        }

        if( $this->roast->details_image != '' ){
            $images['details_image'] = $this->roast->details_image;
        }

        $extractedRoastData = ( new ExtractRoastData(
            $this->scrapedRoast,
            $images
        ) )->execute();

        return $extractedRoastData;
    }
}