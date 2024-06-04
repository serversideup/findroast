<?php

namespace Modules\Offering\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Offering\Database\Factories\RegionFactory;

class Region extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'country_id'
    ];

    protected $table = 'regions';

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    
    // protected static function newFactory(): RegionFactory
    // {
    //     //return RegionFactory::new();
    // }
}
