<?php

namespace Modules\Offering\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Offering\Database\Factories\CountryFactory;

class Country extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name'
    ];

    protected $table = 'countries';

    public function regions()
    {
        return $this->hasMany(Region::class);
    }
    
    // protected static function newFactory(): CountryFactory
    // {
    //     //return CountryFactory::new();
    // }
}
