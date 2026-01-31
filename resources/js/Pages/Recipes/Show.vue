<template>
    <Head :title="recipe.name" />
    
    <div class="bg-gray-50 min-h-screen">
        <!-- Header -->
        <div class="bg-white border-b border-gray-200">
            <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <!-- Brew Method Badge -->
                        <div class="flex items-center gap-2 mb-4">
                            <img 
                                v-if="recipe.brew_method?.icon" 
                                :src="recipe.brew_method.icon" 
                                :alt="recipe.brew_method.name"
                                class="w-8 h-8 rounded-full"
                            />
                            <span class="text-sm font-medium text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                                {{ recipe.brew_method?.name }}
                            </span>
                        </div>

                        <h1 class="text-3xl font-bold text-gray-900">{{ recipe.name }}</h1>
                        
                        <p v-if="recipe.description" class="mt-4 text-gray-600">
                            {{ recipe.description }}
                        </p>

                        <!-- Author & Stats -->
                        <div class="mt-6 flex items-center gap-6 text-sm text-gray-500">
                            <div class="flex items-center gap-2">
                                <img 
                                    :src="recipe.user?.avatar" 
                                    :alt="recipe.user?.name"
                                    class="w-8 h-8 rounded-full"
                                />
                                <span>by {{ recipe.user?.name }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span>{{ recipe.views_count }} views</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2">
                        <button 
                            v-if="$page.props.auth.user"
                            @click="toggleSave"
                            class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                        >
                            <svg 
                                :class="[isSaved ? 'text-red-500 fill-current' : 'text-gray-400']" 
                                class="w-5 h-5" 
                                fill="none" 
                                stroke="currentColor" 
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            {{ isSaved ? 'Saved' : 'Save' }}
                        </button>

                        <Link 
                            v-if="$page.props.auth.user?.id === recipe.user_id"
                            :href="route('recipes.edit', recipe.slug)"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
                        >
                            Edit
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Recipe Parameters -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg border border-gray-200 p-6 sticky top-24">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Recipe Parameters</h2>
                        
                        <dl class="space-y-4">
                            <div v-if="recipe.coffee_dose">
                                <dt class="text-sm font-medium text-gray-500">Coffee Dose</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ recipe.coffee_dose }}</dd>
                            </div>
                            <div v-if="recipe.water_amount">
                                <dt class="text-sm font-medium text-gray-500">Water Amount</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ recipe.water_amount }}</dd>
                            </div>
                            <div v-if="recipe.water_temperature">
                                <dt class="text-sm font-medium text-gray-500">Water Temperature</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ recipe.water_temperature }}</dd>
                            </div>
                            <div v-if="recipe.grind_size">
                                <dt class="text-sm font-medium text-gray-500">Grind Size</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ recipe.grind_size }}</dd>
                            </div>
                            <div v-if="recipe.total_brew_time">
                                <dt class="text-sm font-medium text-gray-500">Total Brew Time</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ recipe.total_brew_time }}</dd>
                            </div>
                            <div v-if="recipe.yield">
                                <dt class="text-sm font-medium text-gray-500">Yield</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ recipe.yield }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Steps -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Brewing Steps</h2>
                        
                        <ol class="relative border-l border-gray-200 ml-3 space-y-8">
                            <li 
                                v-for="(step, index) in recipe.steps" 
                                :key="step.id"
                                class="ml-6"
                            >
                                <span class="absolute flex items-center justify-center w-8 h-8 bg-gray-800 rounded-full -left-4 text-white text-sm font-semibold">
                                    {{ index + 1 }}
                                </span>
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ step.title }}</h3>
                                    
                                    <div v-if="step.duration || step.water_amount" class="flex flex-wrap gap-4 mt-2 text-sm text-gray-600">
                                        <span v-if="step.duration" class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ step.duration }}
                                        </span>
                                        <span v-if="step.water_amount" class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                            </svg>
                                            {{ step.water_amount }}
                                        </span>
                                    </div>
                                    
                                    <p v-if="step.description" class="mt-3 text-gray-600">
                                        {{ step.description }}
                                    </p>
                                </div>
                            </li>
                        </ol>
                    </div>
                </div>
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
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const recipe = computed(() => usePage().props.recipe);
const isSaved = ref(usePage().props.isSaved);

const toggleSave = () => {
    router.post(route('recipes.toggle-save', recipe.value.slug), {}, {
        preserveScroll: true,
        onSuccess: () => {
            isSaved.value = !isSaved.value;
        },
    });
};
</script>



