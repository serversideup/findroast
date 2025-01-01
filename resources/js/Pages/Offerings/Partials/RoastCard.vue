<template>
    <div 
        class="group relative flex flex-col overflow-hidden rounded-lg border border-gray-200 bg-white">
        <div class="aspect-h-4 aspect-w-3 bg-gray-200 relative sm:aspect-none group-hover:opacity-75 sm:h-96">
            <img :src="roast.primary_image" :alt="roast.name" class="h-full w-full object-cover object-center z-50 sm:h-full sm:w-full" />
        </div>
        <div class="hidden group-hover:flex absolute z-[999999] flex-col gap-y-2 items-center justify-center inset-0">
            <a :href="roast.url" target="_blank" class="cursor-pointer inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Buy now
            </a>
            <Link :href="moreInfo" :preserve-scroll="true" :preserve-state="true" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 bg-white text-gray-500 hover:text-gray-900 rounded-lg p-2 cursor-pointer">
                More Info
            </Link>
        </div>
        <div class="flex flex-1 flex-col space-y-2 p-4">
            <h3 class="text-sm font-medium text-gray-900">
                <a :href="roast.href">
                    <span aria-hidden="true" class="absolute inset-0" />
                    {{ roast.name }}
                </a>
            </h3>
            <!-- <p class="text-sm text-gray-500">{{ product.description }}</p> -->
            <div class="flex flex-1 flex-col justify-end">
                <p class="text-sm font-medium text-gray-600">☀️ {{ roast.processes.map(process => process.name).join(', ') }}</p>
                <p class="text-sm text-gray-500">😛 <span class="italic">{{ roast.flavor_notes.map(flavor_note => flavor_note.name).join(', ') }}</span></p>
                <p class="text-sm text-gray-500">☕️ {{ roast.varieties.map(variety => variety.name).join(', ') }} {{ roast.countries.map(country => findFlag(country.name)+' '+country.name).join(', ') }}</p>
                <p class="text-sm text-gray-500"></p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { useCountries } from '@/Composables/useCountries';

const props = defineProps({
    roast: Object,
    moreInfo: String
});

const {
    findFlag
} = useCountries();
</script>