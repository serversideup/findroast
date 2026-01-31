<template>
    <Link 
        :href="route('recipes.show', recipe.slug)"
        class="group relative flex flex-col overflow-hidden rounded-lg border border-gray-200 bg-white hover:shadow-lg transition-shadow duration-200"
    >
        <!-- Brew Method Icon Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <img 
                    v-if="recipe.brew_method?.icon" 
                    :src="recipe.brew_method.icon" 
                    :alt="recipe.brew_method.name"
                    class="w-8 h-8 rounded-full"
                />
                <span class="text-sm font-medium text-gray-600">{{ recipe.brew_method?.name }}</span>
            </div>
            <div class="flex items-center gap-1 text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <span class="text-xs">{{ recipe.views_count }}</span>
            </div>
        </div>

        <!-- Content -->
        <div class="flex flex-1 flex-col p-4">
            <h3 class="font-semibold text-gray-900 text-lg group-hover:text-gray-600 transition-colors">
                {{ recipe.name }}
            </h3>
            
            <p v-if="recipe.description" class="mt-2 text-sm text-gray-500 line-clamp-2">
                {{ recipe.description }}
            </p>

            <!-- Recipe Stats -->
            <div class="mt-4 grid grid-cols-2 gap-2">
                <div v-if="recipe.coffee_dose" class="flex items-center gap-1 text-sm text-gray-600">
                    <span class="font-medium">Dose:</span>
                    <span>{{ recipe.coffee_dose }}</span>
                </div>
                <div v-if="recipe.water_amount" class="flex items-center gap-1 text-sm text-gray-600">
                    <span class="font-medium">Water:</span>
                    <span>{{ recipe.water_amount }}</span>
                </div>
                <div v-if="recipe.grind_size" class="flex items-center gap-1 text-sm text-gray-600">
                    <span class="font-medium">Grind:</span>
                    <span>{{ recipe.grind_size }}</span>
                </div>
                <div v-if="recipe.total_brew_time" class="flex items-center gap-1 text-sm text-gray-600">
                    <span class="font-medium">Time:</span>
                    <span>{{ recipe.total_brew_time }}</span>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <img 
                        :src="recipe.user?.avatar" 
                        :alt="recipe.user?.name"
                        class="w-6 h-6 rounded-full"
                    />
                    <span class="text-sm text-gray-500">{{ recipe.user?.name }}</span>
                </div>
                <div class="flex items-center gap-1 text-sm text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <span>{{ recipe.steps?.length || 0 }} steps</span>
                </div>
            </div>
        </div>
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    recipe: {
        type: Object,
        required: true,
    },
});
</script>



