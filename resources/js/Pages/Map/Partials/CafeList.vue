<template>
    <!-- Desktop Sidebar -->
    <div class="hidden lg:flex flex-col w-96 flex-shrink-0 border-r border-stone-200 bg-white">
        <!-- Search + Count -->
        <div class="px-3 pt-3 pb-2 border-b border-stone-100 flex-shrink-0">
            <div class="relative">
                <MagnifyingGlassIcon class="absolute left-2.5 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-stone-400" />
                <input
                    type="text"
                    v-model="filters.search"
                    placeholder="Search cafes..."
                    class="w-full text-sm border border-stone-200 rounded-lg pl-8 pr-3 py-1.5 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 placeholder:text-stone-400"
                />
            </div>
            <p class="text-xs text-stone-400 mt-2">
                <span class="font-medium text-stone-600">{{ cafes.length }}</span> cafes
            </p>
        </div>

        <!-- Scrollable List -->
        <div class="flex-1 overflow-y-auto divide-y divide-stone-100">
            <CafeListCard
                v-for="cafe in cafes"
                :key="cafe.id"
                :cafe="cafe"
                :active="selectedCafe?.id === cafe.id"
                @select="selectCafe(cafe)"
                @hover="hoveredCafe = cafe"
                @unhover="hoveredCafe = null"
            />

            <div v-if="cafes.length === 0" class="px-4 py-8 text-center">
                <MapPinIcon class="h-8 w-8 text-stone-300 mx-auto mb-2" />
                <p class="text-sm text-stone-500">No cafes match your filters.</p>
                <p class="text-xs text-stone-400 mt-1">Try adjusting your search or filters.</p>
            </div>
        </div>
    </div>

    <!-- Mobile Bottom Sheet -->
    <div class="lg:hidden">
        <!-- Peek trigger bar -->
        <div
            class="fixed bottom-0 left-0 right-0 z-30 bg-white rounded-t-2xl shadow-[0_-4px_20px_rgba(0,0,0,0.1)] border-t border-stone-200"
            @click="mobileOpen = true"
        >
            <div class="flex justify-center pt-2 pb-1">
                <div class="w-10 h-1 bg-stone-300 rounded-full" />
            </div>
            <div class="px-4 pb-3">
                <p class="text-sm font-medium text-stone-900">
                    {{ cafes.length }} cafes
                </p>
                <!-- Preview of first 2 cafes -->
                <div v-if="cafes.length > 0" class="flex gap-2 mt-2 overflow-hidden">
                    <div
                        v-for="cafe in cafes.slice(0, 2)"
                        :key="cafe.id"
                        class="flex items-center gap-2 bg-stone-50 rounded-lg px-2.5 py-1.5 flex-shrink-0"
                    >
                        <MapPinIcon class="h-3.5 w-3.5 text-amber-600 flex-shrink-0" />
                        <span class="text-xs text-stone-700 truncate max-w-[120px]">{{ cafe.name }}</span>
                    </div>
                    <span v-if="cafes.length > 2" class="text-xs text-stone-400 self-center flex-shrink-0">
                        +{{ cafes.length - 2 }} more
                    </span>
                </div>
            </div>
        </div>

        <!-- Full Sheet -->
        <TransitionRoot :show="mobileOpen" as="template">
            <Dialog class="relative z-50 lg:hidden" @close="mobileOpen = false">
                <TransitionChild
                    as="template"
                    enter="ease-out duration-300"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="ease-in duration-200"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-black/30" />
                </TransitionChild>

                <TransitionChild
                    as="template"
                    enter="ease-out duration-300"
                    enter-from="translate-y-full"
                    enter-to="translate-y-0"
                    leave="ease-in duration-200"
                    leave-from="translate-y-0"
                    leave-to="translate-y-full"
                >
                    <div class="fixed inset-0 flex items-end">
                        <DialogPanel class="w-full max-h-[80vh] bg-white rounded-t-2xl shadow-xl flex flex-col">
                            <!-- Drag handle -->
                            <div class="flex justify-center pt-2 pb-1 flex-shrink-0">
                                <div class="w-10 h-1 bg-stone-300 rounded-full" />
                            </div>

                            <!-- Header -->
                            <div class="flex items-center justify-between px-4 pb-2 flex-shrink-0">
                                <p class="text-base font-semibold text-stone-900">
                                    {{ cafes.length }} cafes
                                </p>
                                <button
                                    type="button"
                                    @click="mobileOpen = false"
                                    class="p-1 text-stone-400 hover:text-stone-600"
                                >
                                    <XMarkIcon class="h-5 w-5" />
                                </button>
                            </div>

                            <!-- Search -->
                            <div class="px-4 pb-2 flex-shrink-0">
                                <div class="relative">
                                    <MagnifyingGlassIcon class="absolute left-2.5 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-stone-400" />
                                    <input
                                        type="text"
                                        v-model="filters.search"
                                        placeholder="Search cafes..."
                                        class="w-full text-sm border border-stone-200 rounded-lg pl-8 pr-3 py-1.5 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 placeholder:text-stone-400"
                                    />
                                </div>
                            </div>

                            <!-- List -->
                            <div class="flex-1 overflow-y-auto divide-y divide-stone-100">
                                <CafeListCard
                                    v-for="cafe in cafes"
                                    :key="cafe.id"
                                    :cafe="cafe"
                                    :active="selectedCafe?.id === cafe.id"
                                    @select="selectCafe(cafe); mobileOpen = false"
                                    @hover="hoveredCafe = cafe"
                                    @unhover="hoveredCafe = null"
                                />

                                <div v-if="cafes.length === 0" class="px-4 py-8 text-center">
                                    <p class="text-sm text-stone-500">No cafes match your filters.</p>
                                </div>
                            </div>
                        </DialogPanel>
                    </div>
                </TransitionChild>
            </Dialog>
        </TransitionRoot>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Dialog, DialogPanel, TransitionRoot, TransitionChild } from '@headlessui/vue';
import { MagnifyingGlassIcon, MapPinIcon, XMarkIcon } from '@heroicons/vue/20/solid';
import { useCafeMap } from '@/Composables/useCafeMap';
import CafeListCard from './CafeListCard.vue';

defineProps({
    cafes: { type: Array, required: true },
});

const { filters, selectedCafe, hoveredCafe, selectCafe } = useCafeMap();

const mobileOpen = ref(false);
</script>
