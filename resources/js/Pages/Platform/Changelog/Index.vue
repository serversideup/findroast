<template>
    <Head title="Changelog" />

    <AdminHeader :title="'Changelog'" :count="entries.length">
        <template #actions>
            <Link
                href="/platform/changelog/create"
                class="inline-flex items-center gap-x-1.5 px-3 py-1.5 text-sm font-medium text-white bg-amber-700 hover:bg-amber-800 rounded-lg transition-colors">
                <PlusIcon class="h-4 w-4" />
                New Entry
            </Link>
        </template>
    </AdminHeader>

    <div class="bg-white border-t border-stone-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-stone-200">
                <thead class="bg-stone-50">
                    <tr>
                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Version
                        </th>
                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Date
                        </th>
                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Description
                        </th>
                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-4 py-2.5 text-right text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-stone-100">
                    <tr
                        v-for="entry in entries"
                        :key="entry.id"
                        class="hover:bg-amber-50/50 transition-colors">
                        <td class="px-4 py-2.5 text-sm font-semibold text-stone-900">
                            {{ entry.version }}
                        </td>
                        <td class="px-4 py-2.5 text-sm text-stone-600 whitespace-nowrap">
                            {{ formatDate(entry.date) }}
                        </td>
                        <td class="px-4 py-2.5 text-sm text-stone-600 max-w-md">
                            <p class="truncate">{{ entry.description }}</p>
                        </td>
                        <td class="px-4 py-2.5 text-sm">
                            <span
                                v-if="entry.published_at"
                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                Published
                            </span>
                            <span
                                v-else
                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-stone-100 text-stone-600">
                                Draft
                            </span>
                        </td>
                        <td class="px-4 py-2.5 text-right">
                            <div class="flex items-center justify-end gap-x-1">
                                <Link
                                    :href="`/platform/changelog/${entry.id}/edit`"
                                    class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-stone-600 hover:text-stone-900 hover:bg-stone-100 rounded transition-colors">
                                    Edit
                                </Link>
                                <button
                                    @click="togglePublish(entry)"
                                    class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded transition-colors"
                                    :class="entry.published_at
                                        ? 'text-amber-700 hover:text-amber-800 hover:bg-amber-50'
                                        : 'text-green-700 hover:text-green-800 hover:bg-green-50'">
                                    {{ entry.published_at ? 'Unpublish' : 'Publish' }}
                                </button>
                                <button
                                    @click="deleteEntry(entry)"
                                    class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded transition-colors">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="entries.length === 0">
                        <td colspan="5" class="px-4 py-8 text-center text-sm text-stone-500">
                            No changelog entries yet.
                            <Link href="/platform/changelog/create" class="text-amber-700 hover:text-amber-800 font-medium">Create one</Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AdminHeader from '../Partials/AdminHeader.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useEventBus } from '@vueuse/core';
import { PlusIcon } from '@heroicons/vue/20/solid';

defineOptions({ layout: AdminLayout });

const entries = computed(() => usePage().props.entries);
const notificationBus = useEventBus('roast-notification');

const formatDate = (dateString) => {
    const date = new Date(dateString + 'T00:00:00');
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};

const togglePublish = (entry) => {
    router.put(`/platform/changelog/${entry.id}/publish`, {}, {
        onSuccess: () => {
            notificationBus.emit('show', {
                title: entry.published_at ? 'Entry unpublished.' : 'Entry published.',
            });
        },
    });
};

const deleteEntry = (entry) => {
    if (!confirm(`Delete version ${entry.version}? This cannot be undone.`)) return;

    router.delete(`/platform/changelog/${entry.id}`, {
        onSuccess: () => {
            notificationBus.emit('show', { title: 'Changelog entry deleted.' });
        },
    });
};
</script>
