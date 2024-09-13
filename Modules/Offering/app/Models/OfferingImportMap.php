<?php

namespace Modules\Offering\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Offering\Database\Factories\OfferingImportMapFactory;

class OfferingImportMap extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'company_id',
        'enabled',
        'api_url',
        'day',
        'last_synced_at',
    ];

    protected $table = 'offering_import_maps';

    // protected static function newFactory(): OfferingImportMapFactory
    // {
    //     //return OfferingImportMapFactory::new();
    // }
}
