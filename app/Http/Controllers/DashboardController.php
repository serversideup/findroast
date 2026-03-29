<?php

namespace App\Http\Controllers;

use App\Models\DailyDigestSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Modules\Company\Models\Company;
use Modules\Offering\Http\Actions\Processes\IndexProcesses;
use Modules\Offering\Http\Actions\Roasts\IndexRoasts;
use Modules\Offering\Models\Country;
use Modules\Offering\Models\FlavorNote;
use Modules\Offering\Models\Variety;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $processes = (new IndexProcesses())->execute();

        $roasts = (new IndexRoasts($request))->execute();

        $countries = Country::withCount(['roasts' => function ($query) {
            $query->where('in_stock', 1);
        }])
            ->orderBy('roasts_count', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        $varieties = Variety::withCount(['roasts' => function ($query) {
            $query->where('in_stock', 1);
        }])
            ->orderBy('roasts_count', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        $flavorNotes = FlavorNote::withCount(['roasts' => function ($query) {
            $query->where('in_stock', 1);
        }])
            ->orderBy('roasts_count', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        $companies = Company::withCount(['roasts' => function ($query) {
            $query->where('in_stock', 1);
        }])
            ->orderBy('roasts_count', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        // Check if user is subscribed to daily digest
        $dailyDigestSubscribed = Auth::check()
            ? DailyDigestSubscriber::where('user_id', Auth::id())->exists()
            : false;

        return Inertia::render('Offerings/Index', [
            'processes' => $processes,
            'countries' => $countries,
            'flavorNotes' => $flavorNotes,
            'varieties' => $varieties,
            'roasts' => $roasts,
            'companies' => $companies,
            'dailyDigestSubscribed' => $dailyDigestSubscribed,
        ]);
    }
}
