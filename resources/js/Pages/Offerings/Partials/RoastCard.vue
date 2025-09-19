<template>
    <a :href="roast.url+'?ref=findroast'" target="_blank"
        class="group relative flex flex-col overflow-hidden rounded-lg border border-gray-200 bg-white">
        <div class="aspect-h-4 aspect-w-3 bg-gray-200 relative sm:aspect-none group-hover:opacity-75 sm:h-96">
            <img :src="roast.primary_image" :alt="roast.name" class="h-full w-full object-cover object-center z-50 sm:h-full sm:w-full" />
        </div>
        <div class="flex flex-1 flex-col p-4">
            <h3 class="font-semibold text-gray-900 text-base">
                {{ roast.name }}
            </h3>
            <h4 class="text-gray-500 text-sm mb-2">
                {{ roast.countries.map(country => findFlag(country.name)+' '+country.name).join(', ') }}
            </h4>

            <div class="flex flex-1 flex-col space-y-2">
                <div class="w-full flex flex-col">
                    <p class="font-semibold text-gray-900 text-sm">Flavor Notes</p>
                    <div class="flex items-center flex-wrap gap-1">
                        <span 
                            v-for="note in roast.flavor_notes" 
                            class="text-xs text-[#344054] bg-[#F9FAFB] border border-[#EAECF0] rounded-full px-1.5 py-0.5">{{ note.name }}</span>
                    </div>
                </div>

                <div class="w-full grid grid-cols-2 gap-2">
                    <div class="flex flex-col">
                        <p class="font-semibold text-gray-900 text-sm">Processing</p>
                        <p class="text-sm text-gray-500">{{ roast.processes.map(process => process.name).join(', ') }}</p>
                    </div>
                    <div class="flex flex-col">
                        <p class="font-semibold text-gray-900 text-sm">Varietal</p>
                        <p class="text-sm text-gray-500">{{ roast.varieties.map(variety => variety.name).join(', ') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </a>
</template>

<script setup>
import { useCountries } from '@/Composables/useCountries';

const props = defineProps({
    roast: Object,
    moreInfo: String
});

const {
    findFlag
} = useCountries();
</script>