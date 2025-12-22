<?php

namespace Modules\Offering\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Company\Models\Company;
use Modules\Offering\Database\Factories\RoastFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Roast extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'name',
        'url',
        'price',
        'currency',
        'primary_image',
        'primary_image_disk',
        'meta_data_image',
        'meta_data_image_disk',
        'type',
        'first_seen_at',
        'last_seen_at',
        'in_stock',
        'details_processed_at',
        'last_synced_at'
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

    public function varieties()
    {
        return $this->belongsToMany(Variety::class, 'roast_varieties')
            ->withTimestamps('created_at', 'updated_at');
    }

    public function processes()
    {
        return $this->belongsToMany(Process::class, 'roast_processes')
            ->withTimestamps('created_at', 'updated_at');
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'roast_countries')
            ->withTimestamps('created_at', 'updated_at');
    }

    public function elevations()
    {
        return $this->belongsToMany(Elevation::class, 'roast_elevations')
            ->withTimestamps('created_at', 'updated_at');
    }

    // protected static function newFactory(): RoastFactory
    // {
    //     //return RoastFactory::new();
    // }
}
