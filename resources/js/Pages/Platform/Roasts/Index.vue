<template>
    <Head title="Roasts" />

    <AdminHeader
        :title="'Roasts'"
        :count="roasts.total" />

    <div class="bg-white border-t border-stone-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-stone-200">
                <thead class="bg-stone-50">
                    <tr>
                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Image
                        </th>
                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Flavor Notes
                        </th>
                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Countries
                        </th>
                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Processes
                        </th>
                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Elevations
                        </th>
                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Varieties
                        </th>
                        <th scope="col" class="px-4 py-2.5 text-right text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-stone-100">
                    <tr
                        v-for="roast in roasts.data"
                        :key="roast.id"
                        @click="editRoast(roast)"
                        class="hover:bg-amber-50/50 cursor-pointer transition-colors group">
                        <td class="px-4 py-2.5 text-sm">
                            <div class="font-medium text-stone-900">{{ roast.name }}</div>
                            <div class="text-stone-500 text-xs mt-0.5">{{ roast.company.name }}</div>
                        </td>

                        <td class="px-4 py-2.5">
                            <img
                                v-if="roast.primary_image != null && roast.primary_image != ''"
                                :src="'/storage/'+roast.primary_image"
                                :alt="roast.name"
                                class="w-10 h-10 rounded object-cover border border-stone-200" />
                            <div v-else class="w-10 h-10 rounded bg-stone-100 flex items-center justify-center">
                                <span class="text-stone-400 text-xs">No image</span>
                            </div>
                        </td>

                        <td class="px-4 py-2.5 text-sm text-stone-600">
                            <div v-if="roast.flavor_notes.length > 0" class="flex flex-wrap gap-1">
                                <span
                                    v-for="flavorNote in roast.flavor_notes"
                                    :key="flavorNote.id"
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-amber-100 text-amber-800">
                                    {{ flavorNote.name }}
                                </span>
                            </div>
                            <span v-else class="text-stone-400 text-xs">—</span>
                        </td>

                        <td class="px-4 py-2.5 text-sm text-stone-600">
                            <div v-if="roast.countries.length > 0" class="space-y-0.5">
                                <div v-for="country in roast.countries" :key="country.id">
                                    {{ country.name }}
                                </div>
                            </div>
                            <span v-else class="text-stone-400 text-xs">—</span>
                        </td>

                        <td class="px-4 py-2.5 text-sm text-stone-600">
                            <div v-if="roast.processes.length > 0" class="space-y-0.5">
                                <div v-for="process in roast.processes" :key="process.id">
                                    {{ process.name }}
                                </div>
                            </div>
                            <span v-else class="text-stone-400 text-xs">—</span>
                        </td>

                        <td class="px-4 py-2.5 text-sm text-stone-600">
                            <div v-if="roast.elevations.length > 0" class="space-y-0.5">
                                <div v-for="elevation in roast.elevations" :key="elevation.id">
                                    {{ elevation.name }}
                                </div>
                            </div>
                            <span v-else class="text-stone-400 text-xs">—</span>
                        </td>

                        <td class="px-4 py-2.5 text-sm text-stone-600">
                            <div v-if="roast.varieties.length > 0" class="space-y-0.5">
                                <div v-for="variety in roast.varieties" :key="variety.id">
                                    {{ variety.name }}
                                </div>
                            </div>
                            <span v-else class="text-stone-400 text-xs">—</span>
                        </td>

                        <td class="px-4 py-2.5 text-right">
                            <button
                                @click.stop="deleteRoast(roast.id)"
                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-red-700 hover:text-red-800 hover:bg-red-50 rounded transition-colors">
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Pagination :data="roasts" />
    </div>

    <EditRoastDrawer />
</template>

<script setup>
import AdminHeader from '../Partials/AdminHeader.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import EditRoastDrawer from './Partials/EditRoastDrawer.vue';
import Pagination from '@/Components/Admin/Pagination.vue';
import { computed } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { useEventBus } from '@vueuse/core';

defineOptions({
    layout: AdminLayout
});

const roasts = computed(() => usePage().props.roasts);

const promptBus = useEventBus('roast-prompt-event-bus');
const notificationBus = useEventBus('roast-notification');

const editRoast = (roast) => {
    promptBus.emit('prompt-edit-roast', roast);
};

const deleteRoast = (id) => {
    router.delete(route('platform.roasts.delete', { roast: id }), {
        onSuccess: () => {
            notificationBus.emit('show', {
                title: 'Roast deleted'
            });
        }
    });
};
</script>