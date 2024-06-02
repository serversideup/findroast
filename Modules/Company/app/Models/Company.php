<?php

namespace Modules\Company\Models;

use App\Models\User;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Offering\Models\OfferingImportMap;
// use Modules\Company\Database\Factories\CompanyFactory;

class Company extends Model
{
    use HasFactory, Sluggable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'status',
        'header_image',
        'logo',
        'slug',
        'roaster',
        'subscription',
        'description',
        'website',
        'address',
        'city',
        'state',
        'province',
        'territory',
        'country',
        'zip',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'added_by',
    ];

    /**
     * Set the table associated with the model.
     */
    protected $table = 'companies';

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'company_favorite', 'company_id', 'user_id');
    }

    public function cafes(): HasMany
    {
        return $this->hasMany(Cafe::class);
    }

    public function offeringImportMap(): HasOne
    {
        return $this->hasOne(OfferingImportMap::class, 'company_id', 'id');
    }

    // protected static function newFactory(): CompanyFactory
    // {
    //     //return CompanyFactory::new();
    // }
}
