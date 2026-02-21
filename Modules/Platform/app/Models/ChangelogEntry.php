<?php

namespace Modules\Platform\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChangelogEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'version',
        'date',
        'description',
        'published_at',
    ];

    protected $casts = [
        'date' => 'date',
        'published_at' => 'datetime',
    ];

    protected $table = 'changelog_entries';

    public function isPublished(): bool
    {
        return $this->published_at !== null;
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }
}
