<template>
    <Head title="Drink Options" />

    <AdminHeader 
        :title="'Drink Options'"
        :breadcrumbs="[
            { label: 'Platform Settings', to: '/platform'},
            { label: 'Drink Options', to: '#'}
        ]">
        <template #actions>
            <PrimaryLink href="/platform/drink-options/create">
                Create Drink Option
            </PrimaryLink>
        </template>
    </AdminHeader>

    <div class="max-w-screen-xl mx-auto">
        <div class="mt-8 flow-root">
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
                            <tr v-for="drinkOption in drinkOptions" :key="drinkOption.id"
                                class="hover:bg-gray-50">
                                <td class="pl-4 pr-3 py-3.5 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <img v-show="drinkOption.icon" :src="drinkOption.icon" class="w-8 h-8 rounded-full inline-block mr-2" alt="icon"/>
                                    {{ drinkOption.name }}
                                </td>
                                <td class="pl-3 pr-4 py-3.5 whitespace-nowrap text-right text-sm font-medium">
                                    <Link :href="`/platform/drink-options/${drinkOption.id}/edit`" class="text-gray-600 hover:text-indigo-900">
                                        Edit
                                    </Link>
                                </td>
                            </tr>
                            <tr v-if="drinkOptions.length === 0">
                                <td class="pl-4 pr-3 py-3.5 whitespace-nowrap text-sm font-medium text-gray-900 text-center" colspan="2">
                                    No drink options found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
    layout: AppLayout
};
</script>

<script setup>
import AdminHeader from '../Partials/AdminHeader.vue';
import PrimaryLink from '@/Components/PrimaryLink.vue'
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const drinkOptions = computed(() => usePage().props.drinkOptions);
</script>