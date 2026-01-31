<?php

namespace Modules\Recipe\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Platform\Models\BrewMethod;
use Modules\Recipe\Models\Recipe;
use Modules\Recipe\Models\RecipeStep;
use Tests\TestCase;

class RecipeTest extends TestCase
{
    use RefreshDatabase;

    public function test_recipes_index_page_can_be_rendered(): void
    {
        $response = $this->get('/recipes');

        $response->assertStatus(200);
    }

    public function test_recipes_are_displayed_on_index_page(): void
    {
        $brewMethod = BrewMethod::factory()->create();
        $user = User::factory()->create();

        $recipe = Recipe::factory()->create([
            'user_id' => $user->id,
            'brew_method_id' => $brewMethod->id,
            'is_public' => true,
        ]);

        RecipeStep::factory()->create([
            'recipe_id' => $recipe->id,
            'order' => 1,
        ]);

        $response = $this->get('/recipes');

        $response->assertStatus(200);
        $response->assertSee($recipe->name);
    }

    public function test_single_recipe_page_can_be_rendered(): void
    {
        $brewMethod = BrewMethod::factory()->create();
        $user = User::factory()->create();

        $recipe = Recipe::factory()->create([
            'user_id' => $user->id,
            'brew_method_id' => $brewMethod->id,
            'is_public' => true,
        ]);

        RecipeStep::factory()->create([
            'recipe_id' => $recipe->id,
            'order' => 1,
        ]);

        $response = $this->get('/recipes/' . $recipe->slug);

        $response->assertStatus(200);
        $response->assertSee($recipe->name);
    }

    public function test_recipe_view_count_increments(): void
    {
        $brewMethod = BrewMethod::factory()->create();
        $user = User::factory()->create();

        $recipe = Recipe::factory()->create([
            'user_id' => $user->id,
            'brew_method_id' => $brewMethod->id,
            'is_public' => true,
            'views_count' => 0,
        ]);

        $this->get('/recipes/' . $recipe->slug);

        $recipe->refresh();
        $this->assertEquals(1, $recipe->views_count);
    }

