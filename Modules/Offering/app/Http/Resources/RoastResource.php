<?php

namespace Modules\Offering\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoastResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'price' => $this->price,
            'currency' => $this->currency,
            'type' => $this->type,
            'in_stock' => (bool) $this->in_stock,
            'primary_image' => $this->primary_image,
            'first_seen_at' => $this->first_seen_at,
            'last_seen_at' => $this->last_seen_at,
            'created_at' => $this->created_at,
            'company' => $this->whenLoaded('company', fn () => [
                'id' => $this->company->id,
                'name' => $this->company->name,
                'slug' => $this->company->slug,
            ]),
            'flavor_notes' => $this->whenLoaded('flavorNotes', fn () =>
                $this->flavorNotes->map(fn ($n) => ['id' => $n->id, 'name' => $n->name])
            ),
            'processes' => $this->whenLoaded('processes', fn () =>
                $this->processes->map(fn ($p) => ['id' => $p->id, 'name' => $p->name])
            ),
            'countries' => $this->whenLoaded('countries', fn () =>
                $this->countries->map(fn ($c) => ['id' => $c->id, 'name' => $c->name])
            ),
            'varieties' => $this->whenLoaded('varieties', fn () =>
                $this->varieties->map(fn ($v) => ['id' => $v->id, 'name' => $v->name])
            ),
            'elevations' => $this->whenLoaded('elevations', fn () =>
                $this->elevations->map(fn ($e) => ['id' => $e->id, 'name' => $e->name])
            ),
        ];
    }
}
