<?php

namespace Modules\Recipe\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Recipe\Database\Factories\RecipeFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Models\BrewMethod;

class Recipe extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'brew_method_id',
        'name',
        'slug',
        'description',
        'coffee_dose',
        'water_amount',
        'water_temperature',
        'grind_size',
        'total_brew_time',
        'yield',
        'is_public',
        'views_count',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'views_count' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function brewMethod(): BelongsTo
    {
        return $this->belongsTo(BrewMethod::class);
    }

    public function steps(): HasMany
    {
        return $this->hasMany(RecipeStep::class)->orderBy('order');
    }

    public function savedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_saved_recipes')
            ->withTimestamps();
    }

    protected static function newFactory(): RecipeFactory
    {
        return RecipeFactory::new();
    }
}

