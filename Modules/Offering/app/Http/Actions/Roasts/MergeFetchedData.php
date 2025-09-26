<?php

namespace Modules\Offering\Http\Actions\Roasts;

class MergeFetchedData
{
    protected array $mergedData = [
        'text' => [],
        'images' => [],
        'links' => [],
    ];

    public function __construct(
        protected array $fetchedFromCollection,
        protected array $fetchedFromSingle
    ){}

    public function execute()
    {
        $this->mergeData();

        return $this->mergedData;
    }

    protected function mergeData()
    {
        $this->mergedData['text'] = array_unique(
            array_merge(
                isset( $this->fetchedFromCollection['text'] ) ? $this->fetchedFromCollection['text'] : [], 
                isset( $this->fetchedFromSingle['text'] ) ? $this->fetchedFromSingle['text'] : []
            ), SORT_REGULAR);

        $this->mergedData['images'] = array_unique(
            array_merge(
                isset( $this->fetchedFromCollection['images'] ) ? $this->fetchedFromCollection['images'] : [], 
                isset( $this->fetchedFromSingle['images'] ) ? $this->fetchedFromSingle['images'] : []
            ), SORT_REGULAR);

        $this->mergedData['links'] = $this->fetchedFromCollection['links'];

        $this->mergedData['text'] = array_values($this->mergedData['text']);
        $this->mergedData['images'] = array_values($this->mergedData['images']);

        return $this->mergedData;
    }
}