<template>
    <div class="flex flex-col gap-3 pb-6">
        <!-- Results count -->
        <div class="flex items-center justify-between">
            <p class="text-xs text-stone-500">
                <span class="font-medium text-stone-700">{{ roasts.total }}</span> coffees found
            </p>
        </div>

        <!-- Grid -->
        <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">
            <RoastCard
                v-for="roast in roasts.data"
                :key="roast.id"
                :roast="roast"
            />
        </div>

        <!-- Empty state -->
        <div v-if="roasts.data.length === 0" class="text-center py-12">
            <p class="text-stone-500 text-sm">No coffees match your filters.</p>
            <p class="text-stone-400 text-xs mt-1">Try adjusting your search or removing some filters.</p>
        </div>

        <!-- Pagination -->
        <div v-if="roasts.last_page > 1" class="flex items-center justify-center gap-1 pt-2">
            <Link
                v-if="roasts.prev_page_url"
                :href="roasts.prev_page_url"
                class="px-2.5 py-1.5 text-xs text-stone-600 hover:text-stone-900 border border-stone-200 rounded-md hover:bg-stone-50 transition-colors"
            >
                &larr; Prev
            </Link>

            <template v-for="link in paginationLinks" :key="link.label">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    :class="[
                        'px-2.5 py-1.5 text-xs rounded-md transition-colors',
                        link.active
                            ? 'bg-amber-700 text-white font-medium'
                            : 'text-stone-600 hover:text-stone-900 border border-stone-200 hover:bg-stone-50'
                    ]"
                >
                    {{ link.label }}
                </Link>
                <span v-else class="px-1.5 text-stone-300 text-xs">&hellip;</span>
            </template>

            <Link
                v-if="roasts.next_page_url"
                :href="roasts.next_page_url"
                class="px-2.5 py-1.5 text-xs text-stone-600 hover:text-stone-900 border border-stone-200 rounded-md hover:bg-stone-50 transition-colors"
            >
                Next &rarr;
            </Link>
        </div>
    </div>
</template>

<script setup>
import RoastCard from './RoastCard.vue';
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const roasts = computed(() => usePage().props.roasts);

const paginationLinks = computed(() => {
    return roasts.value.links.slice(1, -1);
});
</script>
