<?php

namespace Modules\Recipe\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Platform\Models\BrewMethod;
use Modules\Recipe\Http\Requests\StoreRecipeRequest;
use Modules\Recipe\Http\Requests\UpdateRecipeRequest;
use Modules\Recipe\Models\Recipe;

class RecipeController extends Controller
{
    /**
     * Display a listing of recipes.
     */
    public function index(Request $request): Response
    {
        $recipes = Recipe::query()
            ->with(['user', 'brewMethod', 'steps'])
            ->where('is_public', true)
            ->when($request->get('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($request->get('brew_method'), function ($query, $brewMethodId) {
                $query->where('brew_method_id', $brewMethodId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        $brewMethods = BrewMethod::orderBy('name')->get();

        return Inertia::render('Recipes/Index', [
            'recipes' => $recipes,
            'brewMethods' => $brewMethods,
            'filters' => [
                'search' => $request->get('search'),
                'brew_method' => $request->get('brew_method'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new recipe.
     */
    public function create(): Response
    {
        $brewMethods = BrewMethod::orderBy('name')->get();

        return Inertia::render('Recipes/Create', [
            'brewMethods' => $brewMethods,
        ]);
    }

    /**
     * Store a newly created recipe.
     */
    public function store(StoreRecipeRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $recipe = $request->user()->recipes()->create([
            'brew_method_id' => $validated['brew_method_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . Str::random(6),
            'description' => $validated['description'] ?? null,
            'coffee_dose' => $validated['coffee_dose'] ?? null,
            'water_amount' => $validated['water_amount'] ?? null,
            'water_temperature' => $validated['water_temperature'] ?? null,
            'grind_size' => $validated['grind_size'] ?? null,
            'total_brew_time' => $validated['total_brew_time'] ?? null,
            'yield' => $validated['yield'] ?? null,
            'is_public' => $validated['is_public'] ?? true,
        ]);

        foreach ($validated['steps'] as $index => $step) {
            $recipe->steps()->create([
                'order' => $index + 1,
                'title' => $step['title'],
                'description' => $step['description'] ?? null,
                'duration' => $step['duration'] ?? null,
                'water_amount' => $step['water_amount'] ?? null,
            ]);
        }

        return redirect()->route('recipes.show', $recipe->slug)
            ->with('success', 'Recipe created successfully!');
    }

    /**
     * Display the specified recipe.
     */
    public function show(string $slug): Response
    {
        $recipe = Recipe::query()
            ->with(['user', 'brewMethod', 'steps'])
            ->where('slug', $slug)
            ->firstOrFail();

        $recipe->increment('views_count');

        $isSaved = false;
        if (auth()->check()) {
            $isSaved = $recipe->savedByUsers()->where('user_id', auth()->id())->exists();
        }

        return Inertia::render('Recipes/Show', [
            'recipe' => $recipe,
            'isSaved' => $isSaved,
        ]);
    }

    /**
     * Show the form for editing the specified recipe.
     */
    public function edit(string $slug): Response
    {
        $recipe = Recipe::query()
            ->with(['steps'])
            ->where('slug', $slug)
            ->firstOrFail();

        $this->authorize('update', $recipe);

        $brewMethods = BrewMethod::orderBy('name')->get();

        return Inertia::render('Recipes/Edit', [
            'recipe' => $recipe,
            'brewMethods' => $brewMethods,
        ]);
    }

    /**
     * Update the specified recipe.
     */
    public function update(UpdateRecipeRequest $request, string $slug): RedirectResponse
    {
        $recipe = Recipe::where('slug', $slug)->firstOrFail();

        $this->authorize('update', $recipe);

        $validated = $request->validated();

        $recipe->update([
            'brew_method_id' => $validated['brew_method_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'coffee_dose' => $validated['coffee_dose'] ?? null,
            'water_amount' => $validated['water_amount'] ?? null,
            'water_temperature' => $validated['water_temperature'] ?? null,
            'grind_size' => $validated['grind_size'] ?? null,
            'total_brew_time' => $validated['total_brew_time'] ?? null,
            'yield' => $validated['yield'] ?? null,
            'is_public' => $validated['is_public'] ?? true,
        ]);

        $recipe->steps()->delete();

        foreach ($validated['steps'] as $index => $step) {
            $recipe->steps()->create([
                'order' => $index + 1,
                'title' => $step['title'],
                'description' => $step['description'] ?? null,
                'duration' => $step['duration'] ?? null,
                'water_amount' => $step['water_amount'] ?? null,
            ]);
        }

        return redirect()->route('recipes.show', $recipe->slug)
            ->with('success', 'Recipe updated successfully!');
    }

    /**
     * Remove the specified recipe.
     */
    public function destroy(string $slug): RedirectResponse
    {
        $recipe = Recipe::where('slug', $slug)->firstOrFail();

        $this->authorize('delete', $recipe);

        $recipe->delete();

        return redirect()->route('recipes.index')
            ->with('success', 'Recipe deleted successfully!');
    }

    /**
     * Toggle saving a recipe to user's library.
     */
    public function toggleSave(string $slug): RedirectResponse
    {
        $recipe = Recipe::where('slug', $slug)->firstOrFail();
        $user = auth()->user();

        if ($user->savedRecipes()->where('recipe_id', $recipe->id)->exists()) {
            $user->savedRecipes()->detach($recipe->id);
            $message = 'Recipe removed from your library.';
        } else {
            $user->savedRecipes()->attach($recipe->id);
            $message = 'Recipe saved to your library!';
        }

        return back()->with('success', $message);
    }

    /**
     * Display user's saved recipes.
     */
    public function saved(Request $request): Response
    {
        $recipes = $request->user()
            ->savedRecipes()
            ->with(['user', 'brewMethod', 'steps'])
            ->orderBy('user_saved_recipes.created_at', 'desc')
            ->paginate(12);

        return Inertia::render('Recipes/Saved', [
            'recipes' => $recipes,
        ]);
    }

    /**
     * Display user's own recipes.
     */
    public function myRecipes(Request $request): Response
    {
        $recipes = $request->user()
            ->recipes()
            ->with(['brewMethod', 'steps'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return Inertia::render('Recipes/MyRecipes', [
            'recipes' => $recipes,
        ]);
    }
}
