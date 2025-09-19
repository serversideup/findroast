<template>
    <div>
        <!-- Company Filter -->
        <Disclosure as="div" 
            class="border-b border-gray-200 py-6" 
            v-slot="{ open }"
            :default-open="true">
                <h3 class="-my-3 flow-root">
                    <DisclosureButton class="flex w-full items-center justify-between bg-white py-3 text-sm text-gray-400 hover:text-gray-500">
                        <span class="font-medium text-gray-900">Company</span>
                        <span class="ml-6 flex items-center">
                            <PlusIcon v-if="!open" class="h-5 w-5" aria-hidden="true" />
                            <MinusIcon v-else class="h-5 w-5" aria-hidden="true" />
                        </span>
                    </DisclosureButton>
                </h3>
                <DisclosurePanel class="pt-6">
                    <div class="space-y-4">
                        <div v-for="(option, optionIdx) in availableCompanies" 
                            :key="option.value" class="flex items-center">
                                <input 
                                    :id="`filter-company-${optionIdx}`" 
                                    :value="option.value" 
                                    type="checkbox" 
                                    v-model="form.companies"
                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                <label :for="`filter-company-${optionIdx}`" class="ml-3 text-sm text-gray-600">{{ option.label }}</label>
                        </div>
                    </div>
                </DisclosurePanel>
        </Disclosure>

        <!-- Process Filter -->
        <Disclosure as="div" 
            class="border-b border-gray-200 py-6" 
            v-slot="{ open }"
            :default-open="true">
                <h3 class="-my-3 flow-root">
                    <DisclosureButton class="flex w-full items-center justify-between bg-white py-3 text-sm text-gray-400 hover:text-gray-500">
                        <span class="font-medium text-gray-900">Process</span>
                        <span class="ml-6 flex items-center">
                            <PlusIcon v-if="!open" class="h-5 w-5" aria-hidden="true" />
                            <MinusIcon v-else class="h-5 w-5" aria-hidden="true" />
                        </span>
                    </DisclosureButton>
                </h3>
                <DisclosurePanel class="pt-6">
                    <div class="space-y-4">
                        <div v-for="(option, optionIdx) in availableProcesses" 
                            :key="option.value" class="flex items-center">
                                <input 
                                    :id="`filter-process-${optionIdx}`" 
                                    :value="option.value" 
                                    type="checkbox" 
                                    v-model="form.processes"
                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                <label :for="`filter-process-${optionIdx}`" class="ml-3 text-sm text-gray-600">{{ option.label }}</label>
                        </div>
                    </div>
                </DisclosurePanel>
        </Disclosure>

        <!-- Flavor Note Filter -->
        <Disclosure as="div" 
            class="border-b border-gray-200 py-6" 
            v-slot="{ open }">
                <h3 class="-my-3 flow-root">
                    <DisclosureButton class="flex w-full items-center justify-between bg-white py-3 text-sm text-gray-400 hover:text-gray-500">
                        <span class="font-medium text-gray-900">Flavor Notes</span>
                        <span class="ml-6 flex items-center">
                            <PlusIcon v-if="!open" class="h-5 w-5" aria-hidden="true" />
                            <MinusIcon v-else class="h-5 w-5" aria-hidden="true" />
                        </span>
                    </DisclosureButton>
                </h3>
                <DisclosurePanel class="pt-6">
                    <div class="space-y-4">
                        <div v-for="(option, optionIdx) in availableFlavorNotes" 
                            :key="option.value" class="flex items-center">
                                <input 
                                    :id="`filter-flavor-note-${optionIdx}`" 
                                    :value="option.value" 
                                    type="checkbox" 
                                    :checked="option.checked" 
                                    v-model="form.flavor_notes"
                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                <label :for="`filter-flavor-note-${optionIdx}`" class="ml-3 text-sm text-gray-600">{{ option.label }}</label>
                        </div>
                    </div>
                </DisclosurePanel>
        </Disclosure>

        <!-- Variety Filter -->
        <Disclosure as="div" 
            class="border-b border-gray-200 py-6" 
            v-slot="{ open }">
                <h3 class="-my-3 flow-root">
                    <DisclosureButton class="flex w-full items-center justify-between bg-white py-3 text-sm text-gray-400 hover:text-gray-500">
                        <span class="font-medium text-gray-900">Varieties</span>
                        <span class="ml-6 flex items-center">
                            <PlusIcon v-if="!open" class="h-5 w-5" aria-hidden="true" />
                            <MinusIcon v-else class="h-5 w-5" aria-hidden="true" />
                        </span>
                    </DisclosureButton>
                </h3>
                <DisclosurePanel class="pt-6">
                    <div class="space-y-4">
                        <div v-for="(option, optionIdx) in availableVarieties" 
                            :key="option.value" class="flex items-center">
                                <input 
                                    :id="`filter-variety-${optionIdx}`" 
                                    :value="option.value" 
                                    type="checkbox" 
                                    :checked="option.checked" 
                                    v-model="form.varieties"
                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                <label :for="`filter-variety-${optionIdx}`" class="ml-3 text-sm text-gray-600">{{ option.label }}</label>
                        </div>
                    </div>
                </DisclosurePanel>
        </Disclosure>
        
        <!-- Country Filter -->
        <Disclosure as="div" 
            class="border-b border-gray-200 py-6" 
            v-slot="{ open }">
                <h3 class="-my-3 flow-root">
                    <DisclosureButton class="flex w-full items-center justify-between bg-white py-3 text-sm text-gray-400 hover:text-gray-500">
                        <span class="font-medium text-gray-900">Origin</span>
                        <span class="ml-6 flex items-center">
                            <PlusIcon v-if="!open" class="h-5 w-5" aria-hidden="true" />
                            <MinusIcon v-else class="h-5 w-5" aria-hidden="true" />
                        </span>
                    </DisclosureButton>
                </h3>
                <DisclosurePanel class="pt-6">
                    <div class="space-y-4">
                        <div v-for="(option, optionIdx) in availableCountries" 
                            :key="option.value" class="flex items-center">
                                <input 
                                    :id="`filter-country-${optionIdx}`" 
                                    :value="option.value" 
                                    type="checkbox" 
                                    :checked="option.checked" 
                                    v-model="form.countries"
                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                <label :for="`filter-country-${optionIdx}`" class="ml-3 text-sm text-gray-600">{{ findFlag(option.label) }} {{ option.label }}</label>
                        </div>
                    </div>
                </DisclosurePanel>
        </Disclosure>
    </div>
