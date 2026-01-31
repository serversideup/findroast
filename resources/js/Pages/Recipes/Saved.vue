<template>
    <Head title="Saved Recipes" />
    
    <div class="bg-gray-50 min-h-screen">
        <!-- Header -->
        <div class="bg-white border-b border-gray-200">
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-gray-900">Saved Recipes</h1>
                <p class="mt-2 text-gray-600">Recipes you've saved to your library</p>
            </div>
        </div>

        <!-- Recipe Grid -->
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div v-if="recipes.data.length > 0" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <RecipeCard 
                    v-for="recipe in recipes.data" 
                    :key="recipe.id" 
                    :recipe="recipe" 
                />
            </div>

            <div v-else class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-semibold text-gray-900">No saved recipes</h3>
                <p class="mt-1 text-sm text-gray-500">Start exploring and save recipes you like!</p>
                <div class="mt-6">
                    <Link :href="route('recipes.index')" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        Browse Recipes
                    </Link>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="recipes.data.length > 0" class="mt-8 flex justify-center">
                <nav class="flex items-center gap-2">
                    <Link 
                        v-for="link in recipes.links" 
                        :key="link.label"
                        :href="link.url || '#'"
                        :class="[
                            'px-3 py-2 text-sm font-medium rounded-md',
                            link.active 
                                ? 'bg-gray-800 text-white' 
                                : link.url 
                                    ? 'text-gray-700 hover:bg-gray-100' 
                                    : 'text-gray-400 cursor-not-allowed'
                        ]"
                        v-html="link.label"
                        preserve-scroll
                    />
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
    layout: AppLayout
};
</script>

<script setup>
import RecipeCard from './Partials/RecipeCard.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const recipes = computed(() => usePage().props.recipes);
</script>



