<template>
    <div class="bg-stone-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 py-6">
            <!-- Description -->
            <div v-if="company.description" class="mb-8">
                <div class="bg-white rounded-lg border border-stone-200 p-4 sm:p-6">
                    <h2 class="text-lg font-semibold text-stone-900 mb-2">About</h2>
                    <p class="text-sm text-stone-600 leading-relaxed whitespace-pre-line">
                        {{ company.description }}
                    </p>
                </div>
            </div>

            <!-- Cafes -->
            <div v-if="company.cafes && company.cafes.length > 0" class="mb-8">
                <h2 class="text-lg font-semibold text-stone-900 mb-3">
                    Cafes & Locations
                </h2>
                <div class="bg-white rounded-lg border border-stone-200 divide-y divide-stone-100">
                    <div
                        v-for="cafe in company.cafes"
                        :key="cafe.id"
                        class="p-4 hover:bg-stone-50 transition-colors"
                    >
                        <div class="flex items-start gap-3">
                            <!-- Cafe Image/Icon -->
                            <div class="flex-shrink-0">
                                <div
                                    v-if="cafe.primary_image"
                                    class="h-12 w-12 rounded-lg bg-stone-100 overflow-hidden"
                                >
                                    <img
                                        :src="cafe.primary_image"
                                        :alt="cafe.name"
                                        class="h-full w-full object-cover"
                                    />
                                </div>
                                <div
                                    v-else
                                    class="h-12 w-12 rounded-lg bg-stone-100 flex items-center justify-center"
                                >
                                    <MapPinIcon class="h-6 w-6 text-stone-400" />
                                </div>
                            </div>

                            <!-- Cafe Info -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-semibold text-stone-900">
                                    {{ cafe.name }}
                                </h3>
                                <p class="text-xs text-stone-500 mt-0.5">
                                    📍 {{ formatCafeAddress(cafe) }}
                                </p>
                                <!-- Brew methods if available -->
                                <div v-if="cafe.brew_methods && cafe.brew_methods.length" class="flex flex-wrap gap-1 mt-2">
                                    <span
                                        v-for="method in cafe.brew_methods.slice(0, 3)"
                                        :key="method.id"
                                        class="text-[10px] bg-amber-50 text-amber-800 border border-amber-200 rounded-full px-1.5 py-0.5 leading-none"
                                    >
                                        {{ method.name }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Offerings -->
            <div v-if="company.roasts && company.roasts.length > 0">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-semibold text-stone-900">
                        Current Offerings
                    </h2>
                    <p class="text-xs text-stone-500">
                        <span class="font-medium text-stone-700">{{ company.roasts.length }}</span> coffees available
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">
                    <RoastCard
                        v-for="roast in company.roasts"
                        :key="roast.id"
                        :roast="roast"
                    />
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="(!company.roasts || company.roasts.length === 0) && (!company.cafes || company.cafes.length === 0) && !company.description" class="text-center py-12">
                <p class="text-stone-500 text-sm">No additional information available for this company.</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import RoastCard from '@/Pages/Offerings/Partials/RoastCard.vue';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { MapPinIcon } from '@heroicons/vue/20/solid';

const company = computed(() => usePage().props.company);

const formatCafeAddress = (cafe) => {
    const parts = [];

    if (cafe.address) parts.push(cafe.address);
    if (cafe.city) parts.push(cafe.city);

    if (cafe.country === 'US' && cafe.state) {
        parts.push(cafe.state);
    } else if (cafe.country === 'CA' && cafe.province) {
        parts.push(cafe.province);
    } else if (cafe.country === 'AU' && cafe.territory) {
        parts.push(cafe.territory);
    }

    return parts.join(', ');
};
</script>