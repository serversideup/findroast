<template>
    <Head title="Elevations" />

    <AdminHeader 
        :title="'Elevations'"
        :breadcrumbs="[
            { label: 'Platform Settings', to: '/platform'},
            { label: 'Elevations', to: '#'}
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
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="elevation in elevations" :key="elevation.id"
                                class="hover:bg-gray-50">
                                <td class="pl-4 pr-3 py-3.5 whitespace-nowrap text-sm font-medium text-gray-900 sm:pl-0">
                                    {{ elevation.name }}
                                </td>
                                <td class="pl-3 pr-4 py-3.5 whitespace-nowrap text-right text-sm font-medium">
                                    <button @click="editElevation(elevation)" class="text-gray-600 hover:text-indigo-900">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="elevations.length === 0">
                                <td class="pl-4 pr-3 py-3.5 whitespace-nowrap text-sm font-medium text-gray-900 text-center" colspan="2">
                                    No elevations found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <EditElevationDrawer />
</template>

<script setup>
import AdminHeader from '../Partials/AdminHeader.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useEventBus } from '@vueuse/core';
import EditElevationDrawer from './Partials/EditElevationDrawer.vue';

defineOptions({
    layout: AdminLayout
});

const elevations = computed(() => usePage().props.elevations);

const promptBus = useEventBus('roast-prompt-event-bus');

const editElevation = (elevation) => {
    promptBus.emit('prompt-edit-elevation', elevation);
}
</script>