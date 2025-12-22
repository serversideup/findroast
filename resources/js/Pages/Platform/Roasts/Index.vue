<template>
    <Head title="Roasts" />

    <AdminHeader 
        :title="'Roasts'"
        :breadcrumbs="[
            { label: 'Platform Settings', to: '/platform'},
            { label: 'Roasts', to: '#'}
        ]">
            
    </AdminHeader>

    <div class="max-w-screen-xl mx-auto lg:px-8">
        <div class="flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <table class="min-w-full whitespace-nowrap table-fixed border-spacing-0 border-separate margin-0">
                        <thead>
                            <tr>
                                <th
                                    scope="col"
                                    class="left-0 top-0  text-left text-sm font-semibold text-gray-900 bg-white sm:pl-0">
                                        Name
                                </th>
                                <th
                                    scope="col"
                                    class="py-3.5 pl-3 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                        Primary Image
                                </th>
                                <th scope="col" class="relative text-sm font-semibold text-left py-3.5 pl-3 pr-4 sm:pr-0">
                                    Flavor Notes
                                </th>
                                <th scope="col" class="relative text-sm font-semibold text-left py-3.5 pl-3 pr-4 sm:pr-0">
                                    Countries
                                </th>
                                <th scope="col" class="relative text-sm font-semibold text-left py-3.5 pl-3 pr-4 sm:pr-0">
                                    Processes
                                </th>
                                <th scope="col" class="relative text-sm font-semibold text-left py-3.5 pl-3 pr-4 sm:pr-0">
                                    Elevations
                                </th>
                                <th scope="col" class="relative text-sm font-semibold text-left py-3.5 pl-3 pr-4 sm:pr-0">
                                    Varieties
                                </th>
                                <th scope="col" class="relative text-sm font-semibold text-left py-3.5 pl-3 pr-4 sm:pr-0">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="roast in roasts.data" :key="roast.id"
                                @click="editRoast(roast)"
                                class="hover:bg-gray-50 cursor-pointer">
                                <td class="whitespace-nowrap text-sm font-medium text-gray-900 border-b border-[#E4E7EC]">
                                    {{ roast.company.name }}:<br> {{ roast.name }}
                                </td>
                            
                                <td class="whitespace-nowrap text-sm font-medium text-gray-900 border-b border-[#E4E7EC]">
                                    <img 
                                        v-if="roast.primary_image != null && roast.primary_image != ''" 
                                        :src="'/storage/'+roast.primary_image" 
                                        :alt="roast.name" class="w-10 h-10 object-cover" />
                                </td>
                                <td class="pl-3 whitespace-nowrap text-sm font-medium text-gray-900 border-b border-[#E4E7EC]">
                                    <ul>
                                        <li v-for="flavorNote in roast.flavor_notes" :key="flavorNote.id">{{ flavorNote.name }}</li>
                                    </ul>
                                </td>
                                <td class="pl-3 whitespace-nowrap text-sm font-medium text-gray-900 text-left border-b border-[#E4E7EC]">
                                    <ul>
                                        <li v-for="country in roast.countries" :key="country.id">{{ country.name }}</li>
                                    </ul>
                                </td>
                                <td class=" pl-3 whitespace-nowrap text-sm font-medium text-gray-900 text-left border-b border-[#E4E7EC]">
                                    <ul>
                                        <li v-for="process in roast.processes" :key="process.id">{{ process.name }}</li>
                                    </ul>
                                </td>
                                <td class="pl-3 whitespace-nowrap text-sm font-medium text-gray-900 text-left border-b border-[#E4E7EC]">
                                    <ul>
                                        <li v-for="elevation in roast.elevations" :key="elevation.id">{{ elevation.name }}</li>
                                    </ul>
                                </td>
                                <td class="pl-3 whitespace-nowrap text-sm font-medium text-gray-900 text-left border-b border-[#E4E7EC]">
                                    <ul>
                                        <li v-for="variety in roast.varieties" :key="variety.id">{{ variety.name }}</li>
                                    </ul>
                                </td>
                                <td class="pl-3 whitespace-nowrap text-right text-sm font-medium space-x-2 border-b border-[#E4E7EC] py-1">
                                    <DangerButton @click="deleteRoast(roast.id)" @click.native.stop type="button">Delete</DangerButton>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <EditRoastDrawer />
</template>

<script setup>
import AdminHeader from '../Partials/AdminHeader.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import EditRoastDrawer from './Partials/EditRoastDrawer.vue';
import { computed, onMounted, ref } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { useEventBus } from '@vueuse/core';

defineOptions({
    layout: AdminLayout
});

const roasts = computed(() => usePage().props.roasts);

const promptBus = useEventBus('roast-prompt-event-bus');
const notificationBus = useEventBus('roast-notification');
const editRoast = (roast) => {
    promptBus.emit('prompt-edit-roast', roast);
};

const deleteRoast = (id) => {
    router.delete( route('platform.roasts.delete', { roast: id } ), {
        onSuccess: () => {
            notificationBus.emit('show',{
                title: 'Roast deleted'
            })
        }
    } );
};
</script>