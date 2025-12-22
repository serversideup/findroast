<?php

namespace Modules\Offering\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Offering\Http\Actions\Processes\IndexProcesses;
use Modules\Offering\Http\Actions\Roasts\IndexRoasts;
use Modules\Offering\Models\Country;
use Modules\Offering\Models\FlavorNote;
use Modules\Offering\Models\Variety;
use Modules\Offering\Models\Roast;
use Modules\Company\Models\Company;

class OfferingController extends Controller
{
    public function index(Request $request)
    {
        $processes = (new IndexProcesses())->execute();

        $roasts = (new IndexRoasts($request))->execute();

        $countries = Country::withCount(['roasts' => function($query) {
            $query->where('in_stock', 1);
        }])
        ->orderBy('roasts_count', 'desc')
        ->orderBy('name', 'asc')
        ->get();

        $varieties = Variety::withCount(['roasts' => function($query) {
            $query->where('in_stock', 1);
        }])
        ->orderBy('roasts_count', 'desc')
        ->orderBy('name', 'asc')
        ->get();

        $flavorNotes = FlavorNote::withCount(['roasts' => function($query) {
            $query->where('in_stock', 1);
        }])
        ->orderBy('roasts_count', 'desc')
        ->orderBy('name', 'asc')
        ->get();

        $companies = Company::withCount(['roasts' => function($query) {
            $query->where('in_stock', 1);
        }])
        ->orderBy('roasts_count', 'desc')
        ->orderBy('name', 'asc')
        ->get();

        return Inertia::render('Offerings/Index', [
            'processes' => $processes,
            'countries' => $countries,
            'flavorNotes' => $flavorNotes,
            'varieties' => $varieties,
            'roasts' => $roasts,
            'companies' => $companies
        ]);
    }
}
