<?php

namespace Modules\Offering\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Company\Models\Company;
use Modules\Offering\Database\Factories\RoastFactory;

class Roast extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'name',
        'url',
        'price',
        'image',
        'type',
        'first_seen',
        'last_seen',
    ];

    protected $table = 'roasts';

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function flavorNotes()
    {
        return $this->belongsToMany(FlavorNote::class, 'roast_flavor_notes');
    }

    public function varietals()
    {
        return $this->belongsToMany(Varietal::class, 'roast_varetials');
    }

    public function processes()
    {
        return $this->belongsToMany(Process::class, 'roast_processes');
    }

    public function regions()
    {
        return $this->belongsToMany(Country::class, 'roast_regions');
    }

    // protected static function newFactory(): RoastFactory
    // {
    //     //return RoastFactory::new();
    // }
}
