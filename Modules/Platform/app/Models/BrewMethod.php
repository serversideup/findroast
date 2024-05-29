<?php

namespace Modules\BrewMethod\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\BrewMethod\Database\Factories\BrewMethodFactory;

class BrewMethod extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'icon',
    ];

    /**
     * Set the table associated with the model.
     */
    protected $table = 'brew_methods';

    // protected static function newFactory(): BrewMethodFactory
    // {
    //     //return BrewMethodFactory::new();
    // }
}
