<template>
    <div>
        <!-- Desktop: Horizontal filter pills -->
        <div class="hidden lg:flex items-center gap-2 py-2 overflow-visible">
            <FilterPopover
                label="Origin"
                :options="availableCountries"
                v-model="form.countries"
                search-placeholder="Search origins..."
            >
                <template #option-label="{ option }">
                    {{ findFlag(option.name) }} {{ option.name }}
                </template>
            </FilterPopover>

            <FilterPopover
                label="Process"
                :options="availableProcesses"
                v-model="form.processes"
                search-placeholder="Search processes..."
            />

            <FilterPopover
                label="Flavor"
                :options="availableFlavorNotes"
                v-model="form.flavor_notes"
                search-placeholder="Search flavors..."
            />

            <FilterPopover
                label="Variety"
                :options="availableVarieties"
                v-model="form.varieties"
                search-placeholder="Search varieties..."
            />

            <FilterPopover
                label="Roaster"
                :options="availableCompanies"
                v-model="form.companies"
                search-placeholder="Search roasters..."
                align="right"
            />

            <!-- Sort (desktop) -->
            <div class="ml-auto flex-shrink-0">
                <Menu as="div" class="relative">
                    <MenuButton class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm rounded-lg border border-stone-200 bg-white text-stone-600 hover:border-stone-300 hover:text-stone-800 whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-amber-500/40">
                        <ArrowsUpDownIcon class="h-3.5 w-3.5" />
                        Sort
                    </MenuButton>

                    <transition
                        enter-active-class="transition ease-out duration-100"
                        enter-from-class="transform opacity-0 scale-95"
                        enter-to-class="transform opacity-100 scale-100"
                        leave-active-class="transition ease-in duration-75"
                        leave-from-class="transform opacity-100 scale-100"
                        leave-to-class="transform opacity-0 scale-95"
                    >
                        <MenuItems class="absolute right-0 z-50 mt-1.5 w-44 origin-top-right rounded-xl bg-white shadow-lg border border-stone-200 focus:outline-none py-1">
                            <MenuItem v-for="option in sortOptions" :key="option.value" v-slot="{ active }">
                                <button
                                    type="button"
                                    @click="setSort(option.value)"
                                    :class="[
                                        'block w-full text-left px-3 py-1.5 text-sm',
                                        option.current ? 'font-medium text-amber-800 bg-amber-50' : 'text-stone-600',
                                        active && !option.current ? 'bg-stone-50' : ''
                                    ]"
                                >
                                    {{ option.name }}
                                </button>
                            </MenuItem>
                        </MenuItems>
                    </transition>
                </Menu>
            </div>
        </div>

        <!-- Mobile: Compact bar with Filters button + Sort -->
        <div class="flex lg:hidden items-center gap-2 py-2">
            <button
                type="button"
                @click="mobileOpen = true"
                :class="[
                    'inline-flex items-center gap-1.5 px-3 py-1.5 text-sm rounded-lg border transition-colors whitespace-nowrap focus:outline-none',
                    totalActiveCount > 0
                        ? 'bg-amber-50 border-amber-300 text-amber-800 font-medium'
                        : 'bg-white border-stone-200 text-stone-600'
                ]"
            >
                <FunnelIcon class="h-3.5 w-3.5" />
                Filters
                <span
                    v-if="totalActiveCount > 0"
                    class="bg-amber-600 text-white text-[10px] font-bold rounded-full h-4 min-w-[16px] flex items-center justify-center px-1"
                >
                    {{ totalActiveCount }}
                </span>
            </button>

            <!-- Sort (mobile) -->
            <div class="ml-auto flex-shrink-0">
                <Menu as="div" class="relative">
                    <MenuButton class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm rounded-lg border border-stone-200 bg-white text-stone-600 whitespace-nowrap focus:outline-none">
                        <ArrowsUpDownIcon class="h-3.5 w-3.5" />
                        Sort
                    </MenuButton>

                    <transition
                        enter-active-class="transition ease-out duration-100"
                        enter-from-class="transform opacity-0 scale-95"
                        enter-to-class="transform opacity-100 scale-100"
                        leave-active-class="transition ease-in duration-75"
                        leave-from-class="transform opacity-100 scale-100"
                        leave-to-class="transform opacity-0 scale-95"
                    >
                        <MenuItems class="absolute right-0 z-50 mt-1.5 w-44 origin-top-right rounded-xl bg-white shadow-lg border border-stone-200 focus:outline-none py-1">
                            <MenuItem v-for="option in sortOptions" :key="option.value" v-slot="{ active }">
                                <button
                                    type="button"
                                    @click="setSort(option.value)"
                                    :class="[
                                        'block w-full text-left px-3 py-1.5 text-sm',
                                        option.current ? 'font-medium text-amber-800 bg-amber-50' : 'text-stone-600',
                                        active && !option.current ? 'bg-stone-50' : ''
                                    ]"
                                >
                                    {{ option.name }}
                                </button>
                            </MenuItem>
                        </MenuItems>
                    </transition>
                </Menu>
            </div>
        </div>

        <!-- Mobile Filter Bottom Sheet -->
        <TransitionRoot :show="mobileOpen" as="template">
            <Dialog class="relative z-50 lg:hidden" @close="mobileOpen = false">
                <!-- Backdrop -->
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

                <!-- Panel -->
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
                            <!-- Drag handle -->
                            <div class="flex justify-center pt-2 pb-1 flex-shrink-0">
                                <div class="w-10 h-1 bg-stone-300 rounded-full" />
                            </div>

                            <!-- Header -->
                            <div class="flex items-center justify-between px-4 pb-3 border-b border-stone-100 flex-shrink-0">
                                <h2 class="text-base font-semibold text-stone-900">Filters</h2>
                                <div class="flex items-center gap-3">
                                    <button
                                        v-if="totalActiveCount > 0"
                                        type="button"
                                        @click="clearAllFilters"
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

                            <!-- Filter Sections (scrollable) -->
                            <div class="overflow-y-auto flex-1 divide-y divide-stone-100">
                                <!-- Origin -->
                                <MobileFilterSection
                                    label="Origin"
                                    :options="availableCountries"
                                    v-model="form.countries"
                                    search-placeholder="Search origins..."
                                    :default-open="true"
                                >
                                    <template #option-label="{ option }">
                                        {{ findFlag(option.name) }} {{ option.name }}
                                    </template>
                                </MobileFilterSection>

                                <!-- Process -->
                                <MobileFilterSection
                                    label="Process"
                                    :options="availableProcesses"
                                    v-model="form.processes"
                                    search-placeholder="Search processes..."
                                />

                                <!-- Flavor Notes -->
                                <MobileFilterSection
                                    label="Flavor Notes"
                                    :options="availableFlavorNotes"
                                    v-model="form.flavor_notes"
                                    search-placeholder="Search flavors..."
                                />

                                <!-- Variety -->
                                <MobileFilterSection
                                    label="Variety"
                                    :options="availableVarieties"
                                    v-model="form.varieties"
                                    search-placeholder="Search varieties..."
                                />

                                <!-- Roaster -->
                                <MobileFilterSection
                                    label="Roaster"
                                    :options="availableCompanies"
                                    v-model="form.companies"
                                    search-placeholder="Search roasters..."
                                />
                            </div>

                            <!-- Sticky Footer -->
                            <div class="px-4 py-3 border-t border-stone-200 flex-shrink-0">
                                <button
                                    type="button"
                                    @click="mobileOpen = false"
                                    class="w-full py-2.5 bg-amber-700 hover:bg-amber-800 text-white text-sm font-medium rounded-lg transition-colors"
                                >
                                    Show {{ resultsTotal }} coffees
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
import { useOfferings } from '@/Composables/useOfferings';
import { useCountries } from '@/Composables/useCountries';
import FilterPopover from '@/Components/FilterPopover.vue';
import MobileFilterSection from '@/Components/MobileFilterSection.vue';

