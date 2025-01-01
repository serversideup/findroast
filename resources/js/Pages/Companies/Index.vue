<template>
    <Head title="Companies" />
    <div class="bg-white">
        <div class="w-full relative h-96 bg-cover bg-center bg-no-repeat lg:h-80 bg-[url(/stock/coffee-beans.jpg)]">
            <div class="absolute inset-0 bg-black bg-opacity-50" />
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <h1 class="text-4xl font-bold tracking-tight text-white">Browse Companies</h1>
                <!-- <button class="bg-white text-black px-4 py-2 rounded-md">Add Company</button> -->
            </div>
            <a target="_blank" href="https://www.pexels.com/photo/shallow-focus-photo-of-coffee-beans-894695/" class="text-white text-xs underline font-medium absolute bottom-2 right-2">📷: Juan Pablo Serrano</a>
        </div>

        <Filters />

        <div class="mx-auto max-w-3xl px-4 text-center sm:px-6 lg:max-w-7xl lg:px-8 mt-8 pb-8">
            <div class="grid grid-cols-1 gap-y-12 sm:grid-cols-2 sm:gap-x-6 lg:grid-cols-4 lg:gap-x-6">
                <CompanyCard v-for="company in companies.data" 
                    :key="company.id" 
                    :company="company" />
            </div>
        </div>
    </div>
</template>
  
  <script>
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
    layout: AppLayout
};
</script>

<script setup>
import CompanyCard from './Partials/CompanyCard.vue';
import Filters from './Partials/Filters.vue';
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';

const companies = computed(() => usePage().props.companies);
</script>