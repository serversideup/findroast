<?php

namespace Modules\Offering\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Offering\Database\Factories\FlavorNoteFactory;

class FlavorNote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name'
    ];

    protected $table = 'flavor_notes';
    
    // protected static function newFactory(): FlavorNoteFactory
    // {
    //     //return FlavorNoteFactory::new();
    // }
}
