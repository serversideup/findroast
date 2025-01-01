<?php

namespace Modules\Offering\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Offering\Models\Roast;

class Elevation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name'
    ];

    protected $table = 'elevations';

    public function roasts()
    {
        return $this->belongsToMany(Roast::class, 'roast_elevations');
    }
}
