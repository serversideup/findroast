<template>
    <div class="flex flex-col gap-y-4 pb-10">
        <div class="w-full grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6 sm:gap-y-10 lg:gap-x-8 xl:grid-cols-4">
            <RoastCard v-for="roast in roasts.data" 
                :key="roast.id" 
                :roast="roast"
                :more-info="route('offerings.show', { roast: roast.id })" />

        </div>
        <div class="w-full flex items-center justify-between">
            <Link :href="roasts.prev_page_url ? roasts.prev_page_url : '#'" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 hover:text-gray-900 rounded-lg p-2 cursor-pointer">
                Previous
            </Link>

            <div class="flex items-center gap-2">
                <Link v-for="link in links" :href="link.url" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 hover:text-gray-900 rounded-lg p-2 cursor-pointer">
                    {{ link.label }}
                </Link>
            </div>
            
            <Link :href="roasts.next_page_url ? roasts.next_page_url : '#'" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 hover:text-gray-900 rounded-lg p-2 cursor-pointer">
                Next
            </Link>
        </div>
    </div>
</template>

<script setup>
import RoastCard from './RoastCard.vue';
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const roasts = computed(() => usePage().props.roasts);

const links = computed(() => {
    const filteredLinks = roasts.value.links.slice(1, -1);
    return filteredLinks;
});
</script>