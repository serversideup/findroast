<template>
    <Link :href="route('companies.show', company.id)">
        <div class="group border border-gray-200 rounded-lg">
            <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-t-lg bg-white group-hover:opacity-75 relative">
                <img 
                    v-if="company.logo != null"
                    :src="company.logo" 
                    :alt="company.name" 
                    class="w-full h-full object-cover object-center"
                />
                <img 
                    v-else
                    src="/stock/latte-art.png" 
                    alt="Coffee Art" 
                    class="w-full h-full object-cover object-center"
                />
                <a v-if="company.logo == null" target="_blank" href="https://www.pexels.com/photo/person-performing-coffee-art-302899/" class="text-white text-xs underline font-medium absolute bottom-2 right-2">📷: Chevanon Photography</a>
            </div>
            <div class="py-4">
                <h3 class="font-medium text-gray-900">{{ company.name }}</h3>
                <p class="mt-1 text-sm text-gray-500">📍 {{ address }}</p>
                <p class="mt-1 text-sm text-gray-500" v-if="company.roaster == 1">☕️ Roaster</p>
                <p class="mt-1 text-sm text-gray-500" v-if="company.subscription == 1">🛒 Subscription</p>
            </div>
        </div>
    </Link>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    company: Object
});

const address = computed(() => {
    switch(props.company.country) {
        case 'US':
            return `${props.company.city}, ${props.company.state}`;
        case 'CA':
            return `${props.company.city}, ${props.company.province}`;
        case 'AU':
            return `${props.company.city}, ${props.company.territory}`;
        default:
            return `${props.company.city}, ${props.company.country}`;
    }
});
</script>