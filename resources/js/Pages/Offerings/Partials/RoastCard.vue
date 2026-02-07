<template>
    <a
        :href="roast.url + '?ref=findroast'"
        target="_blank"
        class="group flex flex-col overflow-hidden rounded-lg border border-stone-200 bg-white hover:border-amber-300 hover:shadow-sm transition-all"
    >
        <!-- Image -->
        <div class="relative bg-stone-100 h-32 sm:h-36 overflow-hidden">
            <img
                :src="'/storage/' + roast.primary_image"
                :alt="roast.name"
                class="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-300"
            />
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
    const symbol = currency === 'USD' || !currency ? '$' : currency;
    return `${symbol}${parseFloat(price).toFixed(2)}`;
};
</script>
