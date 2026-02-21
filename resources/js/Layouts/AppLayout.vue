<template>
    <div id="app-layout" class="flex flex-col min-h-screen w-screen">
        <MobileHeader/>
        <Header/>

        <main class="pt-14 lg:pt-16">
            <slot />
        </main>

        <Notification/>

        <NewReleaseModal v-if="latestChangelog" :entry="latestChangelog" />
    </div>
</template>

<script setup>
import Header from '@/Components/Header.vue';
import MobileHeader from '@/Components/MobileHeader.vue';
import Notification from '@/Components/Notification.vue';
import NewReleaseModal from '@/Components/NewReleaseModal.vue';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const latestChangelog = computed(() => usePage().props.latest_changelog);
</script>