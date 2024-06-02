<template>
    <Head title="Companies" />

    <AdminHeader 
        :title="'Companies'"
        :breadcrumbs="[
            { label: 'Platform Settings', to: '/platform'},
            { label: 'Companies', to: '#'}
        ]">
        <template #actions>
            <TextInput v-model="search" @keypress="searchCompanies" placeholder="Search companies..." class="mr-3"/>

            <PrimaryLink href="/platform/companies/create">
                Add Company
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
                                    Location
                                </th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 text-left sm:pr-0">
                                    Website
                                </th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                    Roaster
                                </th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="company in companies.data" :key="company.id"
                                class="hover:bg-gray-50">
                                <td class="pl-4 pr-3 py-3.5 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <div class="flex items-center">
                                        <div class="flex items-center justify-center w-8 h-8 mr-2">
                                            <img v-if="company.logo != '' && company.logo != null" :src="company.logo" class=" rounded-full inline-block" alt="icon"/>
                                        </div>
                                        
                                        {{ company.name }}
                                    </div>
                                </td>
                                <td class="capitalize pr-3 py-3.5 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ company.status }}
                                </td>
                                <td class="pl-4 pr-3 py-3.5 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ company.city }} {{ company.country == 'US' ? company.state : '' }}{{ company.country == 'AU' ? company.territory : '' }}{{ company.country == 'CA' ? company.province : '' }}
                                </td>
                                <td class="pl-4 pr-3 py-3.5 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ company.website }}
                                </td>
                                <td class="pl-4 pr-3 py-3.5 whitespace-nowrap text-sm font-medium text-gray-900 text-center">
                                    {{ company.roaster == 1 ? 'Yes' : 'No' }}
                                </td>
                                <td class="pl-3 pr-4 py-3.5 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <Link :href="`/platform/companies/${company.id}`" class="text-gray-600 hover:text-indigo-900">
                                        View
                                    </Link>
                                </td>
                            </tr>
                            <tr v-if="companies.data.length === 0">
                                <td class="pl-4 pr-3 py-3.5 whitespace-nowrap text-sm font-medium text-gray-900 text-center" colspan="5">
                                    No companies found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <nav class="flex items-center justify-between border-t border-gray-200 bg-white px-4 sm:px-0 py-3" aria-label="Pagination">
                        <div class="hidden sm:block">
                            <p class="text-sm text-gray-700">
                                Showing
                                {{ ' ' }}
                                <span class="font-medium">{{ companies.from }}</span>
                                {{ ' ' }}
                                to
                                {{ ' ' }}
                                <span class="font-medium">{{ companies.to }}</span>
                                {{ ' ' }}
                                of
                                {{ ' ' }}
                                <span class="font-medium">{{ companies.total }}</span>
                                {{ ' ' }}
                                results
                            </p>
                        </div>
                        <div class="flex flex-1 justify-between sm:justify-end">
                            <Link :href="companies.prev_page_url" class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0">Previous</Link>
                            <Link :href="companies.next_page_url" class="relative ml-3 inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0">Next</Link>
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
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useDebounceFn } from '@vueuse/core'

const companies = computed(() => usePage().props.companies);

const search = ref('');

const searchCompanies = useDebounceFn(() => {
    router.reload({
        only: ['companies'],
        data: {
            search: search.value,
            page: 1
        }
    });
}, 500)

</script>