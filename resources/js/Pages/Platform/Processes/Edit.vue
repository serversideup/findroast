<template>
    <Head title="Processes" />

    <AdminHeader 
        :title="'Edit '+process.name"
        :breadcrumbs="[
            { label: 'Platform Settings', to: '/platform' },
            { label: 'Processes', to: '/platform/processes' },
            { label: 'Edit '+process.name, to: '#'}
        ]"/>

    <form class="max-w-screen-xl mx-auto mt-8 lg:px-8 flex flex-col space-y-4" @submit.prevent="submit()">
        <div class="max-w-md">
            <InputLabel for="name">Name</InputLabel>
            <TextInput name="name" class="w-full mt-1" v-model="form.name"/>
        </div>
        <div class="max-w-md">
            <InputLabel for="slug">Slug</InputLabel>
            <TextInput name="slug" class="w-full mt-1" v-model="form.slug"/>
        </div>
        <div class="max-w-md mt-6 flex items-center justify-end gap-x-6">
            <Link href="/platform/processes" class="text-sm font-semibold leading-6 text-gray-900">Cancel</Link>
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

const process = computed(() => usePage().props.process);

const form = useForm({
    _method: 'PUT',
    name: process.value.name,
    slug: process.value.slug
});

const notificationBus = useEventBus('roast-notification');

const submit = () => {
    router.post('/platform/processes/'+process.value.id, form.data(), {
        onSuccess: () => {
            notificationBus.emit('show', {
                title: 'Process Updated',
            });
        }
    });
};
</script>