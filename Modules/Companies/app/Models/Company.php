<?php

namespace Modules\Company\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    // protected static function newFactory(): CompanyFactory
    // {
    //     //return CompanyFactory::new();
    // }
}