</template>

<script setup>
import { computed, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useOfferings } from '@/Composables/useOfferings';
import { useCountries } from '@/Composables/useCountries';

import {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
} from '@headlessui/vue';

import {
    PlusIcon,
    MinusIcon,
} from '@heroicons/vue/24/outline';

const { 
    form
} = useOfferings();

const {
    findFlag
} = useCountries();

/**
 * Define the filters
 */
const availableProcesses = computed(() => usePage().props.processes.map(process => ({ 
    value: process.id, 
    label: process.name + ' (' + process.roasts_count + ')'
})));

const availableCountries = computed(() => usePage().props.countries.map(country => ({ 
    value: country.id, 
    label: country.name + ' (' + country.roasts_count + ')'
})));

const availableFlavorNotes = computed(() => usePage().props.flavorNotes.map(flavorNote => ({ 
    value: flavorNote.id, 
    label: flavorNote.name + ' (' + flavorNote.roasts_count + ')'
})));

const availableVarieties = computed(() => usePage().props.varieties.map(variety => ({ 
    value: variety.id, 
    label: variety.name + ' (' + variety.roasts_count + ')'
})));

const availableCompanies = computed(() => usePage().props.companies.map(company => ({ 
    value: company.id, 
    label: company.name + ' (' + company.roasts_count + ')'
})));
</script>