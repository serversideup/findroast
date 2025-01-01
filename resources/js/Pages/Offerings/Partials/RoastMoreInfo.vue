<template>
    <Teleport to="body">
        <Transition leave-active-class="duration-200">
            <div class="fixed z-[999] inset-0 overflow-y-auto px-4 py-6 sm:px-0" scroll-region>
                <Transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div class="fixed inset-0 transform transition-all" @click="close">
                        <div class="absolute inset-0 bg-gray-500 opacity-75" />
                    </div>
                </Transition>

                <Transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                >
                    <div
                        class="p-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto sm:max-w-xl grid grid-cols-2 gap-4">
                        <div>
                            <img :src="roast.primary_image" alt="Roast Image" class="w-full h-full object-cover rounded-md">
                        </div>
                        <div class="flex flex-col gap-y-2">
                            <h3 class="text-lg font-medium text-gray-900">{{ roast.name }}</h3>
                            <p class="text-sm text-gray-500"><span class="font-bold">From:</span> {{ roast.price }}</p>
                            <p class="text-sm text-gray-500"><span class="font-bold">Origin:</span> {{ roast.countries.map(country => findFlag(country.name)+' '+country.name).join(', ') }}</p>
                            <p class="text-sm text-gray-500"><span class="font-bold">Variety:</span> {{ roast.varieties.map(variety => variety.name).join(', ') }}</p>
                            <p class="text-sm text-gray-500"><span class="font-bold">Process:</span> {{ roast.processes.map(process => process.name).join(', ') }}</p>
                            <p class="text-sm text-gray-500"><span class="font-bold">Flavor Notes:</span> {{ roast.flavor_notes.map(flavor_note => flavor_note.name).join(', ') }}</p>
                            <p class="text-sm text-gray-500"><span class="font-bold">Elevation:</span> {{ roast.elevations.map(elevation => elevation.name).join(', ') }}</p>
                            <div class="flex items-center justify-end space-x-2">
                                <a :href="roast.url" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Buy now
                                </a>
                            </div>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { useCountries } from '@/Composables/useCountries';

const props = defineProps({
    roast: Object
});

const {
    findFlag
} = useCountries();
</script>