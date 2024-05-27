<?php

namespace Modules\Company\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

// use Modules\Company\Database\Factories\CompanyFactory;

class Company extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
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
        'yelp_url',
        'added_by',
    ];

    /**
     * Set the table associated with the model.
     */
    protected $table = 'companies';

    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'company_favorite', 'company_id', 'user_id');
    }

    public function cafes(): HasMany
    {
        return $this->hasMany(Cafe::class);
    }

    // protected static function newFactory(): CompanyFactory
    // {
    //     //return CompanyFactory::new();
    // }
}
