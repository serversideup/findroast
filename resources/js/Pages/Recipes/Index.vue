<template>
    <Head title="Recipes" />
    
    <div class="bg-white min-h-screen">
        <!-- Hero Section -->
        <div class="w-full relative h-96 bg-cover bg-center bg-no-repeat lg:h-80 bg-[url(/stock/coffee-beans.jpg)]">
            <div class="absolute inset-0 bg-black bg-opacity-50" />
            <div class="absolute inset-0 flex flex-col items-center justify-center gap-4">
                <h1 class="text-4xl font-bold tracking-tight text-white">Coffee Recipes</h1>
                <p class="text-lg text-gray-200 text-center max-w-2xl px-4">
                    Discover and share brewing recipes from the community
                </p>
                <Link v-if="$page.props.auth.user" :href="route('recipes.create')" class="inline-flex items-center px-4 py-2 bg-white border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-100 focus:bg-gray-100 active:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Create Recipe
                </Link>
            </div>
            <a target="_blank" href="https://www.pexels.com/photo/shallow-focus-photo-of-coffee-beans-894695/" class="text-white text-xs underline font-medium absolute bottom-2 right-2">📷: Juan Pablo Serrano</a>
        </div>

        <!-- Filters -->
        <div class="border-b border-gray-200 bg-gray-50">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row gap-4">
                    <!-- Search -->
                    <div class="flex-1">
                        <TextInput 
                            v-model="localFilters.search"
                            type="text"
                            placeholder="Search recipes..."
                            class="w-full"
                            @keyup.enter="applyFilters"
                        />
                    </div>
                    
                    <!-- Brew Method Filter -->
                    <div class="w-full sm:w-48">
                        <select 
                            v-model="localFilters.brew_method"
                            class="w-full border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-md shadow-sm"
                            @change="applyFilters"
                        >
                            <option :value="null">All Brew Methods</option>
                            <option v-for="method in brewMethods" :key="method.id" :value="method.id">
                                {{ method.name }}
                            </option>
                        </select>
                    </div>

                    <PrimaryButton @click="applyFilters">
                        Search
                    </PrimaryButton>
                </div>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-semibold text-gray-900">No recipes found</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new recipe.</p>
                <div class="mt-6">
                    <Link v-if="$page.props.auth.user" :href="route('recipes.create')" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        Create Recipe
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
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const recipes = computed(() => usePage().props.recipes);
const brewMethods = computed(() => usePage().props.brewMethods);
const filters = computed(() => usePage().props.filters);

const localFilters = ref({
    search: filters.value.search || '',
    brew_method: filters.value.brew_method || null,
});

const applyFilters = () => {
    router.get(route('recipes.index'), {
        search: localFilters.value.search || undefined,
        brew_method: localFilters.value.brew_method || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>
