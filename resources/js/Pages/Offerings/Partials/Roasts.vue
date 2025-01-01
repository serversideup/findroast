<template>
    <div class="w-full grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6 sm:gap-y-10 lg:gap-x-8 xl:grid-cols-3">
        <RoastCard v-for="roast in roasts" 
            :key="roast.id" 
            :roast="roast"
            :more-info="route('offerings.show', { roast: roast.id })" />
    </div>
</template>

<script setup>
import RoastCard from './RoastCard.vue';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const roasts = computed(() => usePage().props.roasts);

</script>