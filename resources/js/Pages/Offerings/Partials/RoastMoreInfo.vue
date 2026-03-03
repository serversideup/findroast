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
                        <div class="relative bg-stone-100 rounded-md overflow-hidden">
                            <!-- Primary Image -->
                            <img
                                v-if="roast.primary_image"
                                :src="roast.primary_image"
                                alt="Roast Image"
                                class="w-full h-full object-cover"
                            >

                            <!-- Fallback with Company Logo -->
                            <div
                                v-else-if="roast.company?.logo"
                                class="w-full h-full flex items-center justify-center bg-gradient-to-br from-amber-50 via-stone-50 to-amber-50/50"
                            >
                                <img
                                    :src="roast.company.logo"
                                    :alt="roast.company.name"
                                    class="max-h-24 max-w-[60%] object-contain opacity-40"
                                />
                            </div>

                            <!-- Fallback without Logo -->
                            <div
                                v-else
                                class="w-full h-full flex items-center justify-center bg-gradient-to-br from-stone-100 via-stone-50 to-amber-50/30 min-h-[250px]"
                            >
                                <div class="text-center">
                                    <div class="w-16 h-16 mx-auto mb-2 rounded-full bg-amber-100/50 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-amber-700/30" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M2 21h19v-3H2v3zM20 8H4V6h16v2zm1.81-5.85C21.91 2.03 22 1.82 22 1.6V1c0-.55-.45-1-1-1H3c-.55 0-1 .45-1 1v.6c0 .22.09.43.24.58L4 4v1h16V4l1.81-1.85z"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs font-medium text-stone-400 uppercase tracking-wide">
                                        {{ roast.company?.name || 'Coffee' }}
                                    </p>
                                </div>
                            </div>
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