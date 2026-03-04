<?php

namespace Modules\Offering\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Offering\Database\Factories\ProcessFactory;

class Process extends Model
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

    protected $table = 'processes';

    public function roasts()
    {
        return $this->belongsToMany(Roast::class, 'roast_processes');
    }

    /**
     * Get the process this was migrated to
     */
    public function migratedTo()
    {
        return $this->belongsTo(Process::class, 'migrated_to_id');
    }

    /**
     * Get all processes that were migrated to this one
     */
    public function migratedFrom()
    {
        return $this->hasMany(Process::class, 'migrated_to_id');
    }

    // protected static function newFactory(): ProcessFactory
    // {
    //     //return ProcessFactory::new();
    // }
}
