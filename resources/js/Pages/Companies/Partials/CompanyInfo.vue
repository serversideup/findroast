<template>
    <div class="mx-auto max-w-5xl px-4 flex flex-col mt-8 sm:px-6 lg:px-8">
        <div class="flex flex-col">
            <h3 class="text-2xl font-medium text-gray-900">Services</h3>
            <div class="grid grid-cols-3 gap-4 mt-5">
                <div v-if="company.roaster" class="border border-gray-200 p-4 rounded-md">
                    <h4 class="text-lg font-medium text-gray-900">☕️ Roaster</h4>
                    <p class="text-sm text-gray-500">This company roasts their own coffee.</p>
                </div>
                <div v-if="company.subscription" class="border border-gray-200 p-4 rounded-md">
                    <h4 class="text-lg font-medium text-gray-900">🛒 Subscription Available</h4>
                    <p class="text-sm text-gray-500">This company offers a subscription service for their coffee.</p>
                </div>
            </div>

            <h3 class="text-2xl font-medium text-gray-900 mt-8" v-if="company.cafes.length > 0">Cafes</h3>

            <h3 class="text-2xl font-medium text-gray-900 mt-8">Current Offerings</h3>

            <div class="grid grid-cols-4 gap-4 mt-5">
                <RoastCard v-for="roast in company.roasts" 
                    :key="roast.id" 
                    :roast="roast"/>
            </div>
        </div>
    </div>
</template>

<script setup>
import RoastCard from '@/Pages/Offerings/Partials/RoastCard.vue';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useCountries } from '@/Composables/useCountries';

const company = computed(() => usePage().props.company);

const {
    findFlag
} = useCountries();
</script>