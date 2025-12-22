<template>
    <Head title="Messages" />

    <AdminHeader 
        :title="'Messages'"
        :breadcrumbs="[
            { label: 'Platform Settings', to: '/platform'},
            { label: 'Messages', to: '#'}
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
                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                        Email
                                </th>
                                <th
                                    scope="col"
                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                        Created At
                                </th>
                                <th
                                    scope="col"
                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                        Responded To
                                </th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                    <span class="sr-only">View</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="message in messages" :key="message.id"
                                class="hover:bg-gray-50">
                                <td class="pl-4 pr-3 py-3.5 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ message.name }}
                                </td>
                                <td class="pl-4 pr-4 py-3.5 whitespace-nowrap text-sm font-medium sm:pl-0">
                                    {{ message.email }}
                                </td>
                                <td class="pl-4 pr-4 py-3.5 whitespace-nowrap text-sm font-medium sm:pl-0">
                                    {{ message.created_at }}
                                </td>
                                <td class="pl-4 pr-4 py-3.5 whitespace-nowrap text-sm font-medium sm:pl-0">
                                    {{ message.responded_to }}
                                </td>
                                <td class="pl-3 pr-4 py-3.5 whitespace-nowrap text-right text-sm font-medium">
                                    <button @click="viewMessage(message)" class="text-gray-600 hover:text-indigo-900">
                                        View
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="messages.length === 0">
                                <td class="pl-4 pr-3 py-3.5 whitespace-nowrap text-sm font-medium text-gray-900 text-center" colspan="5">
                                    No messages found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <ViewMessageDrawer />
</template>

<script setup>
import AdminHeader from '../Partials/AdminHeader.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
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
</script>