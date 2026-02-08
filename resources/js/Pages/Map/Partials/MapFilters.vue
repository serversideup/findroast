<template>
    <div>
        <!-- Desktop: Horizontal filter pills -->
        <div class="hidden lg:flex items-center gap-2 py-2 overflow-visible">
            <FilterPopover
                label="Brew Methods"
                :options="availableBrewMethods"
                v-model="filters.brewMethods"
                search-placeholder="Search brew methods..."
            />

            <FilterPopover
                label="Amenities"
                :options="availableAmenities"
                v-model="filters.amenities"
                search-placeholder="Search amenities..."
            />

            <FilterPopover
                label="Drinks"
                :options="availableDrinkOptions"
                v-model="filters.drinkOptions"
                search-placeholder="Search drinks..."
                align="right"
            />

            <button
                v-if="totalActiveFilters > 0"
                type="button"
                @click="clearFilters"
                class="text-xs text-stone-400 hover:text-stone-600 underline underline-offset-2"
            >
                Clear filters
            </button>
        </div>

        <!-- Mobile: Filter button -->
        <div class="flex lg:hidden items-center gap-2 py-2">
            <button
                type="button"
                @click="mobileOpen = true"
                :class="[
                    'inline-flex items-center gap-1.5 px-3 py-1.5 text-sm rounded-lg border transition-colors whitespace-nowrap focus:outline-none',
                    totalActiveFilters > 0
                        ? 'bg-amber-50 border-amber-300 text-amber-800 font-medium'
                        : 'bg-white border-stone-200 text-stone-600'
                ]"
            >
                <FunnelIcon class="h-3.5 w-3.5" />
                Filters
                <span
                    v-if="totalActiveFilters > 0"
                    class="bg-amber-600 text-white text-[10px] font-bold rounded-full h-4 min-w-[16px] flex items-center justify-center px-1"
                >
                    {{ totalActiveFilters }}
                </span>
            </button>
        </div>

        <!-- Mobile Filter Bottom Sheet -->
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
                        <DialogPanel class="w-full max-h-[85vh] bg-white rounded-t-2xl shadow-xl flex flex-col">
                            <div class="flex justify-center pt-2 pb-1 flex-shrink-0">
                                <div class="w-10 h-1 bg-stone-300 rounded-full" />
                            </div>

                            <div class="flex items-center justify-between px-4 pb-3 border-b border-stone-100 flex-shrink-0">
                                <h2 class="text-base font-semibold text-stone-900">Filters</h2>
                                <div class="flex items-center gap-3">
                                    <button
                                        v-if="totalActiveFilters > 0"
                                        type="button"
                                        @click="clearFilters"
                                        class="text-xs text-amber-700 font-medium hover:text-amber-800"
                                    >
                                        Clear all
                                    </button>
                                    <button
                                        type="button"
                                        @click="mobileOpen = false"
                                        class="p-1 text-stone-400 hover:text-stone-600 rounded"
                                    >
                                        <XMarkIcon class="h-5 w-5" />
                                    </button>
                                </div>
                            </div>

                            <div class="overflow-y-auto flex-1 divide-y divide-stone-100">
                                <MobileFilterSection
                                    label="Brew Methods"
                                    :options="availableBrewMethods"
                                    v-model="filters.brewMethods"
                                    search-placeholder="Search brew methods..."
                                    :default-open="true"
                                />

                                <MobileFilterSection
                                    label="Amenities"
                                    :options="availableAmenities"
                                    v-model="filters.amenities"
                                    search-placeholder="Search amenities..."
                                />

                                <MobileFilterSection
                                    label="Drink Options"
                                    :options="availableDrinkOptions"
                                    v-model="filters.drinkOptions"
                                    search-placeholder="Search drinks..."
                                />
                            </div>

                            <div class="px-4 py-3 border-t border-stone-200 flex-shrink-0">
                                <button
                                    type="button"
                                    @click="mobileOpen = false"
                                    class="w-full py-2.5 bg-amber-700 hover:bg-amber-800 text-white text-sm font-medium rounded-lg transition-colors"
                                >
                                    Show {{ cafeCount }} cafes
                                </button>
                            </div>
                        </DialogPanel>
                    </div>
                </TransitionChild>
            </Dialog>
        </TransitionRoot>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Dialog, DialogPanel, TransitionRoot, TransitionChild } from '@headlessui/vue';
import { FunnelIcon, XMarkIcon } from '@heroicons/vue/20/solid';
import { useCafeMap } from '@/Composables/useCafeMap';
import FilterPopover from '@/Components/FilterPopover.vue';
import MobileFilterSection from '@/Components/MobileFilterSection.vue';

const props = defineProps({
    cafeCount: { type: Number, default: 0 },
});

const { filters, totalActiveFilters, clearFilters } = useCafeMap();

const mobileOpen = ref(false);

const availableBrewMethods = computed(() =>
    (usePage().props.brewMethods || []).map(m => ({ id: m.id, name: m.name, roasts_count: '' }))
);

const availableAmenities = computed(() =>
    (usePage().props.amenities || []).map(a => ({ id: a.id, name: a.name, roasts_count: '' }))
);

const availableDrinkOptions = computed(() =>
    (usePage().props.drinkOptions || []).map(d => ({ id: d.id, name: d.name, roasts_count: '' }))
);
</script>
