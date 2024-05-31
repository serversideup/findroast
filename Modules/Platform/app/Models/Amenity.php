<?php

namespace Modules\Platform\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Amenity\Database\Factories\AmenityFactory;

class Amenity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'icon',
        'description',
    ];

    /**
     * Set the table associated with the model.
     */
    protected $table = 'amenities';

    // protected static function newFactory(): AmenityFactory
    // {
    //     //return AmenityFactory::new();
    // }
}
