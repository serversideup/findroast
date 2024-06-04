<?php

namespace Modules\Offering\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Offering\Database\Factories\VarietalFactory;

class Varietal extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name'
    ];

    protected $table = 'varietals';

    // protected static function newFactory(): VarietalFactory
    // {
    //     //return VarietalFactory::new();
    // }
}
