<?php

namespace Modules\Company\Models;

use Modules\Platform\Models\Amenity;
use Modules\Platform\Models\BrewMethod;
use Modules\Platform\Models\DrinkOption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Modules\Cafes\Database\Factories\CafeFactory;

class Cafe extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'name',
        'status',
        'slug',
        'google_place_id',
        'yelp_url',
        'primary_image',
        'description',
        'address',
        'city',
        'state',
        'province',
        'territory',
        'country',
        'zip',
        'latitude',
        'longitude',
    ];

    /**
     * Set the table associated with the model.
     */
    protected $table = 'cafes';

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'cafe_amenity');
    }

    public function brewMethods()
    {
        return $this->belongsToMany(BrewMethod::class, 'cafe_brew_method');
    }

    public function drinkOptions()
    {
        return $this->belongsToMany(DrinkOption::class, 'cafe_drink_option');
    }


    // protected static function newFactory(): CafeFactory
    // {
    //     //return CafeFactory::new();
    // }
}