    public function test_create_recipe_page_requires_authentication(): void
    {
        $response = $this->get('/recipes/create');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_create_recipe_page(): void
    {
        $user = User::factory()->create();
        BrewMethod::factory()->create();

        $response = $this->actingAs($user)->get('/recipes/create');

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_create_recipe(): void
    {
        $user = User::factory()->create();
        $brewMethod = BrewMethod::factory()->create();

        $response = $this->actingAs($user)->post('/recipes', [
            'brew_method_id' => $brewMethod->id,
            'name' => 'My Test Recipe',
            'description' => 'A delicious pour over recipe',
            'coffee_dose' => '18g',
            'water_amount' => '300ml',
            'water_temperature' => '93°C',
            'grind_size' => 'Medium-fine',
            'total_brew_time' => '3:00',
            'yield' => '~250ml',
            'is_public' => true,
            'steps' => [
                [
                    'title' => 'Bloom',
                    'description' => 'Pour 50ml and wait',
                    'duration' => '0:00 - 0:45',
                    'water_amount' => '50ml',
                ],
                [
                    'title' => 'First Pour',
                    'description' => 'Pour in circles',
                    'duration' => '0:45 - 1:30',
                    'water_amount' => '150ml',
                ],
            ],
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('recipes', [
            'user_id' => $user->id,
            'name' => 'My Test Recipe',
            'brew_method_id' => $brewMethod->id,
        ]);

        $recipe = Recipe::where('name', 'My Test Recipe')->first();
        $this->assertCount(2, $recipe->steps);
    }

    public function test_recipe_creation_requires_at_least_one_step(): void
    {
        $user = User::factory()->create();
        $brewMethod = BrewMethod::factory()->create();

        $response = $this->actingAs($user)->post('/recipes', [
            'brew_method_id' => $brewMethod->id,
            'name' => 'My Test Recipe',
            'is_public' => true,
            'steps' => [],
        ]);

        $response->assertSessionHasErrors('steps');
    }

    public function test_recipe_creation_requires_step_titles(): void
    {
        $user = User::factory()->create();
        $brewMethod = BrewMethod::factory()->create();

        $response = $this->actingAs($user)->post('/recipes', [
            'brew_method_id' => $brewMethod->id,
            'name' => 'My Test Recipe',
            'is_public' => true,
            'steps' => [
                [
                    'title' => '',
                    'description' => 'A step without title',
                ],
            ],
        ]);

        $response->assertSessionHasErrors('steps.0.title');
    }

    public function test_user_can_save_recipe_to_library(): void
    {
        $user = User::factory()->create();
        $brewMethod = BrewMethod::factory()->create();
        $recipe = Recipe::factory()->create([
            'brew_method_id' => $brewMethod->id,
            'is_public' => true,
        ]);

        $response = $this->actingAs($user)->post('/recipes/' . $recipe->slug . '/toggle-save');

        $response->assertRedirect();

        $this->assertTrue($user->savedRecipes()->where('recipe_id', $recipe->id)->exists());
    }

    public function test_user_can_unsave_recipe_from_library(): void
    {
        $user = User::factory()->create();
        $brewMethod = BrewMethod::factory()->create();
        $recipe = Recipe::factory()->create([
            'brew_method_id' => $brewMethod->id,
            'is_public' => true,
        ]);

        $user->savedRecipes()->attach($recipe->id);

        $response = $this->actingAs($user)->post('/recipes/' . $recipe->slug . '/toggle-save');

        $response->assertRedirect();

        $this->assertFalse($user->savedRecipes()->where('recipe_id', $recipe->id)->exists());
    }

    public function test_saved_recipes_page_requires_authentication(): void
    {
        $response = $this->get('/recipes/saved');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_saved_recipes(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/recipes/saved');

        $response->assertStatus(200);
    }

    public function test_my_recipes_page_requires_authentication(): void
    {
        $response = $this->get('/recipes/my-recipes');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_my_recipes(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/recipes/my-recipes');

        $response->assertStatus(200);
    }

    public function test_owner_can_edit_their_recipe(): void
    {
        $user = User::factory()->create();
        $brewMethod = BrewMethod::factory()->create();
        $recipe = Recipe::factory()->create([
            'user_id' => $user->id,
            'brew_method_id' => $brewMethod->id,
        ]);

        RecipeStep::factory()->create([
            'recipe_id' => $recipe->id,
            'order' => 1,
        ]);

        $response = $this->actingAs($user)->get('/recipes/' . $recipe->slug . '/edit');

        $response->assertStatus(200);
    }

    public function test_non_owner_cannot_edit_recipe(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $brewMethod = BrewMethod::factory()->create();

        $recipe = Recipe::factory()->create([
            'user_id' => $owner->id,
            'brew_method_id' => $brewMethod->id,
        ]);

        RecipeStep::factory()->create([
            'recipe_id' => $recipe->id,
            'order' => 1,
        ]);

        $response = $this->actingAs($otherUser)->get('/recipes/' . $recipe->slug . '/edit');

        $response->assertForbidden();
    }

    public function test_owner_can_update_their_recipe(): void
    {
        $user = User::factory()->create();
        $brewMethod = BrewMethod::factory()->create();
        $recipe = Recipe::factory()->create([
            'user_id' => $user->id,
            'brew_method_id' => $brewMethod->id,
            'name' => 'Original Name',
        ]);

        RecipeStep::factory()->create([
            'recipe_id' => $recipe->id,
            'order' => 1,
        ]);

        $response = $this->actingAs($user)->put('/recipes/' . $recipe->slug, [
            'brew_method_id' => $brewMethod->id,
            'name' => 'Updated Name',
            'is_public' => true,
            'steps' => [
                [
                    'title' => 'New Step',
                    'description' => 'Updated step description',
                ],
            ],
        ]);

        $response->assertRedirect();

        $recipe->refresh();
        $this->assertEquals('Updated Name', $recipe->name);
    }

    public function test_owner_can_delete_their_recipe(): void
    {
        $user = User::factory()->create();
        $brewMethod = BrewMethod::factory()->create();
        $recipe = Recipe::factory()->create([
            'user_id' => $user->id,
            'brew_method_id' => $brewMethod->id,
        ]);

        $response = $this->actingAs($user)->delete('/recipes/' . $recipe->slug);

        $response->assertRedirect('/recipes');

        $this->assertSoftDeleted('recipes', [
            'id' => $recipe->id,
        ]);
    }

    public function test_non_owner_cannot_delete_recipe(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $brewMethod = BrewMethod::factory()->create();

        $recipe = Recipe::factory()->create([
            'user_id' => $owner->id,
            'brew_method_id' => $brewMethod->id,
        ]);

        $response = $this->actingAs($otherUser)->delete('/recipes/' . $recipe->slug);

        $response->assertForbidden();

        $this->assertDatabaseHas('recipes', [
            'id' => $recipe->id,
        ]);
    }

    public function test_private_recipes_are_not_visible_on_index(): void
    {
        $user = User::factory()->create();
        $brewMethod = BrewMethod::factory()->create();

        $publicRecipe = Recipe::factory()->create([
            'user_id' => $user->id,
            'brew_method_id' => $brewMethod->id,
            'is_public' => true,
            'name' => 'Public Recipe',
        ]);

        $privateRecipe = Recipe::factory()->create([
            'user_id' => $user->id,
            'brew_method_id' => $brewMethod->id,
            'is_public' => false,
            'name' => 'Private Recipe',
        ]);

        $response = $this->get('/recipes');

        $response->assertSee('Public Recipe');
        $response->assertDontSee('Private Recipe');
    }

    public function test_recipes_can_be_filtered_by_search(): void
    {
        $user = User::factory()->create();
        $brewMethod = BrewMethod::factory()->create();

        Recipe::factory()->create([
            'user_id' => $user->id,
            'brew_method_id' => $brewMethod->id,
            'name' => 'V60 Pour Over',
            'is_public' => true,
        ]);

        Recipe::factory()->create([
            'user_id' => $user->id,
            'brew_method_id' => $brewMethod->id,
            'name' => 'AeroPress Recipe',
            'is_public' => true,
        ]);

        $response = $this->get('/recipes?search=V60');

        $response->assertSee('V60 Pour Over');
        $response->assertDontSee('AeroPress Recipe');
    }

    public function test_recipes_can_be_filtered_by_brew_method(): void
    {
        $user = User::factory()->create();
        $v60Method = BrewMethod::factory()->create(['name' => 'V60']);
        $aeropressMethod = BrewMethod::factory()->create(['name' => 'AeroPress']);

        Recipe::factory()->create([
            'user_id' => $user->id,
            'brew_method_id' => $v60Method->id,
            'name' => 'V60 Recipe',
            'is_public' => true,
        ]);

        Recipe::factory()->create([
            'user_id' => $user->id,
            'brew_method_id' => $aeropressMethod->id,
            'name' => 'AeroPress Recipe',
            'is_public' => true,
        ]);

        $response = $this->get('/recipes?brew_method=' . $v60Method->id);

        $response->assertSee('V60 Recipe');
        $response->assertDontSee('AeroPress Recipe');
    }
}



