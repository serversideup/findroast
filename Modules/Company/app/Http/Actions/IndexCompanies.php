<?php

namespace Modules\Company\Http\Actions;

use Illuminate\Http\Request;
use Modules\Company\Models\Company;

class IndexCompanies
{
    private $request;
    private $query;

    public function __construct( Request $request )
    {
        $this->request = $request;
        $this->query = Company::query();
    }

    public function execute()
    {
        $this->applySearch();
        $this->applyOrder();

        return $this->query->paginate();
    }

    private function applySearch()
    {
        if( $this->request->has('search') ) {
            $this->query->where('name', 'like', '%'.$this->request->input('search').'%');
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