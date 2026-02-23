<?php

namespace Modules\Offering\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Offering\Http\Actions\Roasts\IndexRoasts;
use Modules\Offering\Http\Resources\RoastResource;
use Modules\Offering\Models\Roast;

class RoastController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'processes' => ['nullable', 'array'],
            'processes.*' => ['integer'],
            'flavor_notes' => ['nullable', 'array'],
            'flavor_notes.*' => ['integer'],
            'varieties' => ['nullable', 'array'],
            'varieties.*' => ['integer'],
            'countries' => ['nullable', 'array'],
            'countries.*' => ['integer'],
            'companies' => ['nullable', 'array'],
            'companies.*' => ['integer'],
            'sort' => ['nullable', 'string', 'in:newest,a-z'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $perPage = min((int) $request->get('per_page', 12), 50);

        $roasts = (new IndexRoasts($request))->executeForApi($perPage);

        return RoastResource::collection($roasts);
    }

    public function show(Roast $roast)
    {
        $roast->load(['company', 'flavorNotes', 'processes', 'countries', 'varieties', 'elevations']);

        return new RoastResource($roast);
    }
}
