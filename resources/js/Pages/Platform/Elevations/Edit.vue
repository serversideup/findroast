<template>
    <Head title="Elevations" />

    <AdminHeader 
        :title="'Edit '+elevation.name"
        :breadcrumbs="[
            { label: 'Platform Settings', to: '/platform' },
            { label: 'Elevations', to: '/platform/elevations' },
            { label: 'Edit '+elevation.name, to: '#'}
        ]"/>

    <form class="max-w-screen-xl mx-auto mt-8 lg:px-8 flex flex-col space-y-4" @submit.prevent="submit()">
        <div class="max-w-md">
            <InputLabel for="name">Name</InputLabel>
            <TextInput name="name" class="w-full mt-1" v-model="form.name"/>
        </div>
        <div class="max-w-md mt-6 flex items-center justify-end gap-x-6">
            <Link href="/platform/elevations" class="text-sm font-semibold leading-6 text-gray-900">Cancel</Link>
            <PrimaryButton>Update</PrimaryButton>
        </div>
    </form>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
    layout: AppLayout
};
</script>

<script setup>
import AdminHeader from '../Partials/AdminHeader.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { computed } from 'vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { useEventBus } from '@vueuse/core';

const elevation = computed(() => usePage().props.elevation);

const form = useForm({
    _method: 'PUT',
    name: elevation.value.name,
});

const notificationBus = useEventBus('roast-notification');

const submit = () => {
    router.post('/platform/elevations/'+elevation.value.id, form.data(), {
        onSuccess: () => {
            notificationBus.emit('show', {
                title: 'Elevation Updated',
            });
        }
    });
};
</script>