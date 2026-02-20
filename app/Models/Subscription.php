<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'filters',
        'search',
    ];

    protected function casts(): array
    {
        return [
            'filters' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get a human-readable description of the filters
     */
    public function getFilterDescriptionAttribute(): string
    {
        $parts = [];

        if (!empty($this->search)) {
            $parts[] = 'Search: "' . $this->search . '"';
        }

        $filterTypes = [
            'countries' => 'Origins',
            'processes' => 'Processes',
            'flavor_notes' => 'Flavors',
            'varieties' => 'Varieties',
            'companies' => 'Roasters',
        ];

        foreach ($filterTypes as $key => $label) {
            if (!empty($this->filters[$key]) && count($this->filters[$key]) > 0) {
                $parts[] = $label . ' (' . count($this->filters[$key]) . ')';
            }
        }

        return implode(', ', $parts);
    }
}
