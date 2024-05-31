<?php

namespace Modules\Company\Http\Actions;

use Modules\Company\Models\Company;

class ShowCompany
{
    private $company;

    public function __construct( Company $company )
    {
        $this->company = $company;
    }

    public function execute()
    {
        return $this->company;
    }
}