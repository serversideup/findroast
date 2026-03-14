<template>
    <Link
        :href="route('companies.show', company.slug)"
        class="group flex flex-col overflow-hidden rounded-lg border border-stone-200 bg-white hover:border-amber-300 hover:shadow-sm transition-all"
    >
        <!-- Logo/Image -->
        <div
            :class="['relative h-32 sm:h-36 overflow-hidden flex items-center justify-center', company.logo ? (company.logo_background_color === 'black' ? 'bg-black' : 'bg-white') : 'bg-stone-100']"
        >
            <img
                v-if="company.logo"
                :src="company.logo"
                :alt="company.name"
                class="h-full w-full object-contain object-center p-4 group-hover:scale-105 transition-transform duration-300"
            />
            <div
                v-else
                class="h-full w-full flex items-center justify-center"
            >
                <BuildingStorefrontIcon class="h-16 w-16 text-stone-300" />
            </div>
        </div>

        <!-- Content -->
        <div class="flex flex-col gap-1 p-2.5">
            <!-- Company Name -->
            <h3 class="text-sm font-semibold text-stone-900 leading-tight line-clamp-2">
                {{ company.name }}
            </h3>

            <!-- Location -->
            <p
                v-if="address"
                class="text-xs text-stone-500 leading-tight"
            >
                📍 {{ address }}
            </p>

            <!-- Badges -->
            <div v-if="company.roaster || company.subscription" class="flex flex-wrap gap-0.5 mt-0.5">
                <span
                    v-if="company.roaster"
                    class="text-[10px] bg-amber-50 text-amber-800 border border-amber-200 rounded-full px-1.5 py-0.5 leading-none"
                >
                    ☕️ Roaster
                </span>
                <span
                    v-if="company.subscription"
                    class="text-[10px] bg-amber-50 text-amber-800 border border-amber-200 rounded-full px-1.5 py-0.5 leading-none"
                >
                    🛒 Subscription
                </span>
            </div>
        </div>
    </Link>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { BuildingStorefrontIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    company: Object
});

const address = computed(() => {
    if (!props.company.city) return '';

    switch(props.company.country) {
        case 'US':
            return `${props.company.city}, ${props.company.state}`;
        case 'CA':
            return `${props.company.city}, ${props.company.province}`;
        case 'AU':
            return `${props.company.city}, ${props.company.territory}`;
        default:
            return `${props.company.city}, ${props.company.country}`;
    }
});
</script>
