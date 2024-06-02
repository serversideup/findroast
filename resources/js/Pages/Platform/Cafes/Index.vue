<template>
    <Head :title="company.name +' - Cafes'" />

    <AdminHeader 
        :title="'Cafes'"
        :breadcrumbs="[
            { label: 'Platform Settings', to: '/platform'},
            { label: 'Companies', to: '/platform/companies'},
            { label: company.name, to: '/platform/companies/'+company.id},
            { label: 'Cafes', to: '#'}
        ]">
        <template #actions>
            <PrimaryLink :href="'/platform/companies/'+company.id+'/cafes/create'">
                Add Cafe
            </PrimaryLink>
        </template>
    </AdminHeader>

    <div class="max-w-screen-xl mx-auto mt-8 lg:px-8">
        <div class="flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead>
                            <tr>
                                <th
                                    scope="col"
                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                        Name
                                </th>
                                <th
                                    scope="col"
                                    class="py-3.5 pl-3 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                        Status
                                </th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 text-left sm:pr-0">
                                    Address
                                </th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="cafe in cafes.data" :key="cafe.id"
                                class="hover:bg-gray-50">
                                <td class="pl-4 pr-3 py-3.5 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <div class="flex items-center">                                        
                                        {{ cafe.name }}
                                    </div>
                                </td>
                                <td class="capitalize pr-3 py-3.5 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ cafe.status }}
                                </td>
                                <td class="pl-4 pr-3 py-3.5 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ cafe.address }} {{ cafe.city }} {{ cafe.country == 'US' ? cafe.state : '' }}{{ cafe.country == 'AU' ? cafe.territory : '' }}{{ cafe.country == 'CA' ? cafe.province : '' }}
                                </td>
                                <td class="pl-3 pr-4 py-3.5 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <Link :href="`/platform/companies/${cafe.company_id}/cafes/${cafe.id}`" class="text-gray-600 hover:text-indigo-900">
                                        View
                                    </Link>
                                </td>
                            </tr>
                            <tr v-if="cafes.data.length === 0">
                                <td class="pl-4 pr-3 py-3.5 whitespace-nowrap text-sm font-medium text-gray-900 text-center" colspan="5">
                                    No cafes found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <nav class="flex items-center justify-between border-t border-gray-200 bg-white px-4 sm:px-0 py-3" aria-label="Pagination">
                        <div class="hidden sm:block">
                            <p class="text-sm text-gray-700">
                                Showing
                                {{ ' ' }}
                                <span class="font-medium">{{ cafes.from }}</span>
                                {{ ' ' }}
                                to
                                {{ ' ' }}
                                <span class="font-medium">{{ cafes.to }}</span>
                                {{ ' ' }}
                                of
                                {{ ' ' }}
                                <span class="font-medium">{{ cafes.total }}</span>
                                {{ ' ' }}
                                results
                            </p>
                        </div>
                        <div class="flex flex-1 justify-between sm:justify-end">
                            <Link :href="cafes.prev_page_url" class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0">Previous</Link>
                            <Link :href="cafes.next_page_url" class="relative ml-3 inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0">Next</Link>
                        </div>
                    </nav>
                </div>
            </div>
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
import AdminHeader from '../Partials/AdminHeader.vue';
import PrimaryLink from '@/Components/PrimaryLink.vue'
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const company = computed(() => usePage().props.company);
const cafes = computed(() => usePage().props.cafes);

</script>