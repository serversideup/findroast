<?php

namespace Modules\Offering\Http\Actions\Roasts;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Modules\Offering\Models\Roast;

class DownloadImage
{
    public function __construct(
        protected string $imageUrl,
        protected Roast $roast,
        protected string $type
    ){}

    public function execute()
    {
        $response = Http::withoutVerifying()
            ->get($this->imageUrl);

        if( $response->successful() ){
            $image = $response->body();
            $path = 'companies/'.$this->roast->company->slug.'/roasts/roast-'.$this->type.'-'.$this->roast->id.'.jpg';

            Storage::disk('public')->put($path, $image);
        }

        return '/storage/'.$path;
    }
}
