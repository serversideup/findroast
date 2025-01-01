<?php

namespace Modules\Offering\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Offering\Models\Roast;

class Country extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug'
    ];

    protected $table = 'countries';

    public function regions()
    {
        return $this->hasMany(Region::class);
    }
    
    public function roasts()
    {
        return $this->belongsToMany(Roast::class, 'roast_countries');
    }
}
