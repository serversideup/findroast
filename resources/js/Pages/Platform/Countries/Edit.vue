<template>
    <Head title="Countries" />

    <AdminHeader 
        :title="'Edit '+country.name"
        :breadcrumbs="[
            { label: 'Platform Settings', to: '/platform' },
            { label: 'Countries', to: '/platform/countries' },
            { label: 'Edit '+country.name, to: '#'}
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
            <DangerButton type="button" @click="deleteCountry()">Delete</DangerButton>
            <div class="flex items-center gap-x-6">
                <Link href="/platform/countries" class="text-sm font-semibold leading-6 text-gray-900">Cancel</Link>
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

const country = computed(() => usePage().props.country);

const form = useForm({
    _method: 'PUT',
    name: country.value.name,
    slug: country.value.slug
});

const notificationBus = useEventBus('roast-notification');

const submit = () => {
    router.post('/platform/countries/'+country.value.id, form.data(), {
        onSuccess: () => {
            notificationBus.emit('show', {
                title: 'Country Updated',
            });
        }
    });
};

const deleteCountry = () => {
    router.post('/platform/countries/'+country.value.id, {
        _method: 'DELETE'
    }, {
        onSuccess: () => {
            notificationBus.emit('show', {
                title: 'Country Deleted',
            });
        }
    });
};
</script>