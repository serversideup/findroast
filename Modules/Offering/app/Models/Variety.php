<?php

namespace Modules\Offering\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Offering\Models\Roast;

class Variety extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug'
    ];

    protected $table = 'varieties';

    public function roasts()
    {
        return $this->belongsToMany(Roast::class, 'roast_varieties');
    }
}
