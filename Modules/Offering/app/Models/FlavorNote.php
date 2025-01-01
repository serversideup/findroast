<?php

namespace Modules\Offering\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Offering\Database\Factories\FlavorNoteFactory;
use Modules\Offering\Models\Roast;

class FlavorNote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug'
    ];

    protected $table = 'flavor_notes';
    
    // protected static function newFactory(): FlavorNoteFactory
    // {
    //     //return FlavorNoteFactory::new();
    // }

    public function roasts()
    {
        return $this->belongsToMany(Roast::class, 'roast_flavor_notes');
    }
}
