<template>
    <Head title="Countries" />

    <AdminHeader
        :title="'Countries'"
        :count="countries.length" />

    <div class="bg-white border-t border-stone-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-stone-200">
                <thead class="bg-stone-50">
                    <tr>
                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Slug
                        </th>
                        <th scope="col" class="px-4 py-2.5 text-right text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-stone-100">
                    <tr
                        v-for="country in countries"
                        :key="country.id"
                        class="hover:bg-amber-50/50 cursor-pointer transition-colors"
                        @click="editCountry(country)">
                        <td class="px-4 py-2.5 text-sm font-medium text-stone-900">
                            {{ country.name }}
                        </td>
                        <td class="px-4 py-2.5 text-sm text-stone-600">
                            {{ country.slug }}
                        </td>
                        <td class="px-4 py-2.5 text-right">
                            <button
                                @click.stop="editCountry(country)"
                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-amber-700 hover:text-amber-800 hover:bg-amber-50 rounded transition-colors">
                                Edit
                            </button>
                        </td>
                    </tr>
                    <tr v-if="countries.length === 0">
                        <td colspan="3" class="px-4 py-8 text-center text-sm text-stone-500">
                            No countries found
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <EditCountryDrawer />
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AdminHeader from '../Partials/AdminHeader.vue';
import EditCountryDrawer from './Partials/EditCountryDrawer.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useEventBus } from '@vueuse/core';

const promptBus = useEventBus('roast-prompt-event-bus');

defineOptions({
    layout: AdminLayout
});

const countries = computed(() => usePage().props.countries);

const editCountry = (country) => {
    promptBus.emit('prompt-edit-country', country);
}
</script>
