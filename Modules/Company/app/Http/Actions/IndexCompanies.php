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

    public function execute( $with = [] )
    {
        $this->applySearch();
        $this->applyRoaster();
        $this->applyCafe();
        $this->applySubscription();
        $this->applyOrder();

        if( !empty( $with ) ) {
            $this->query->with( $with );
        }

        $results = $this->query->paginate(18);

        return $results;
    }

    private function applySearch()
    {
        if( $this->request->has('search') ) {
            $this->query->where('name', 'like', '%'.$this->request->input('search').'%');
        }
    }

    private function applyRoaster()
    {
        if( $this->request->has('types') 
            && in_array('roaster', $this->request->input('types') ) ) {
            $this->query->where('roaster', 1);
        }
    }

    private function applyCafe()
    {
        if( $this->request->has('types') 
            && in_array('cafe', $this->request->input('types') ) ) {
            // $this->query->where('cafe', 1);
        }
    }

    private function applySubscription()
    {
        if( $this->request->has('types') 
            && in_array('subscription', $this->request->input('types') ) ) {
            $this->query->where('subscription', 1);
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