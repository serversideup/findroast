<template>
    <Head title="Roasts" />

    <AdminHeader 
        :title="'Roasts'"
        :breadcrumbs="[
            { label: 'Platform Settings', to: '/platform'},
            { label: 'Roasts', to: '#'}
        ]">
            
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
                                        URL
                                </th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                    Company
                                </th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 text-left sm:pr-0">
                                    Flavor Notes
                                </th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 text-left sm:pr-0">
                                    Countries
                                </th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                    Processes
                                </th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                    Elevations
                                </th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                    Varieties
                                </th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="roast in roasts" :key="roast.id"
                                class="hover:bg-gray-50">
                                <td class="pl-4 pr-3 py-3.5 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ roast.name }}
                                </td>
                                <td class="pr-3 py-3.5 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <a :href="roast.url" target="_blank">{{ roast.url }}</a>
                                </td>
                                <td class="pl-4 pr-3 py-3.5 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ roast.company.name }}
                                </td>
                                <td class="pl-4 pr-3 py-3.5 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <ul>
                                        <li v-for="flavorNote in roast.flavor_notes" :key="flavorNote.id">{{ flavorNote.name }}</li>
                                    </ul>
                                </td>
                                <td class="pl-4 pr-3 py-3.5 whitespace-nowrap text-sm font-medium text-gray-900 text-center">
                                    <ul>
                                        <li v-for="country in roast.countries" :key="country.id">{{ country.name }}</li>
                                    </ul>
                                </td>
                                <td class="pl-4 pr-3 py-3.5 whitespace-nowrap text-sm font-medium text-gray-900 text-center">
                                    <ul>
                                        <li v-for="process in roast.processes" :key="process.id">{{ process.name }}</li>
                                    </ul>
                                </td>
                                <td class="pl-4 pr-3 py-3.5 whitespace-nowrap text-sm font-medium text-gray-900 text-center">
                                    <ul>
                                        <li v-for="elevation in roast.elevations" :key="elevation.id">{{ elevation.name }}</li>
                                    </ul>
                                </td>
                                <td class="pl-4 pr-3 py-3.5 whitespace-nowrap text-sm font-medium text-gray-900 text-center">
                                    <ul>
                                        <li v-for="variety in roast.varieties" :key="variety.id">{{ variety.name }}</li>
                                    </ul>
                                </td>
                                <td class="pl-3 pr-4 py-3.5 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <!-- <Link :href="`/platform/companies/${company.id}`" class="text-gray-600 hover:text-indigo-900">
                                        View
                                    </Link> -->
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
import { computed, onMounted, ref } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';

const roasts = computed(() => usePage().props.roasts);

const createdAt = ref(new Date().toISOString().split('T')[0]);

onMounted(() => {
    loadRoasts();
});

const loadRoasts = () => {
    router.reload({
        only: ['roasts'],
        data: {
            created_at: createdAt.value
        }
    });
};
</script>