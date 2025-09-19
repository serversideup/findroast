<template>
    <Head title="Current Offerings" />

    <div class="bg-white">
        <div>
            <!-- Mobile filter dialog -->
            <MobileFilters />

            <div class="w-full relative h-96 bg-cover bg-center bg-no-repeat lg:h-80 bg-[url(/stock/coffee-beans.jpg)]">
                <div class="absolute inset-0 bg-black bg-opacity-50" />
                <div class="absolute inset-0 flex items-center justify-center">
                    <h1 class="text-4xl font-bold tracking-tight text-white">Explore Current Offerings</h1>
                </div>
                <a target="_blank" href="https://www.pexels.com/photo/shallow-focus-photo-of-coffee-beans-894695/" class="text-white text-xs underline font-medium absolute bottom-2 right-2">📷: Juan Pablo Serrano</a>
            </div>
            
            <main class="px-4 sm:px-6 lg:px-8">
                <div class="flex items-baseline justify-between border-b border-gray-200 py-6">
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900">Offerings</h1>

                    <div class="flex items-center">
                        <Menu as="div" class="relative inline-block text-left">
                            <div>
                                <MenuButton class="group inline-flex justify-center text-sm font-medium text-gray-700 hover:text-gray-900">
                                    Sort
                                    <ChevronDownIcon class="-mr-1 ml-1 h-5 w-5 flex-shrink-0 text-gray-400 group-hover:text-gray-500" aria-hidden="true" />
                                </MenuButton>
                            </div>

                            <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                                <MenuItems class="absolute right-0 z-10 mt-2 w-40 origin-top-right rounded-md bg-white shadow-2xl ring-1 ring-black ring-opacity-5 focus:outline-none">
                                    <div class="py-1">
                                        <MenuItem v-for="option in sortOptions" :key="option.name" v-slot="{ active }">
                                            <a :href="option.href" :class="[option.current ? 'font-medium text-gray-900' : 'text-gray-500', active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm']">{{ option.name }}</a>
                                        </MenuItem>
                                    </div>
                                </MenuItems>
                            </transition>
                        </Menu>

                        <button type="button" class="-m-2 ml-5 p-2 text-gray-400 hover:text-gray-500 sm:ml-7">
                            <span class="sr-only">View grid</span>
                            <Squares2X2Icon class="h-5 w-5" aria-hidden="true" />
                        </button>

                        <button type="button" class="-m-2 ml-4 p-2 text-gray-400 hover:text-gray-500 sm:ml-6 lg:hidden" @click="mobileFiltersOpen = true">
                            <span class="sr-only">Filters</span>
                            <FunnelIcon class="h-5 w-5" aria-hidden="true" />
                        </button>
                    </div>
                </div>

                <section aria-labelledby="offerings-heading" class="pt-6">
                    <h2 id="offerings-heading" class="sr-only">Offerings</h2>

                    <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-4">
                        <!-- Filters -->
                        <form class="hidden lg:block overflow-y-auto h-[calc(100vh-177px)] px-1">
                            <Filters />
                        </form>

                        <!-- Roasts -->
                        <div class="lg:col-span-3 overflow-y-auto h-[calc(100vh-177px)] px-1">
                            <Roasts />
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>

</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
    layout: AppLayout
};
</script>

<script setup>
import Filters from './Partials/Filters.vue';
import MobileFilters from './Partials/MobileFilters.vue';
import Roasts from './Partials/Roasts.vue';
import { Head } from '@inertiajs/vue3';

import {
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
} from '@headlessui/vue'

import { 
    ChevronDownIcon,
    FunnelIcon,
    Squares2X2Icon
} from '@heroicons/vue/24/outline'

const sortOptions = [
    { name: 'Most Popular', href: '#', current: true },
    { name: 'Best Rating', href: '#', current: false },
    { name: 'Newest', href: '#', current: false },
    { name: 'Price: Low to High', href: '#', current: false },
    { name: 'Price: High to Low', href: '#', current: false },
]
</script>