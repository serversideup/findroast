<?php

namespace Modules\Offering\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Offering\Database\Factories\ProcessFactory;

class Process extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected $table = 'processes';
    // protected static function newFactory(): ProcessFactory
    // {
    //     //return ProcessFactory::new();
    // }
}
