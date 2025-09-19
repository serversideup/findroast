<?php

namespace Modules\Offering\Models;

use Modules\Company\Models\Company;
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
        'day',
        'collection_url',
        'container_selector',
        'product_list_item_selector',
        'product_selector',
        'last_synced_at',
    ];

    protected $table = 'offering_import_maps';

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // protected static function newFactory(): OfferingImportMapFactory
    // {
    //     //return OfferingImportMapFactory::new();
    // }
}
