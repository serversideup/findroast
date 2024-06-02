<?php

namespace Modules\Company\Http\Actions;

use Modules\Company\Models\Cafe;

class ShowCafe
{
    private $cafe;

    public function __construct( Cafe $cafe )
    {
        $this->cafe = $cafe;
    }

    public function execute()
    {
        return $this->cafe->load('amenities', 'brewMethods', 'drinkOptions');
    }
}