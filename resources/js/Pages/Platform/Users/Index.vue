<template>
    <Head title="Users" />

    <AdminHeader
        :title="'Users'"
        :count="users.total" />

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
                            Created At
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-stone-100">
                    <tr
                        v-for="user in users.data"
                        :key="user.id"
                        class="hover:bg-amber-50/50 transition-colors">
                        <td class="px-4 py-2.5 text-sm font-medium text-stone-900">
                            {{ user.name }}
                        </td>
                        <td class="px-4 py-2.5 text-sm text-stone-600">
                            {{ user.email }}
                        </td>
                        <td class="px-4 py-2.5 text-sm text-stone-600">
                            {{ user.created_at }}
                        </td>
                    </tr>
                    <tr v-if="users.data.length === 0">
                        <td colspan="3" class="px-4 py-8 text-center text-sm text-stone-500">
                            No users found
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Pagination :data="users" />
    </div>
</template>

<script setup>
import AdminHeader from '../Partials/AdminHeader.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Admin/Pagination.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineOptions({
    layout: AdminLayout
});

const users = computed(() => usePage().props.users);
</script>
