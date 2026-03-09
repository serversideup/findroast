<template>
    <Head title="Amenities" />

    <AdminHeader
        :title="'Amenities'"
        :count="amenities.total">
        <template #actions>
            <button
                @click="promptCreateAmenity()"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-amber-700 hover:bg-amber-800 rounded-lg transition-colors">
                Create Amenity
            </button>
        </template>
    </AdminHeader>

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
                        v-for="amenity in amenities.data"
                        :key="amenity.id"
                        class="hover:bg-amber-50/50 cursor-pointer transition-colors"
                        @click="promptEditAmenity(amenity)">
                        <td class="px-4 py-2.5 text-sm font-medium text-stone-900">
                            <div class="flex items-center gap-3">
                                <img
                                    v-if="amenity.icon"
                                    :src="amenity.icon"
                                    class="w-8 h-8 rounded object-cover border border-stone-200"
                                    alt="icon"/>
                                <div v-else class="w-8 h-8 rounded bg-stone-100 flex items-center justify-center">
                                    <span class="text-stone-400 text-xs">N/A</span>
                                </div>
                                <span>{{ amenity.name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-2.5 text-right">
                            <button
                                @click.stop="promptEditAmenity(amenity)"
                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-amber-700 hover:text-amber-800 hover:bg-amber-50 rounded transition-colors">
                                Edit
                            </button>
                        </td>
                    </tr>
                    <tr v-if="amenities.data.length === 0">
                        <td colspan="2" class="px-4 py-8 text-center text-sm text-stone-500">
                            No amenities found
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Pagination :data="amenities" />
    </div>

    <CreateAmenityDrawer />
    <EditAmenityDrawer />
</template>

<script setup>
import AdminHeader from '../Partials/AdminHeader.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import CreateAmenityDrawer from './Partials/CreateAmenityDrawer.vue';
import EditAmenityDrawer from './Partials/EditAmenityDrawer.vue';
import Pagination from '@/Components/Admin/Pagination.vue';
import { useEventBus } from '@vueuse/core';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineOptions({
    layout: AdminLayout
});

const amenities = computed(() => usePage().props.amenities);

const promptBus = useEventBus('roast-prompt-event-bus');

const promptCreateAmenity = () => {
    promptBus.emit('prompt-create-amenity');
}

const promptEditAmenity = (amenity) => {
    promptBus.emit('prompt-edit-amenity', amenity);
}
</script>
