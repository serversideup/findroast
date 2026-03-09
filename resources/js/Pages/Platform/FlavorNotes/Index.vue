<template>
    <Head title="Flavor Notes" />

    <AdminHeader
        :title="'Flavor Notes'"
        :count="flavorNotes.total" />

    <!-- Active Flavor Notes -->
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
                        v-for="flavorNote in flavorNotes.data"
                        :key="flavorNote.id"
                        class="hover:bg-amber-50/50 transition-colors">
                        <td class="px-4 py-2.5 text-sm font-medium text-stone-900">
                            {{ flavorNote.name }}
                        </td>
                        <td class="px-4 py-2.5 text-sm text-stone-600">
                            {{ flavorNote.slug }}
                        </td>
                        <td class="px-4 py-2.5 text-right space-x-2">
                            <button
                                @click="migrateFlavorNote(flavorNote)"
                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-blue-700 hover:text-blue-800 hover:bg-blue-50 rounded transition-colors">
                                Migrate
                            </button>
                            <button
                                @click="editFlavorNote(flavorNote)"
                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-amber-700 hover:text-amber-800 hover:bg-amber-50 rounded transition-colors">
                                Edit
                            </button>
                        </td>
                    </tr>
                    <tr v-if="flavorNotes.data.length === 0">
                        <td colspan="3" class="px-4 py-8 text-center text-sm text-stone-500">
                            No flavor notes found
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Pagination :data="flavorNotes" />
    </div>

    <!-- Migrated Flavor Notes -->
    <div v-if="migratedFlavorNotes.length > 0" class="mt-8">
        <h3 class="px-4 py-3 text-sm font-semibold text-stone-700 bg-stone-50 border-t border-b border-stone-200">
            Migrated Flavor Notes ({{ migratedFlavorNotes.length }})
        </h3>
        <div class="bg-white border-b border-stone-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-stone-200">
                    <thead class="bg-stone-50">
                        <tr>
                            <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                                Original Name
                            </th>
                            <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                                Migrated To
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-stone-100">
                        <tr
                            v-for="flavorNote in migratedFlavorNotes"
                            :key="flavorNote.id"
                            class="text-stone-500">
                            <td class="px-4 py-2.5 text-sm">
                                {{ flavorNote.name }}
                            </td>
                            <td class="px-4 py-2.5 text-sm">
                                {{ flavorNote.migrated_to?.name }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <EditFlavorNoteDrawer />
    <MigrateFlavorNoteDrawer />
</template>

<script setup>
import AdminHeader from '../Partials/AdminHeader.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useEventBus } from '@vueuse/core';
import EditFlavorNoteDrawer from './Partials/EditFlavorNoteDrawer.vue';
import MigrateFlavorNoteDrawer from './Partials/MigrateFlavorNoteDrawer.vue';
import Pagination from '@/Components/Admin/Pagination.vue';

defineOptions({
    layout: AdminLayout
});

const promptBus = useEventBus('roast-prompt-event-bus');

const flavorNotes = computed(() => usePage().props.flavorNotes);
const migratedFlavorNotes = computed(() => usePage().props.migratedFlavorNotes);

const editFlavorNote = (flavorNote) => {
    promptBus.emit('prompt-edit-flavor-note', flavorNote);
}

const migrateFlavorNote = (flavorNote) => {
    // Pass all flavor notes except the one being migrated as potential targets
    const targetFlavorNotes = flavorNotes.value.data.filter(fn => fn.id !== flavorNote.id);

    promptBus.emit('prompt-migrate-flavor-note', {
        flavorNote,
        targetFlavorNotes
    });
}
</script>
