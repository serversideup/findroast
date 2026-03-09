<template>
    <div v-if="data.data && data.data.length > 0" class="flex items-center justify-between border-t border-stone-200 px-4 py-3 bg-stone-50">
        <div class="text-sm text-stone-600">
            Showing
            <span class="font-medium text-stone-900">{{ data.from }}</span>
            to
            <span class="font-medium text-stone-900">{{ data.to }}</span>
            of
            <span class="font-medium text-stone-900">{{ data.total }}</span>
            results
        </div>
        <div class="flex gap-2">
            <Link
                v-if="data.prev_page_url"
                :href="data.prev_page_url"
                preserve-scroll
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-stone-700 bg-white border border-stone-300 rounded-lg hover:bg-stone-50 transition-colors shadow-sm">
                Previous
            </Link>
            <Link
                v-if="data.next_page_url"
                :href="data.next_page_url"
                preserve-scroll
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-stone-700 bg-white border border-stone-300 rounded-lg hover:bg-stone-50 transition-colors shadow-sm">
                Next
            </Link>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    data: {
        type: Object,
        required: true
    }
});
</script>
