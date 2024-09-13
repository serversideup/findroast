<?php

namespace Modules\Offering\Data;

use Spatie\LaravelData\Data;

class RoastData extends Data
{
    public function __construct(
        public ?string $url,
        public ?string $price,
        public ?string $name,
        public ?string $type,
        public ?string $image,
        public ?string $detailsCard,
        public ?array $flavorNotes,
        public ?array $elevations,
        public ?array $countries,
        public ?array $processes,
        public ?array $varieties,
        public ?string $rawText,
        public ?bool $inStock,
        public ?array $detailsMap
    ){}

    public static function fromCollectionResponse(array $response): static
    {
        return new static(
            url: $response['url'],
            price: $response['price'] ? $response['price'] : '',
            name: $response['name'],
            type: '',
            image: '',
            detailsCard: '',
            flavorNotes: [],
            elevations: [],
            countries: [],
            processes: [],
            varieties: [],
            rawText: '',
            inStock: '',
            detailsMap: []
        );
    }

    public static function fromSingleResponse(array $response): static
    {
        return new static(
            url: '',
            price: '',
            name: '',
            type: $response['type'],
            image: $response['image'],
            detailsCard: $response['details_card'],
            flavorNotes: $response['flavor_notes'],
            elevations: $response['elevations'],
            countries: $response['countries'],
            processes: $response['processes'],
            varieties: $response['varieties'],
            rawText: $response['raw_text'],
            inStock: $response['in_stock'],
            detailsMap: $response['details_map']
        );
    }
}