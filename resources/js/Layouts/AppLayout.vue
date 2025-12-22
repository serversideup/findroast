<template>
    <div id="app-layout" class="flex flex-col min-h-screen w-screen">
        <!-- <GlobalSearchDropdown/>      -->
        <MobileHeader/>
        <Header/>
        
        <main class="pt-14 lg:pt-16">
            <slot />
        </main>

        <Notification/>
        <!-- <CompaniesCreateModal/>-->
        <!-- <GlobalNotification/>
        <ContributionsModal/>
        <GlobalVerify/>
        <GlobalFloatingMenu/>
        <GlobalAppFooter v-show="showFooter" v-if="PLATFORM == 'web'"/> -->
    </div>
</template>

<script setup>
import Header from '@/Components/Header.vue';
import MobileHeader from '@/Components/MobileHeader.vue';
import Notification from '@/Components/Notification.vue';
</script>