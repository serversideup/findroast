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

        $this->filterByDates();
        $this->filterByProcesses();

        $this->appendCompany();
        $this->appendFlavorNotes();
        $this->appendProcesses();
        $this->appendCountries();
        $this->appendVarieties();
        $this->appendElevations();

        return $this->query->get();
    }

    protected function extractRequestVariables()
    {
        $this->createdAt = $this->request->get('created_at', null);
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
}