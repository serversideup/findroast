<?php

namespace Modules\Offering\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Company\Models\Company;

class InvalidRoastUrl extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'url',
        'reason'
    ];

    protected $table = 'invalid_roast_urls';

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
