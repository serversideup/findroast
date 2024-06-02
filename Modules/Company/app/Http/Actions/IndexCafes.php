<?php

namespace Modules\Company\Http\Actions;

use Illuminate\Http\Request;
use Modules\Company\Models\Cafe;
use Modules\Company\Models\Company;

class IndexCafes
{
    private $request;
    private $company;
    private $query;

    public function __construct( Request $request, Company $company = null )
    {
        $this->request = $request;
        $this->company = $company;
        $this->query = Cafe::query();
    }

    public function execute()
    {
        $this->applySearch();
        $this->filterCompany();
        $this->applyOrder();

        return $this->query->paginate();
    }

    private function applySearch()
    {
        if( $this->request->has('search') ) {
            $this->query->where('name', 'like', '%'.$this->request->input('search').'%');
        }
    }

    private function filterCompany()
    {
        if( $this->company ) {
            $this->query->where('company_id', $this->company->id);
        }
    }

    private function applyOrder()
    {
        $this->query->orderBy( 
            $this->request->input('order_by', 'name'),
            $this->request->input('order_direction', 'asc')
        );
    }
}