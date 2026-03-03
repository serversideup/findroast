<?php

namespace Modules\Offering\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Offering\Database\Factories\FlavorNoteFactory;
use Modules\Offering\Models\Roast;

class FlavorNote extends Model
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

    protected $table = 'flavor_notes';

    // protected static function newFactory(): FlavorNoteFactory
    // {
    //     //return FlavorNoteFactory::new();
    // }

    public function roasts()
    {
        return $this->belongsToMany(Roast::class, 'roast_flavor_notes');
    }

    /**
     * Get the canonical flavor note this was migrated to
     */
    public function migratedTo()
    {
        return $this->belongsTo(FlavorNote::class, 'migrated_to_id');
    }

    /**
     * Get all flavor notes that were migrated to this one
     */
    public function migratedFrom()
    {
        return $this->hasMany(FlavorNote::class, 'migrated_to_id');
    }
}