import {
    Dialog,
    DialogPanel,
    TransitionRoot,
    TransitionChild,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
} from '@headlessui/vue';

import {
    ArrowsUpDownIcon,
    FunnelIcon,
    XMarkIcon,
} from '@heroicons/vue/20/solid';

const { form } = useOfferings();
const { findFlag } = useCountries();

const mobileOpen = ref(false);

const availableProcesses = computed(() =>
    usePage().props.processes.map(p => ({ id: p.id, name: p.name, roasts_count: p.roasts_count }))
);

const availableCountries = computed(() =>
    usePage().props.countries.map(c => ({ id: c.id, name: c.name, roasts_count: c.roasts_count }))
);

const availableFlavorNotes = computed(() =>
    usePage().props.flavorNotes.map(f => ({ id: f.id, name: f.name, roasts_count: f.roasts_count }))
);

const availableVarieties = computed(() =>
    usePage().props.varieties.map(v => ({ id: v.id, name: v.name, roasts_count: v.roasts_count }))
);

const availableCompanies = computed(() =>
    usePage().props.companies.map(c => ({ id: c.id, name: c.name, roasts_count: c.roasts_count }))
);

const totalActiveCount = computed(() =>
    form.countries.length + form.processes.length + form.flavor_notes.length + form.varieties.length + form.companies.length
);

const resultsTotal = computed(() => usePage().props.roasts?.total ?? 0);

const clearAllFilters = () => {
    form.companies = [];
    form.processes = [];
    form.flavor_notes = [];
    form.varieties = [];
    form.countries = [];
};

const sortOptions = computed(() => [
    { name: 'Newest', value: 'newest', current: form.sort === 'newest' },
    { name: 'A-Z', value: 'a-z', current: form.sort === 'a-z' },
]);

const setSort = (value) => {
    form.sort = value;
};
</script>
