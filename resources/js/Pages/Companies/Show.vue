<template>
    <Head :title="company.name" />

    <CompanyHeader />

    <CompanyInfo />
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
    layout: AppLayout
};
</script>

<script setup>
import CompanyHeader from './Partials/CompanyHeader.vue';
import CompanyInfo from './Partials/CompanyInfo.vue';
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';

const company = computed(() => usePage().props.company);
</script>