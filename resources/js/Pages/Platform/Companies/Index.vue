<template>
    <Head title="Companies" />

    <AdminHeader
        :title="'Companies'"
        :count="companies.total">
        <template #actions>
            <input
                v-model="search"
                @input="searchCompanies"
                type="text"
                placeholder="Search companies..."
                class="px-3 py-2 text-sm border border-stone-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 mr-2"/>

            <button
                @click="addCompany()"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-amber-700 hover:bg-amber-800 rounded-lg transition-colors">
                Add Company
            </button>
        </template>
    </AdminHeader>

    <div class="bg-white border-t border-stone-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-stone-200">
                <thead class="bg-stone-50">
                    <tr>
                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Company
                        </th>
                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Location
                        </th>
                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Website
                        </th>
                        <th scope="col" class="px-4 py-2.5 text-center text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Roaster
                        </th>
                        <th scope="col" class="px-4 py-2.5 text-right text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-stone-100">
                    <tr
                        v-for="company in companies.data"
                        :key="company.id"
                        class="hover:bg-amber-50/50 cursor-pointer transition-colors"
                        @click="editCompany(company)">
                        <td class="px-4 py-2.5 text-sm">
                            <div class="flex items-center gap-3">
                                <img
                                    v-if="company.logo"
                                    :src="company.logo"
                                    class="w-8 h-8 rounded object-cover border border-stone-200"
                                    alt="logo"/>
                                <div v-else class="w-8 h-8 rounded bg-stone-100 flex items-center justify-center">
                                    <span class="text-stone-400 text-xs">N/A</span>
                                </div>
                                <span class="font-medium text-stone-900">{{ company.name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-2.5 text-sm">
                            <span
                                :class="[
                                    'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium',
                                    company.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-stone-100 text-stone-800'
                                ]">
                                {{ company.status }}
                            </span>
                        </td>
                        <td class="px-4 py-2.5 text-sm text-stone-600">
                            <span v-if="company.city">
                                {{ company.city }}<span v-if="company.country === 'US' && company.state">, {{ company.state }}</span><span v-if="company.country === 'AU' && company.territory">, {{ company.territory }}</span><span v-if="company.country === 'CA' && company.province">, {{ company.province }}</span>
                            </span>
                            <span v-else class="text-stone-400 text-xs">—</span>
                        </td>
                        <td class="px-4 py-2.5 text-sm text-stone-600">
                            <a
                                v-if="company.website"
                                :href="company.website"
                                target="_blank"
                                @click.stop
                                class="text-amber-700 hover:text-amber-800 hover:underline truncate max-w-[200px] inline-block">
                                {{ company.website }}
                            </a>
                            <span v-else class="text-stone-400 text-xs">—</span>
                        </td>
                        <td class="px-4 py-2.5 text-sm text-center">
                            <span v-if="company.roaster" class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-amber-100 text-amber-800">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            <span v-else class="text-stone-400 text-xs">—</span>
                        </td>
                        <td class="px-4 py-2.5 text-right">
                            <button
                                @click.stop="editCompany(company)"
                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-amber-700 hover:text-amber-800 hover:bg-amber-50 rounded transition-colors">
                                Edit
                            </button>
                        </td>
                    </tr>
                    <tr v-if="companies.data.length === 0">
                        <td colspan="6" class="px-4 py-8 text-center text-sm text-stone-500">
                            No companies found
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div v-if="companies.data.length > 0" class="flex items-center justify-between border-t border-stone-200 px-4 py-3 bg-stone-50">
            <div class="text-sm text-stone-600">
                Showing
                <span class="font-medium text-stone-900">{{ companies.from }}</span>
                to
                <span class="font-medium text-stone-900">{{ companies.to }}</span>
                of
                <span class="font-medium text-stone-900">{{ companies.total }}</span>
                results
            </div>
        </div>
    </div>

    <EditCompanyDrawer />
    <AddCompanyDrawer />
    <PreviewScrapeModal />
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AdminHeader from '../Partials/AdminHeader.vue';
import EditCompanyDrawer from './Partials/EditCompanyDrawer.vue';
import AddCompanyDrawer from './Partials/AddCompanyDrawer.vue';
import PreviewScrapeModal from './Partials/PreviewScrapeModal.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useDebounceFn, useEventBus } from '@vueuse/core'

defineOptions({
    layout: AdminLayout
});

const companies = computed(() => usePage().props.companies);

const search = ref('');

const searchCompanies = useDebounceFn(() => {
    router.reload({
        only: ['companies'],
        data: {
            search: search.value,
            page: 1
        },
        replace: true
    });
}, 500)

const promptBus = useEventBus('roast-prompt-event-bus');

const addCompany = () => {
    promptBus.emit('prompt-add-company');
}

const editCompany = (company) => {
    promptBus.emit('prompt-edit-company', company);
};
</script>
