<?php

namespace Modules\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Offering\Models\Roast;
use Modules\Offering\Models\Variety;
use Modules\Offering\Models\Process;
use Modules\Offering\Models\Country;
use Modules\Offering\Models\Elevation;
use Modules\Offering\Models\FlavorNote;
use Modules\Platform\Http\Actions\Roasts\UpdateRoast;

class RoastController extends Controller
{
    public function index( Request $request )
    {
        return Inertia::render('Platform/Roasts/Index', [
            'roasts' => fn() => (Roast::with('company')
                ->with('flavorNotes')
                ->with('varieties')
                ->with('processes')
                ->with('countries')
                ->with('elevations')
                ->orderBy('updated_at', 'desc')
                ->paginate(100)
                ->withQueryString()),
            'varieties' => fn() => Variety::orderBy('name', 'asc')->get(),
            'processes' => fn() => Process::orderBy('name', 'asc')->get(),
            'countries' => fn() => Country::orderBy('name', 'asc')->get(),
            'elevations' => fn() => Elevation::orderBy('name', 'asc')->get(),
            'flavorNotes' => fn() => FlavorNote::orderBy('name', 'asc')->get(),
        ]);
    }

    public function update( Request $request, Roast $roast )
    {
        ( new UpdateRoast( $request, $roast ) )
            ->update( $request, $roast );

        return redirect()->back();
    }

    public function delete( Request $request, Roast $roast )
    {
        $roast->delete();

        return redirect()->back();
    }
}