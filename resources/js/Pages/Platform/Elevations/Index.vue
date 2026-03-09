<template>
    <Head title="Elevations" />

    <AdminHeader
        :title="'Elevations'"
        :count="elevations.total" />

    <div class="bg-white border-t border-stone-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-stone-200">
                <thead class="bg-stone-50">
                    <tr>
                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col" class="px-4 py-2.5 text-right text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-stone-100">
                    <tr
                        v-for="elevation in elevations.data"
                        :key="elevation.id"
                        class="hover:bg-amber-50/50 cursor-pointer transition-colors"
                        @click="editElevation(elevation)">
                        <td class="px-4 py-2.5 text-sm font-medium text-stone-900">
                            {{ elevation.name }}
                        </td>
                        <td class="px-4 py-2.5 text-right">
                            <button
                                @click.stop="editElevation(elevation)"
                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-amber-700 hover:text-amber-800 hover:bg-amber-50 rounded transition-colors">
                                Edit
                            </button>
                        </td>
                    </tr>
                    <tr v-if="elevations.data.length === 0">
                        <td colspan="2" class="px-4 py-8 text-center text-sm text-stone-500">
                            No elevations found
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Pagination :data="elevations" />
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
import Pagination from '@/Components/Admin/Pagination.vue';

defineOptions({
    layout: AdminLayout
});

const elevations = computed(() => usePage().props.elevations);

const promptBus = useEventBus('roast-prompt-event-bus');

const editElevation = (elevation) => {
    promptBus.emit('prompt-edit-elevation', elevation);
}
</script>
