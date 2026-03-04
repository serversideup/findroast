<?php

namespace Modules\Offering\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Offering\Models\Roast;

class Variety extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'migrated_to_id'
    ];

    protected $table = 'varieties';

    public function roasts()
    {
        return $this->belongsToMany(Roast::class, 'roast_varieties');
    }

    /**
     * Get the variety this was migrated to
     */
    public function migratedTo()
    {
        return $this->belongsTo(Variety::class, 'migrated_to_id');
    }

    /**
     * Get all varieties that were migrated to this one
     */
    public function migratedFrom()
    {
        return $this->hasMany(Variety::class, 'migrated_to_id');
    }
}
