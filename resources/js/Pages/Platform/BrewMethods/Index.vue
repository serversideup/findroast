<template>
    <Head title="Platform Settings" />

    <AdminHeader 
        :title="'Platform Settings'"
        :breadcrumbs="[
            { label: 'Platform Settings', to: '/platform'},
            { label: 'Brew Methods', to: '#'}
        ]"/>

    <div class="max-w-5xl mx-auto mt-8">
        
    </div>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
    layout: AppLayout
};
</script>

<script setup>
import AdminHeader from './Partials/AdminHeader.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>