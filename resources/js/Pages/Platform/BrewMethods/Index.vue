<template>
    <Head title="Brew Methods" />

    <AdminHeader
        :title="'Brew Methods'"
        :count="brewMethods.total">
        <template #actions>
            <button
                @click="promptCreateBrewMethod()"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-amber-700 hover:bg-amber-800 rounded-lg transition-colors">
                Create Brew Method
            </button>
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
                        <th scope="col" class="px-4 py-2.5 text-right text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-stone-100">
                    <tr
                        v-for="brewMethod in brewMethods.data"
                        :key="brewMethod.id"
                        class="hover:bg-amber-50/50 cursor-pointer transition-colors"
                        @click="promptEditBrewMethod(brewMethod)">
                        <td class="px-4 py-2.5 text-sm font-medium text-stone-900">
                            <div class="flex items-center gap-3">
                                <img
                                    v-if="brewMethod.icon"
                                    :src="brewMethod.icon"
                                    class="w-8 h-8 rounded object-cover border border-stone-200"
                                    alt="icon"/>
                                <div v-else class="w-8 h-8 rounded bg-stone-100 flex items-center justify-center">
                                    <span class="text-stone-400 text-xs">N/A</span>
                                </div>
                                <span>{{ brewMethod.name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-2.5 text-right">
                            <button
                                @click.stop="promptEditBrewMethod(brewMethod)"
                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-amber-700 hover:text-amber-800 hover:bg-amber-50 rounded transition-colors">
                                Edit
                            </button>
                        </td>
                    </tr>
                    <tr v-if="brewMethods.data.length === 0">
                        <td colspan="2" class="px-4 py-8 text-center text-sm text-stone-500">
                            No brew methods found
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Pagination :data="brewMethods" />
    </div>

    <CreateBrewMethodDrawer />
    <EditBrewMethodDrawer />
</template>

<script setup>
import AdminHeader from '../Partials/AdminHeader.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import CreateBrewMethodDrawer from './Partials/CreateBrewMethodDrawer.vue';
import EditBrewMethodDrawer from './Partials/EditBrewMethodDrawer.vue';
import Pagination from '@/Components/Admin/Pagination.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useEventBus } from '@vueuse/core';

defineOptions({
    layout: AdminLayout
});

const brewMethods = computed(() => usePage().props.brewMethods);

const promptBus = useEventBus('roast-prompt-event-bus');

const promptCreateBrewMethod = () => {
    promptBus.emit('prompt-create-brew-method');
}

const promptEditBrewMethod = (brewMethod) => {
    promptBus.emit('prompt-edit-brew-method', brewMethod);
}
</script>
