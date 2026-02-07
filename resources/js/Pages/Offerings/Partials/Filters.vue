<template>
    <div class="flex items-center gap-2 py-2 overflow-visible no-scrollbar">
        <!-- Origin -->
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

        <!-- Process -->
        <FilterPopover
            label="Process"
            :options="availableProcesses"
            v-model="form.processes"
            search-placeholder="Search processes..."
        />

        <!-- Flavor Notes -->
        <FilterPopover
            label="Flavor"
            :options="availableFlavorNotes"
            v-model="form.flavor_notes"
            search-placeholder="Search flavors..."
        />

        <!-- Variety -->
        <FilterPopover
            label="Variety"
            :options="availableVarieties"
            v-model="form.varieties"
            search-placeholder="Search varieties..."
        />

        <!-- Company -->
        <FilterPopover
            label="Roaster"
            :options="availableCompanies"
            v-model="form.companies"
            search-placeholder="Search roasters..."
            align="right"
        />

        <!-- Sort -->
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
                        <MenuItem v-for="option in sortOptions" :key="option.name" v-slot="{ active }">
                            <button
                                type="button"
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
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useOfferings } from '@/Composables/useOfferings';
import { useCountries } from '@/Composables/useCountries';
import FilterPopover from '@/Components/FilterPopover.vue';

import {
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
} from '@headlessui/vue';

import { ArrowsUpDownIcon } from '@heroicons/vue/20/solid';

const { form } = useOfferings();
const { findFlag } = useCountries();

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

const sortOptions = [
    { name: 'Newest', href: '#', current: true },
    { name: 'Price: Low to High', href: '#', current: false },
    { name: 'Price: High to Low', href: '#', current: false },
    { name: 'Name A-Z', href: '#', current: false },
];
</script>
