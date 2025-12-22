<template>
    <Head title="View current coffee offerings, log brews, save recipes, and more!" />

    <Hero/>
    <!-- <Features/> -->
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
    layout: AppLayout
};
</script>

<script setup>
import Hero from './Partials/Hero.vue';
// import Features from './Partials/Features.vue';
import { Head } from '@inertiajs/vue3';
</script>