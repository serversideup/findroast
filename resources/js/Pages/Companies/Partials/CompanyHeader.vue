<template>
    <div>
        <div class="relative z-10">
            <img class="h-32 w-full object-cover lg:h-48" src="/stock/company-default.jpg" alt="" />
            <a target="_blank" href="https://www.pexels.com/photo/close-up-photography-of-roasted-coffee-beans-773958/" class="text-white text-xs underline font-medium absolute bottom-2 right-2">📷: Adam Lukac</a>
        </div>
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="-mt-12 sm:-mt-16 sm:flex sm:items-end sm:space-x-5">
                <div class="flex">
                    <img class="h-24 w-24 rounded-xl bg-white ring-4 ring-gray-50 sm:h-32 sm:w-32 z-20" :src="company.logo" :alt="company.name" />
                </div>
                <div class="mt-6 sm:flex sm:min-w-0 sm:flex-1 sm:items-center sm:justify-end sm:space-x-6 sm:pb-1">
                    <div class="mt-6 min-w-0 flex-1 sm:hidden md:block">
                        <h1 class="truncate text-2xl font-bold text-gray-900">{{ company.name }}</h1>
                        <p class="text-gray-500 text-sm">📍 {{ address }}</p>
                    </div>
                    <div class="mt-6 flex flex-col justify-stretch space-y-3 sm:flex-row sm:space-x-4 sm:space-y-0">
                        <button type="button" class="inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            <HeartIcon class="-ml-0.5 mr-1.5 h-5 w-5 text-gray-400" aria-hidden="true" />
                            <span>Favorite</span>
                        </button>
                        <button type="button" class="inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            <PhoneIcon class="-ml-0.5 mr-1.5 h-5 w-5 text-gray-400" aria-hidden="true" />
                            <span>Call</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="mt-6 hidden min-w-0 flex-1 sm:block md:hidden">
                <h1 class="truncate text-2xl font-bold text-gray-900">{{ company.name }}</h1>
            </div>
        </div>
    </div>
</template>
  
<script setup>
import { HeartIcon, PhoneIcon } from '@heroicons/vue/20/solid'
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const company = computed(() => usePage().props.company);

const address = computed(() => {
    switch(company.value.country) {
        case 'US':
            return `${company.value.city}, ${company.value.state}`;
        case 'CA':
            return `${company.value.city}, ${company.value.province}`;
        case 'AU':
            return `${company.value.city}, ${company.value.territory}`;
        default:
            return `${company.value.city}, ${company.value.country}`;
    }
});
</script>