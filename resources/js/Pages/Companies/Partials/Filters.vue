<template>
<div>
    <!-- Mobile filter dialog -->
    <TransitionRoot as="template" :show="open">
        <Dialog class="relative z-[9999] sm:hidden" @close="open = false">
            <TransitionChild as="template" enter="transition-opacity ease-linear duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="transition-opacity ease-linear duration-300" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black bg-opacity-25" />
            </TransitionChild>

            <div class="fixed inset-0 z-40 flex">
                <TransitionChild as="template" enter="transition ease-in-out duration-300 transform" enter-from="translate-x-full" enter-to="translate-x-0" leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0" leave-to="translate-x-full">
                    <DialogPanel class="relative ml-auto flex h-full w-full max-w-xs flex-col overflow-y-auto bg-white py-4 pb-6 shadow-xl">
                        <div class="flex items-center justify-between px-4">
                            <h2 class="text-lg font-medium text-gray-900">Filters</h2>
                            <button type="button" class="-mr-2 flex h-10 w-10 items-center justify-center rounded-md bg-white p-2 text-gray-400 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500" @click="open = false">
                                <span class="sr-only">Close menu</span>
                                <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                            </button>
                        </div>

                        <!-- Filters -->
                        <div class="mt-4">
                            <Disclosure as="div" class="border-t border-gray-200 px-4 py-6" v-slot="{ open }">
                                <h3 class="-mx-2 -my-3 flow-root">
                                    <DisclosureButton class="flex w-full items-center justify-between bg-white px-2 py-3 text-sm text-gray-400">
                                        <span class="font-medium text-gray-900">Company Type</span>
                                        <span class="ml-6 flex items-center">
                                            <ChevronDownIcon :class="[open ? '-rotate-180' : 'rotate-0', 'h-5 w-5 transform']" aria-hidden="true" />
                                        </span>
                                    </DisclosureButton>
                                </h3>
                                <DisclosurePanel class="pt-6">
                                    <div class="space-y-6">
                                        <div v-for="(option, optionIdx) in types" :key="option.value" class="flex items-center">
                                            <input 
                                                v-model="form.types" 
                                                :id="`filter-mobile-type-${optionIdx}`" 
                                                :name="`type[]`" 
                                                :value="option.value" 
                                                type="checkbox" 
                                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" 
                                            />
                                            <label :for="`filter-mobile-type-${optionIdx}`" class="ml-3 text-sm text-gray-500">{{ option.label }}</label>
                                        </div>
                                    </div>
                                </DisclosurePanel>
                            </Disclosure>
                        </div>
                    </DialogPanel>
                </TransitionChild>
            </div>
        </Dialog>
    </TransitionRoot>

    <div class="mx-auto max-w-3xl px-4 text-center sm:px-6 lg:max-w-7xl lg:px-8">
        <section aria-labelledby="filter-heading" class="flex items-center justify-between py-6">
            <h2 id="filter-heading" class="text-3xl font-semibold">Company filters</h2>

            <div class="flex items-center justify-end space-x-8">
                <Menu as="div" class="relative inline-block text-left">
                    <div>
                        <MenuButton class="group inline-flex justify-center text-sm font-medium text-gray-700 hover:text-gray-900">
                            Sort
                            <ChevronDownIcon class="-mr-1 ml-1 h-5 w-5 flex-shrink-0 text-gray-400 group-hover:text-gray-500" aria-hidden="true" />
                        </MenuButton>
                    </div>

                    <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                        <MenuItems class="absolute left-0 z-10 mt-2 w-40 origin-top-left rounded-md bg-white shadow-2xl ring-1 ring-black ring-opacity-5 focus:outline-none">
                            <div class="py-1">
                                <MenuItem v-for="option in sortOptions" :key="option" v-slot="{ active }">
                                    <button 
                                        @click="form.sort = option.value"
                                        :class="[form.sort == option.value ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm font-medium text-gray-900 w-full text-left']">
                                        {{ option.name }}
                                    </button>
                                </MenuItem>
                            </div>
                        </MenuItems>
                    </transition>
                </Menu>

                <button type="button" class="inline-block text-sm font-medium text-gray-700 hover:text-gray-900 sm:hidden" @click="open = true">Filters</button>

                <PopoverGroup class="hidden sm:flex sm:items-baseline sm:space-x-8">
                    <Popover as="div" :id="`desktop-menu-type`" class="relative inline-block text-left">
                        <div>
                            <PopoverButton class="group inline-flex items-center justify-center text-sm font-medium text-gray-700 hover:text-gray-900">
                                <span>Company Type</span>
                                <ChevronDownIcon class="-mr-1 ml-1 h-5 w-5 flex-shrink-0 text-gray-400 group-hover:text-gray-500" aria-hidden="true" />
                            </PopoverButton>
                        </div>

                        <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                            <PopoverPanel class="absolute right-0 z-10 mt-2 origin-top-right rounded-md bg-white p-4 shadow-2xl ring-1 ring-black ring-opacity-5 focus:outline-none">
                                <div class="space-y-4">
                                    <div v-for="(option, optionIdx) in types" :key="option.value" class="flex items-center">
                                        <input 
                                            v-model="form.types" 
                                            :id="`filter-type-${optionIdx}`" 
                                            :name="`type[]`" 
                                            :value="option.value" 
                                            type="checkbox" 
                                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" 
                                        />
                                        <label :for="`filter-type-${optionIdx}`" class="ml-3 whitespace-nowrap pr-6 text-sm font-medium text-gray-900">{{ option.label }}</label>
                                    </div>
                                </div>
                            </PopoverPanel>
                        </transition>
                    </Popover>
                </PopoverGroup>

                <TextInput v-model="form.search" placeholder="Search" />
            </div>
        </section>
    </div>
</div>
</template>

<script setup>
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue'
import {
    Dialog,
    DialogPanel,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    Popover,
    PopoverButton,
    PopoverGroup,
    PopoverPanel,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import { ChevronDownIcon } from '@heroicons/vue/20/solid'
import { useCompanies } from '@/Composables/useCompanies'

const { form } = useCompanies()

const sortOptions = [
    { name: 'Most Popular', value: 'popular' },
    { name: 'Best Rating', value: 'rating' },
    { name: 'Newest', value: 'newest' },
]

const types = [
    { value: 'subscription', label: 'Offers subscription' },
    { value: 'has-cafe', label: 'Has Cafe' },
    { value: 'roaster', label: 'Roasts own coffee' },
];

const open = ref(false)
</script>