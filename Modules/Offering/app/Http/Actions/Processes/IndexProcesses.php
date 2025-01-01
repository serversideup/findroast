<?php

namespace Modules\Offering\Http\Actions\Processes;

use Modules\Offering\Models\Process;

class IndexProcesses
{
    private $query;

    public function __construct(
        protected bool $roastsInStock = true,
        protected array $orderBy = [
            'roasts_count' => 'desc',
            'name' => 'asc'
        ]
    ){}

    public function execute()
    {
        $this->query = Process::query();

        $this->appendRoastCount();

        $this->order();

        $results = $this->query->get();

        return $results;
    }

    private function appendRoastCount()
    {
        $this->query->withCount(['roasts' => function($query) {
            if ($this->roastsInStock) {
                $query->where('in_stock', 1);
            }
        }]);
    }

    private function order()
    {
        foreach ($this->orderBy as $column => $direction) { 
            $this->query->orderBy($column, $direction);
        }
    }
}