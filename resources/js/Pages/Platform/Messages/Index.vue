<template>
    <Head title="Messages" />

    <AdminHeader
        :title="'Messages'"
        :count="messages.total" />

    <div class="bg-white border-t border-stone-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-stone-200">
                <thead class="bg-stone-50">
                    <tr>
                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Subject
                        </th>
                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Created At
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
                        v-for="message in messages.data"
                        :key="message.id"
                        class="hover:bg-amber-50/50 cursor-pointer transition-colors"
                        @click="viewMessage(message)">
                        <td class="px-4 py-2.5 text-sm font-medium text-stone-900">
                            {{ message.name }}
                        </td>
                        <td class="px-4 py-2.5 text-sm text-stone-600">
                            {{ message.email }}
                        </td>
                        <td class="px-4 py-2.5 text-sm text-stone-600">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-stone-100 text-stone-800">
                                {{ formatSubject(message.subject) }}
                            </span>
                        </td>
                        <td class="px-4 py-2.5 text-sm text-stone-600">
                            {{ formatDate(message.created_at) }}
                        </td>
                        <td class="px-4 py-2.5 text-sm">
                            <span
                                v-if="message.responded_to"
                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                Responded
                            </span>
                            <span
                                v-else
                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">
                                Pending
                            </span>
                        </td>
                        <td class="px-4 py-2.5 text-right">
                            <button
                                @click.stop="viewMessage(message)"
                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-amber-700 hover:text-amber-800 hover:bg-amber-50 rounded transition-colors">
                                View
                            </button>
                        </td>
                    </tr>
                    <tr v-if="messages.data.length === 0">
                        <td colspan="6" class="px-4 py-8 text-center text-sm text-stone-500">
                            No messages found
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Pagination :data="messages" />
    </div>

    <ViewMessageDrawer />
</template>

<script setup>
import AdminHeader from '../Partials/AdminHeader.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Admin/Pagination.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useEventBus } from '@vueuse/core';
import ViewMessageDrawer from './Partials/ViewMessageDrawer.vue';

defineOptions({
    layout: AdminLayout
});

const messages = computed(() => usePage().props.messages);

const promptBus = useEventBus('roast-prompt-event-bus');

const viewMessage = (message) => {
    promptBus.emit('prompt-view-message', message);
}

const formatSubject = (subject) => {
    return subject
        .split('_')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
}

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}
</script>
