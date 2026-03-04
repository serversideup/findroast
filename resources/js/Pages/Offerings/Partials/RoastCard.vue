<template>
    <a
        :href="roast.url + '?ref=findroast'"
        target="_blank"
        class="group flex flex-col overflow-hidden rounded-lg border border-stone-200 bg-white hover:border-amber-300 hover:shadow-sm transition-all"
    >
        <!-- Image -->
        <div class="relative bg-stone-100 h-32 sm:h-36 overflow-hidden">
            <!-- Primary Image -->
            <img
                v-if="roast.primary_image"
                :src="'/storage/' + roast.primary_image"
                :alt="roast.name"
                class="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-300"
            />

            <!-- Fallback with Company Logo -->
            <div
                v-else-if="roast.company?.logo"
                class="h-full w-full flex items-center justify-center bg-gradient-to-br from-amber-50 via-stone-50 to-amber-50/50"
            >
                <img
                    :src="roast.company.logo"
                    :alt="roast.company.name"
                    class="max-h-16 max-w-[60%] object-contain opacity-40 group-hover:opacity-60 transition-opacity duration-300"
                />
            </div>

            <!-- Fallback without Logo -->
            <div
                v-else
                class="h-full w-full flex items-center justify-center bg-gradient-to-br from-stone-100 via-stone-50 to-amber-50/30"
            >
                <div class="text-center">
                    <div class="w-12 h-12 mx-auto mb-1 rounded-full bg-amber-100/50 flex items-center justify-center">
                        <!-- <svg class="w-6 h-6 text-amber-700/30" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2 21h19v-3H2v3zM20 8H4V6h16v2zm1.81-5.85C21.91 2.03 22 1.82 22 1.6V1c0-.55-.45-1-1-1H3c-.55 0-1 .45-1 1v.6c0 .22.09.43.24.58L4 4v1h16V4l1.81-1.85z"/>
                        </svg> -->
                        <span class="text-5xl">☕</span>
                    </div>
                    <p class="text-[10px] font-medium text-stone-400 uppercase tracking-wide">
                        {{ roast.company?.name || 'Coffee' }}
                    </p>
                </div>
            </div>

            <div
                v-if="roast.price"
                class="absolute top-1.5 right-1.5 bg-white/90 backdrop-blur-sm text-xs font-semibold text-stone-800 px-1.5 py-0.5 rounded"
            >
                {{ formatPrice(roast.price, roast.currency) }}
            </div>
        </div>

        <!-- Content -->
        <div class="flex flex-col gap-1 p-2.5">
            <!-- Company -->
            <p
                v-if="roast.company"
                class="text-[11px] font-medium text-amber-700 uppercase tracking-wide leading-none"
            >
                {{ roast.company.name }}
            </p>

            <!-- Name -->
            <h3 class="text-sm font-semibold text-stone-900 leading-tight line-clamp-2">
                {{ roast.name }}
            </h3>

            <!-- Origin -->
            <p
                v-if="roast.countries?.length"
                class="text-xs text-stone-500 leading-tight"
            >
                {{ roast.countries.map(c => findFlag(c.name) + ' ' + c.name).join(', ') }}
            </p>

            <!-- Flavor Notes -->
            <div v-if="roast.flavor_notes?.length" class="flex flex-wrap gap-0.5 mt-0.5">
                <span
                    v-for="note in roast.flavor_notes.slice(0, 4)"
                    :key="note.id"
                    class="text-[10px] bg-amber-50 text-amber-800 border border-amber-200 rounded-full px-1.5 py-0.5 leading-none"
                >
                    {{ note.name }}
                </span>
                <span
                    v-if="roast.flavor_notes.length > 4"
                    class="text-[10px] text-stone-400 px-1 py-0.5 leading-none"
                >
                    +{{ roast.flavor_notes.length - 4 }}
                </span>
            </div>

            <!-- Process -->
            <p
                v-if="roast.processes?.length"
                class="text-[11px] text-stone-400 mt-0.5"
            >
                {{ roast.processes.map(p => p.name).join(' · ') }}
            </p>
        </div>
    </a>
</template>

<script setup>
import { useCountries } from '@/Composables/useCountries';

const props = defineProps({
    roast: Object
});

const { findFlag } = useCountries();

const formatPrice = (price, currency) => {
    if (!price) return '';

    const currencySymbols = {
        'USD': '$',
        'GBP': '£',
        'EUR': '€',
        'AUD': 'A$',
        'CAD': 'C$',
    };

    const symbol = currencySymbols[currency] || currency || '$';
    return `${symbol}${parseFloat(price).toFixed(2)}`;
};
</script>
