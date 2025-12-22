<template>
    <div class="flex flex-col min-h-screen w-screen">
        <Header />
        <div class="flex items-start h-screen w-screen">
            <Sidebar />

            <main class="flex-1 pt-20 h-screen overflow-y-auto">
                <slot />
            </main>
        </div>
    </div>

    <Notification />
</template>

<script setup>
import Header from '@/Components/Header.vue';
import Sidebar from '@/Components/Admin/Sidebar.vue';
import Notification from '@/Components/Notification.vue';
</script>