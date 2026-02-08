<template>
    <Head :title="company.name +' - Cafes'" />

    <AdminHeader
        :title="`${company.name} - Cafes`"
        :count="cafes.total">
        <template #actions>
            <Link
                :href="`/platform/companies/${company.id}/cafes/create`"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-amber-700 hover:bg-amber-800 rounded-lg transition-colors">
                Add Cafe
            </Link>
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
                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Address
                        </th>
                        <th scope="col" class="px-4 py-2.5 text-right text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-stone-100">
                    <tr
                        v-for="cafe in cafes.data"
                        :key="cafe.id"
                        class="hover:bg-amber-50/50 cursor-pointer transition-colors"
                        @click="viewCafe(cafe)">
                        <td class="px-4 py-2.5 text-sm font-medium text-stone-900">
                            {{ cafe.name }}
                        </td>
                        <td class="px-4 py-2.5 text-sm">
                            <span
                                :class="[
                                    'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium',
                                    cafe.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-stone-100 text-stone-800'
                                ]">
                                {{ cafe.status }}
                            </span>
                        </td>
                        <td class="px-4 py-2.5 text-sm text-stone-600">
                            <div v-if="cafe.address || cafe.city">
                                <span v-if="cafe.address">{{ cafe.address }}, </span>
                                <span v-if="cafe.city">{{ cafe.city }}</span>
                                <span v-if="cafe.country === 'US' && cafe.state">, {{ cafe.state }}</span>
                                <span v-if="cafe.country === 'AU' && cafe.territory">, {{ cafe.territory }}</span>
                                <span v-if="cafe.country === 'CA' && cafe.province">, {{ cafe.province }}</span>
                            </div>
                            <span v-else class="text-stone-400 text-xs">—</span>
                        </td>
                        <td class="px-4 py-2.5 text-right">
                            <Link
                                :href="`/platform/companies/${cafe.company_id}/cafes/${cafe.id}`"
                                @click.stop
                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-amber-700 hover:text-amber-800 hover:bg-amber-50 rounded transition-colors">
                                View
                            </Link>
                        </td>
                    </tr>
                    <tr v-if="cafes.data.length === 0">
                        <td colspan="4" class="px-4 py-8 text-center text-sm text-stone-500">
                            No cafes found
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div v-if="cafes.data.length > 0" class="flex items-center justify-between border-t border-stone-200 px-4 py-3 bg-stone-50">
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
                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-stone-700 bg-white border border-stone-300 rounded-lg hover:bg-stone-50 transition-colors">
                    Previous
                </Link>
                <Link
                    v-if="cafes.next_page_url"
                    :href="cafes.next_page_url"
                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-stone-700 bg-white border border-stone-300 rounded-lg hover:bg-stone-50 transition-colors">
                    Next
                </Link>
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
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const company = computed(() => usePage().props.company);
const cafes = computed(() => usePage().props.cafes);

const viewCafe = (cafe) => {
    // Handled by Link in the Actions column
};
</script>
