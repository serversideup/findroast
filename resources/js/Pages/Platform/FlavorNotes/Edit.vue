<template>
    <Head title="Flavor Notes" />

    <AdminHeader 
        :title="'Edit '+flavorNote.name"
        :breadcrumbs="[
            { label: 'Platform Settings', to: '/platform' },
            { label: 'Flavor Notes', to: '/platform/flavor-notes' },
            { label: 'Edit '+flavorNote.name, to: '#'}
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
        <div class="max-w-md mt-6 flex items-center justify-between gap-x-6">
            <DangerButton type="button" @click="deleteFlavorNote()">Delete</DangerButton>
            <div class="flex items-center gap-x-6">
                <Link href="/platform/flavor-notes" class="text-sm font-semibold leading-6 text-gray-900">Cancel</Link>
                <PrimaryButton>Update</PrimaryButton>
            </div>
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
import DangerButton from '@/Components/DangerButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { computed } from 'vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { useEventBus } from '@vueuse/core';

const flavorNote = computed(() => usePage().props.flavorNote);

const form = useForm({
    _method: 'PUT',
    name: flavorNote.value.name,
    slug: flavorNote.value.slug
});

const notificationBus = useEventBus('roast-notification');

const submit = () => {
    router.post('/platform/flavor-notes/'+flavorNote.value.id, form.data(), {
        onSuccess: () => {
            notificationBus.emit('show', {
                title: 'Flavor Note Updated',
            });
        }
    });
};

const deleteFlavorNote = () => {
    router.post('/platform/flavor-notes/'+flavorNote.value.id, {
        _method: 'DELETE'
    }, {
        onSuccess: () => {
            notificationBus.emit('show', {
                title: 'Flavor Note Deleted',
            });
        }
    });
};
</script>