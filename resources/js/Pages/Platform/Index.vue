<template>
    <Head title="Platform Settings" />

    <AdminHeader 
        :title="'Platform Settings'"
        :breadcrumbs="[
            { label: 'Platform Settings', to: '#'}
        ]"/>

    <div class="grid grid-cols-3 gap-3 max-w-5xl mx-auto mt-8">
        <Link href="/platform/amenities" class="p-6 bg-white rounded-lg shadow-md border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900">Amenities</h2>
            <p class="text-gray-500">Manage the amenities available.</p>
        </Link>

        <Link href="/platform/brew-methods" class="p-6 bg-white rounded-lg shadow-md border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900">Brew Methods</h2>
            <p class="text-gray-500">Manage the brew methods available.</p>
        </Link>

        <Link href="/platform/drink-options" class="p-6 bg-white rounded-lg shadow-md border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900">Drink Options</h2>
            <p class="text-gray-500">Manage the drink options available.</p>
        </Link>
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