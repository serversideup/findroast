<template>
    <Head title="Varieties" />

    <AdminHeader
        :title="'Varieties'"
        :count="varieties.length" />

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
                        v-for="variety in varieties"
                        :key="variety.id"
                        class="hover:bg-amber-50/50 cursor-pointer transition-colors"
                        @click="editVariety(variety)">
                        <td class="px-4 py-2.5 text-sm font-medium text-stone-900">
                            {{ variety.name }}
                        </td>
                        <td class="px-4 py-2.5 text-sm text-stone-600">
                            {{ variety.slug }}
                        </td>
                        <td class="px-4 py-2.5 text-right">
                            <button
                                @click.stop="editVariety(variety)"
                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-amber-700 hover:text-amber-800 hover:bg-amber-50 rounded transition-colors">
                                Edit
                            </button>
                        </td>
                    </tr>
                    <tr v-if="varieties.length === 0">
                        <td colspan="3" class="px-4 py-8 text-center text-sm text-stone-500">
                            No varieties found
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <EditVarietyDrawer />
</template>

<script setup>
import AdminHeader from '../Partials/AdminHeader.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useEventBus } from '@vueuse/core';
import EditVarietyDrawer from './Partials/EditVarietyDrawer.vue';

defineOptions({
    layout: AdminLayout
});

const varieties = computed(() => usePage().props.varieties);

const promptBus = useEventBus('roast-prompt-event-bus');

const editVariety = (variety) => {
    promptBus.emit('prompt-edit-variety', variety);
}
</script>
