<?php

namespace Modules\Offering\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Company\Models\Company;
use Modules\Offering\Models\Roast;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'company_id',
        'scraped_data'
    ];

    protected $table = 'batches';

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function roasts()
    {
        return $this->belongsToMany(Roast::class, 'batch_roast');
    }
}