<template>
    <Head title="Processes" />

    <AdminHeader
        :title="'Processes'"
        :count="processes.total" />

    <!-- Active Processes -->
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
                        v-for="process in processes.data"
                        :key="process.id"
                        class="hover:bg-amber-50/50 transition-colors">
                        <td class="px-4 py-2.5 text-sm font-medium text-stone-900">
                            {{ process.name }}
                        </td>
                        <td class="px-4 py-2.5 text-sm text-stone-600">
                            {{ process.slug }}
                        </td>
                        <td class="px-4 py-2.5 text-right space-x-2">
                            <button
                                @click="migrateProcess(process)"
                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-blue-700 hover:text-blue-800 hover:bg-blue-50 rounded transition-colors">
                                Migrate
                            </button>
                            <button
                                @click="editProcess(process)"
                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-amber-700 hover:text-amber-800 hover:bg-amber-50 rounded transition-colors">
                                Edit
                            </button>
                        </td>
                    </tr>
                    <tr v-if="processes.data.length === 0">
                        <td colspan="3" class="px-4 py-8 text-center text-sm text-stone-500">
                            No processes found
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Pagination :data="processes" />
    </div>

    <!-- Migrated Processes -->
    <div v-if="migratedProcesses.length > 0" class="mt-8">
        <h3 class="px-4 py-3 text-sm font-semibold text-stone-700 bg-stone-50 border-t border-b border-stone-200">
            Migrated Processes ({{ migratedProcesses.length }})
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
                            v-for="process in migratedProcesses"
                            :key="process.id"
                            class="text-stone-500">
                            <td class="px-4 py-2.5 text-sm">
                                {{ process.name }}
                            </td>
                            <td class="px-4 py-2.5 text-sm">
                                {{ process.migrated_to?.name }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <EditProcessDrawer />
    <MigrateProcessDrawer />
</template>

<script setup>
import AdminHeader from '../Partials/AdminHeader.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useEventBus } from '@vueuse/core';
import EditProcessDrawer from './Partials/EditProcessDrawer.vue';
import MigrateProcessDrawer from './Partials/MigrateProcessDrawer.vue';
import Pagination from '@/Components/Admin/Pagination.vue';

defineOptions({
    layout: AdminLayout
});

const processes = computed(() => usePage().props.processes);
const migratedProcesses = computed(() => usePage().props.migratedProcesses);

const promptBus = useEventBus('roast-prompt-event-bus');

const editProcess = (process) => {
    promptBus.emit('prompt-edit-process', process);
}

const migrateProcess = (process) => {
    // Pass all processes except the one being migrated as potential targets
    const targetProcesses = processes.value.data.filter(p => p.id !== process.id);

    promptBus.emit('prompt-migrate-process', {
        process,
        targetProcesses
    });
}
</script>
