<?php

namespace Modules\DrinkOption\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\DrinkOption\Database\Factories\DrinkOptionFactory;

class DrinkOption extends Model
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
    protected $table = 'drink_options';

    // protected static function newFactory(): DrinkOptionFactory
    // {
    //     //return DrinkOptionFactory::new();
    // }
}
