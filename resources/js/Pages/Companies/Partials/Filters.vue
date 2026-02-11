<template>
    <div>
        <!-- Desktop: Horizontal filter pills -->
        <div class="hidden lg:flex items-center gap-2 py-2 overflow-visible">
            <!-- Company Type Filter -->
            <div class="relative">
                <Popover class="relative">
                    <PopoverButton
                        :class="[
                            'inline-flex items-center gap-1.5 px-3 py-1.5 text-sm rounded-lg border transition-colors whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-amber-500/40',
                            form.types.length > 0
                                ? 'bg-amber-50 border-amber-300 text-amber-800 font-medium'
                                : 'bg-white border-stone-200 text-stone-600 hover:border-stone-300 hover:text-stone-800'
                        ]"
                    >
                        <BuildingStorefrontIcon class="h-3.5 w-3.5" />
                        Type
                        <span v-if="form.types.length > 0" class="bg-amber-600 text-white text-[10px] font-bold rounded-full h-4 min-w-[16px] flex items-center justify-center px-1">
                            {{ form.types.length }}
                        </span>
                        <ChevronDownIcon class="h-3.5 w-3.5" />
                    </PopoverButton>

                    <transition
                        enter-active-class="transition ease-out duration-100"
                        enter-from-class="transform opacity-0 scale-95"
                        enter-to-class="transform opacity-100 scale-100"
                        leave-active-class="transition ease-in duration-75"
                        leave-from-class="transform opacity-100 scale-100"
                        leave-to-class="transform opacity-0 scale-95"
                    >
                        <PopoverPanel class="absolute left-0 z-50 mt-1.5 w-64 origin-top-left rounded-xl bg-white shadow-lg border border-stone-200 p-3">
                            <div class="space-y-2">
                                <label
                                    v-for="option in types"
                                    :key="option.value"
                                    class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-stone-50 cursor-pointer"
                                >
                                    <input
                                        v-model="form.types"
                                        :value="option.value"
                                        type="checkbox"
                                        class="h-4 w-4 rounded border-stone-300 text-amber-600 focus:ring-amber-500/40"
                                    />
                                    <span class="text-sm text-stone-700">{{ option.label }}</span>
                                </label>
                            </div>
                        </PopoverPanel>
                    </transition>
                </Popover>
            </div>

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
                                    @click="form.sort = option.value"
                                    :class="[
                                        'block w-full text-left px-3 py-1.5 text-sm',
                                        form.sort === option.value ? 'font-medium text-amber-800 bg-amber-50' : 'text-stone-600',
                                        active && form.sort !== option.value ? 'bg-stone-50' : ''
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
                    form.types.length > 0
                        ? 'bg-amber-50 border-amber-300 text-amber-800 font-medium'
                        : 'bg-white border-stone-200 text-stone-600'
                ]"
            >
                <FunnelIcon class="h-3.5 w-3.5" />
                Filters
                <span
                    v-if="form.types.length > 0"
                    class="bg-amber-600 text-white text-[10px] font-bold rounded-full h-4 min-w-[16px] flex items-center justify-center px-1"
                >
                    {{ form.types.length }}
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
                                    @click="form.sort = option.value"
                                    :class="[
                                        'block w-full text-left px-3 py-1.5 text-sm',
                                        form.sort === option.value ? 'font-medium text-amber-800 bg-amber-50' : 'text-stone-600',
                                        active && form.sort !== option.value ? 'bg-stone-50' : ''
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
                                        v-if="form.types.length > 0"
                                        type="button"
                                        @click="form.types = []"
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
                            <div class="overflow-y-auto flex-1 p-4">
                                <div class="space-y-2">
                                    <p class="text-xs font-medium text-stone-700 uppercase tracking-wide px-2">Company Type</p>
                                    <label
                                        v-for="option in types"
                                        :key="option.value"
                                        class="flex items-center gap-2 px-2 py-2 rounded-lg hover:bg-stone-50 cursor-pointer"
                                    >
                                        <input
                                            v-model="form.types"
                                            :value="option.value"
                                            type="checkbox"
                                            class="h-4 w-4 rounded border-stone-300 text-amber-600 focus:ring-amber-500/40"
                                        />
                                        <span class="text-sm text-stone-700">{{ option.label }}</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Sticky Footer -->
                            <div class="px-4 py-3 border-t border-stone-200 flex-shrink-0">
                                <button
                                    type="button"
                                    @click="mobileOpen = false"
                                    class="w-full py-2.5 bg-amber-700 hover:bg-amber-800 text-white text-sm font-medium rounded-lg transition-colors"
                                >
                                    Show {{ resultsTotal }} companies
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
import { useCompanies } from '@/Composables/useCompanies';

import {
    Dialog,
    DialogPanel,
    TransitionRoot,
    TransitionChild,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    Popover,
    PopoverButton,
    PopoverPanel,
} from '@headlessui/vue';

import {
    ArrowsUpDownIcon,
    FunnelIcon,
    XMarkIcon,
    ChevronDownIcon,
} from '@heroicons/vue/20/solid';

import { BuildingStorefrontIcon } from '@heroicons/vue/24/outline';

const { form } = useCompanies();

const mobileOpen = ref(false);

const resultsTotal = computed(() => usePage().props.companies?.total ?? 0);

const sortOptions = [
    { name: 'Name A-Z', value: 'name_asc' },
    { name: 'Name Z-A', value: 'name_desc' },
    { name: 'Most Popular', value: 'popular' },
];

const types = [
    { value: 'roaster', label: 'Roasts own coffee' },
    { value: 'subscription', label: 'Offers subscription' },
    { value: 'has-cafe', label: 'Has Cafe' },
];
</script>