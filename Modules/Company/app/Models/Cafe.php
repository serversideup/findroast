<?php

namespace Modules\Company\Models;

use Modules\Amenity\Models\Amenity;
use Modules\BrewMethod\Models\BrewMethod;
use Modules\DrinkOption\Models\DrinkOption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Cafes\Database\Factories\CafeFactory;

class Cafe extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

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
