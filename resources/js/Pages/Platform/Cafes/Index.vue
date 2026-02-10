<template>
    <Head title="Cafes" />

    <AdminHeader
        title="Cafes"
        :count="cafes.total">
        <template #actions>
            <Link
                href="/platform/cafes/create"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-amber-700 hover:bg-amber-800 rounded-lg transition-colors shadow-sm">
                <PlusIcon class="h-4 w-4" />
                Add Cafe
            </Link>
        </template>
    </AdminHeader>

    <div class="bg-white border-t border-stone-200 shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-stone-200">
                <thead class="bg-stone-50">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Address
                        </th>
                        <th scope="col" class="px-6 py-3.5 text-right text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-stone-100">
                    <tr
                        v-for="cafe in cafes.data"
                        :key="cafe.id"
                        class="hover:bg-amber-50/50 transition-colors group">
                        <td class="px-6 py-4 text-sm font-medium text-stone-900">
                            {{ cafe.name }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span
                                :class="[
                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                    cafe.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-stone-100 text-stone-800'
                                ]">
                                {{ cafe.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-stone-600">
                            <div v-if="cafe.address || cafe.city">
                                <span v-if="cafe.address">{{ cafe.address }}, </span>
                                <span v-if="cafe.city">{{ cafe.city }}</span>
                                <span v-if="cafe.country === 'US' && cafe.state">, {{ cafe.state }}</span>
                                <span v-if="cafe.country === 'AU' && cafe.territory">, {{ cafe.territory }}</span>
                                <span v-if="cafe.country === 'CA' && cafe.province">, {{ cafe.province }}</span>
                            </div>
                            <span v-else class="text-stone-400 text-xs">No address</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <Link
                                    :href="`/platform/cafes/${cafe.id}/edit`"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-amber-700 hover:text-amber-800 hover:bg-amber-50 rounded-lg transition-colors">
                                    Edit
                                </Link>
                                <Link
                                    :href="`/platform/cafes/${cafe.id}`"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-stone-700 hover:text-stone-800 hover:bg-stone-100 rounded-lg transition-colors">
                                    View
                                </Link>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="cafes.data.length === 0">
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center mb-3">
                                    <BuildingStorefrontIcon class="h-6 w-6 text-stone-400" />
                                </div>
                                <p class="text-sm font-medium text-stone-900 mb-1">No cafes found</p>
                                <p class="text-sm text-stone-500">Get started by adding your first cafe location.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div v-if="cafes.data.length > 0" class="flex items-center justify-between border-t border-stone-200 px-6 py-4 bg-stone-50">
            <div class="text-sm text-stone-600">
                Showing
                <span class="font-medium text-stone-900">{{ cafes.from }}</span>
                to
                <span class="font-medium text-stone-900">{{ cafes.to }}</span>
                of
                <span class="font-medium text-stone-900">{{ cafes.total }}</span>
                results
            </div>
            <div class="flex gap-2">
                <Link
                    v-if="cafes.prev_page_url"
                    :href="cafes.prev_page_url"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-stone-700 bg-white border border-stone-300 rounded-lg hover:bg-stone-50 transition-colors shadow-sm">
                    Previous
                </Link>
                <Link
                    v-if="cafes.next_page_url"
                    :href="cafes.next_page_url"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-stone-700 bg-white border border-stone-300 rounded-lg hover:bg-stone-50 transition-colors shadow-sm">
                    Next
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AdminHeader from '../Partials/AdminHeader.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { PlusIcon, BuildingStorefrontIcon } from '@heroicons/vue/24/outline';
import { computed } from 'vue';

defineOptions({
    layout: AdminLayout
});

const cafes = computed(() => usePage().props.cafes);
</script>
