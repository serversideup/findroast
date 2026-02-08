<template>
    <div class="flex flex-col min-h-screen w-screen bg-stone-50">
        <AdminHeader @toggle-sidebar="toggleSidebar" />

        <div class="flex pt-14 h-screen">
            <AdminSidebar :isCollapsed="sidebarCollapsed" />

            <main class="flex-1 h-full overflow-y-auto">
                <slot />
            </main>
        </div>
    </div>

    <Notification />
</template>

<script setup>
import { ref } from 'vue';
import AdminHeader from '@/Components/Admin/AdminHeader.vue';
import AdminSidebar from '@/Components/Admin/AdminSidebar.vue';
import Notification from '@/Components/Notification.vue';

const sidebarCollapsed = ref(false);

const toggleSidebar = () => {
    sidebarCollapsed.value = !sidebarCollapsed.value;
};
</script>