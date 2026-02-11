<template>
    <div class="bg-gradient-to-b from-amber-50/80 to-stone-50 border-b border-stone-200">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 py-6 lg:py-8">
            <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 items-start">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <div class="h-24 w-24 sm:h-32 sm:w-32 rounded-xl bg-white border-2 border-white shadow-sm overflow-hidden flex items-center justify-center">
                        <img
                            v-if="company.logo"
                            :src="company.logo"
                            :alt="company.name"
                            class="h-full w-full object-contain p-2"
                        />
                        <BuildingStorefrontIcon
                            v-else
                            class="h-12 w-12 sm:h-16 sm:w-16 text-stone-300"
                        />
                    </div>
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-bold text-stone-900 tracking-tight">
                        {{ company.name }}
                    </h1>

                    <!-- Location & Badges -->
                    <div class="flex flex-wrap items-center gap-2 mt-2">
                        <p v-if="address" class="text-sm text-stone-600">
                            📍 {{ address }}
                        </p>
                        <span
                            v-if="company.roaster"
                            class="text-xs bg-amber-50 text-amber-800 border border-amber-200 rounded-full px-2 py-1 leading-none font-medium"
                        >
                            ☕️ Roaster
                        </span>
                        <span
                            v-if="company.subscription"
                            class="text-xs bg-amber-50 text-amber-800 border border-amber-200 rounded-full px-2 py-1 leading-none font-medium"
                        >
                            🛒 Subscription
                        </span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-2 mt-4">
                        <a
                            v-if="company.website"
                            :href="company.website"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm rounded-lg border border-stone-200 bg-white text-stone-700 hover:border-amber-300 hover:text-amber-800 transition-colors"
                        >
                            <GlobeAltIcon class="h-4 w-4" />
                            <span>Visit Website</span>
                        </a>

                        <!-- Social Links -->
                        <a
                            v-if="company.instagram_url"
                            :href="company.instagram_url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm rounded-lg border border-stone-200 bg-white text-stone-700 hover:border-amber-300 hover:text-amber-800 transition-colors"
                        >
                            Instagram
                        </a>
                        <a
                            v-if="company.facebook_url"
                            :href="company.facebook_url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm rounded-lg border border-stone-200 bg-white text-stone-700 hover:border-amber-300 hover:text-amber-800 transition-colors"
                        >
                            Facebook
                        </a>
                        <a
                            v-if="company.twitter_url"
                            :href="company.twitter_url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm rounded-lg border border-stone-200 bg-white text-stone-700 hover:border-amber-300 hover:text-amber-800 transition-colors"
                        >
                            Twitter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { BuildingStorefrontIcon, GlobeAltIcon } from '@heroicons/vue/24/outline';

const company = computed(() => usePage().props.company);

const address = computed(() => {
    if (!company.value.city) return '';

    switch(company.value.country) {
        case 'US':
            return `${company.value.city}, ${company.value.state}`;
        case 'CA':
            return `${company.value.city}, ${company.value.province}`;
        case 'AU':
            return `${company.value.city}, ${company.value.territory}`;
        default:
            return `${company.value.city}, ${company.value.country}`;
    }
});
</script>