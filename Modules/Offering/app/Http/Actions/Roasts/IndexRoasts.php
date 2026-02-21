<?php

namespace Modules\Offering\Http\Actions\Roasts;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Offering\Models\Roast;

class IndexRoasts
{
    protected $query;
    protected $createdAt;

    public function __construct(
        protected Request $request
    ){}

    public function execute()
    {
        $this->query = Roast::query();

        $this->extractRequestVariables();

        $this->filterBySearch();
        $this->filterByDates();
        $this->filterByProcesses();
        $this->filterByFlavorNotes();
        $this->filterByVarieties();
        $this->filterByCountries();
        $this->filterByCompanies();
        $this->filterByInStock();

        $this->applySort();

        $this->appendCompany();
        $this->appendFlavorNotes();
        $this->appendProcesses();
        $this->appendCountries();
        $this->appendVarieties();
        $this->appendElevations();

        return $this->query->paginate(12)->withQueryString();
    }

    protected function extractRequestVariables()
    {
        $this->createdAt = $this->request->get('created_at', null);
    }

    protected function filterBySearch()
    {
        $search = $this->request->get('search', '');

        if ($search !== '') {
            $this->query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('company', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('countries', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('flavorNotes', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
        }
    }

    protected function filterByDates()
    {
        if( $this->createdAt ){
            $this->query->whereBetween('created_at', [
                Carbon::parse($this->createdAt)->startOfDay(),
                Carbon::parse($this->createdAt)->endOfDay()
            ]);
        }
    }

    protected function filterByProcesses()
    {
        if( $this->request->has('processes') ){
            $this->query->whereHas('processes', function($query) {
                $query->whereIn('processes.id', $this->request->get('processes'));
            });
        }
    }

    protected function filterByFlavorNotes()
    {
        if( $this->request->has('flavor_notes') ){
            $this->query->whereHas('flavorNotes', function($query) {
                $query->whereIn('flavor_notes.id', $this->request->get('flavor_notes'));
            });
        }
    }

    protected function filterByVarieties()
    {
        if( $this->request->has('varieties') ){
            $this->query->whereHas('varieties', function($query) {
                $query->whereIn('varieties.id', $this->request->get('varieties'));
            });
        }
    }

    protected function filterByCountries()
    {
        if( $this->request->has('countries') ){
            $this->query->whereHas('countries', function($query) {
                $query->whereIn('countries.id', $this->request->get('countries'));
            });
        }
    }

    protected function filterByCompanies()
    {
        if( $this->request->has('companies') ){
            $this->query->where('company_id', $this->request->get('companies'));
        }
    }

    protected function appendCompany()
    {
        $this->query->with('company');
    }

    protected function appendFlavorNotes()
    {
        $this->query->with('flavorNotes');
    }

    protected function appendProcesses()
    {
        $this->query->with('processes');
    }

    protected function appendCountries()
    {
        $this->query->with('countries');
    }

    protected function appendVarieties()
    {
        $this->query->with('varieties');
    }

    protected function appendElevations()
    {
        $this->query->with('elevations');
    }

    protected function filterByInStock()
    {
        $this->query->where('in_stock', 1);
    }

    protected function applySort()
    {
        $sort = $this->request->get('sort', 'newest');

        switch ($sort) {
            case 'random':
                if ($this->request->boolean('reshuffle') || ! session()->has('roast_random_seed')) {
                    session(['roast_random_seed' => rand(1, 999999)]);
                }
                $seed = session('roast_random_seed');
                $this->query->orderByRaw("RAND({$seed})");
                break;
            case 'a-z':
                session()->forget('roast_random_seed');
                $this->query->orderBy('name', 'asc');
                break;
            case 'newest':
            default:
                session()->forget('roast_random_seed');
                $this->query->orderBy('created_at', 'desc');
                break;
        }
    }
}