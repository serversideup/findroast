<?php

namespace Modules\Offering\Data;

use Spatie\LaravelData\Data;

class RoastDetailsData extends Data
{
    public function __construct(
        public array $flavorNotes,
        public array $elevations,
        public array $countries,
        public array $processes,
        public array $varieties,
    ){}

    public static function fromRoastData( RoastData $roastData ): static
    {
        return new static(
            flavorNotes: $roastData->flavorNotes,
            elevations: $roastData->elevations,
            countries: $roastData->countries,
            processes: $roastData->processes,
            varieties: $roastData->varieties,
        );
    }

    public static function fromOpenAiResult( array $extractedData ): static
    {
        return new static(
            flavorNotes: $extractedData['flavor_notes'],
            elevations: $extractedData['elevations'],
            countries: $extractedData['countries'],
            processes: $extractedData['processes'],
            varieties: $extractedData['varieties'],
        );
    }

    public static function fromMergedData( array $mergedData ): static
    {
        return new static(
            flavorNotes: $mergedData['flavor_notes'],
            elevations: $mergedData['elevations'],
            countries: $mergedData['country'],
            processes: $mergedData['processes'],
            varieties: $mergedData['varieties'],
        );
    }
}